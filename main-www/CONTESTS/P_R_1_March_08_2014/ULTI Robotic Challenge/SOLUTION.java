/*
 * The main observation to be made here is that you can go through gates more than once.
 * This means you can go through a cycles as long as you want to, but more importantly,
 * you can break the graph down into strongly connected components, which are groups
 * of gates that you are guaranteed a path, whether direct or indirect, between any pair
 * of gates. Then once you break it down into SCC, you know you can hit every gate in
 * each SCC, so then all you have to do is try starting at every SCC. One hitch here
 * is that you need to save what each SCC ends up as, otherwise you could end up trying
 * to go through the whole graph many times!
 * 
 * A very helpful trick is to use Kosaraju's algorithm for finding SCC's, since it gives
 * you the SCC's back in topological order, meaning you only need to try the topmost
 * SCC's, and if you come across one you have already passed, don't try it again.
 */
import java.io.BufferedReader;
import java.io.FileReader;
import java.io.InputStreamReader;
import java.util.ArrayDeque;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Deque;
import java.util.LinkedList;
import java.util.StringTokenizer;


public class RobotChallenge {
	static ArrayList<Integer>[] map, reverse;
	static int[] points;
	static long[] best;

	// is a gate already in the stack?
	static boolean[] inKosarajusStack;
	static Deque<Integer> kosarajuStack;

	// index is gate, value is index of scc
	static int[] whichScc;

	// number of scc's
	static int numScc;

	// index is the scc index, value is the sum of all gates within scc
	static long[] sccPoints;

	// maps a scc to a list of indeces of other adjacent scc's
	static ArrayList<Integer>[] sccMap; 

	public static void main(String[] args) throws Exception{
		BufferedReader br; // use buffered reader to make sure we can read input quickly
		if(args.length == 0)
			br = new BufferedReader(new InputStreamReader(System.in));
		else
			br = new BufferedReader(new FileReader("robot.in"));
		int cases = Integer.parseInt(br.readLine());
		for(int c=0;c<cases;c++){
			StringTokenizer st = new StringTokenizer(br.readLine());
			int gates = Integer.parseInt(st.nextToken()), tracks = Integer.parseInt(st.nextToken());

			// Kosaraju's uses both the normal version of a graph and its reverse
			map = new ArrayList[gates];
			reverse = new ArrayList[gates];

			points = new int[gates];

			for(int i=0;i<gates;i++){
				points[i] = Integer.parseInt(br.readLine());
				map[i] = new ArrayList<Integer>();
				reverse[i] = new ArrayList<Integer>();
			}

			for(int i=0;i<tracks;i++){
				st = new StringTokenizer(br.readLine());
				int a = Integer.parseInt(st.nextToken()) - 1,
					b = Integer.parseInt(st.nextToken()) - 1;
				map[a].add(b);
				reverse[b].add(a);
			}

			kosarajus();

			// Initialize the new map for scc's
			sccMap = new ArrayList[numScc];
			sccPoints = new long[numScc];

			for(int i=0;i<sccMap.length;i++)
				sccMap[i] = new ArrayList<Integer>();

			// Now all scc's are in the array list, need to find all edges to other scc's
			for(int i=0;i<gates;i++){
				for(int next : map[i])
				{
					// Means we can go from one SCC to another
					if(whichScc[i] != whichScc[next])
						sccMap[whichScc[i]].add(whichScc[next]);
				}

				// Add points to the SCC, so we always have a reference to the SCC's total
				sccPoints[whichScc[i]] += points[i];
			}

			// Will be used to know if we have already calculated this scc
			best = new long[numScc];
			Arrays.fill(best, -1);

			// Max could get bigger than an int
			long max = 0;

			// This order guarantees we start at the very bottom of the topological order
			// so we can use these values as we go up the graph.
			for(int i=0;i<numScc;i++)
			{
				max = Math.max(max, go(i));
			}

			System.out.println(max);
		}
	}

	static void kosarajus(){

		// initialize the data
		kosarajuStack = new ArrayDeque<Integer>();
		inKosarajusStack = new boolean[map.length];
		numScc = 0;
		whichScc = new int[map.length];

		// dfs from every node (doesn't matter which order, so just run through them all)
		// as long as we haven't hit it before, and push it (and all gates it can reach)
		// onto the stack
		for(int i=0;i<map.length;i++)
			if(!inKosarajusStack[i])
				dfs(i);

		// Then, using the reverse, every node that a node can reach becomes it's SCC
		while(!kosarajuStack.isEmpty()){
			int next = kosarajuStack.pop();

			// don't start a new scc if it is already in one
			if(inKosarajusStack[next])
				fillWithBfs(next);
		}
	}

	// By filling the stack with the reverse first, kosaraju's will fill it in reverse
	// topological order. This way, we don't have to worry about StackOverflow
	static void dfs(int start){
		inKosarajusStack[start] = true;
		for(int next:reverse[start])
			if(!inKosarajusStack[next]){
				dfs(next);
			}

		kosarajuStack.push(start);
	}

	// Fill a new SCC with all gates possible from doing a BFS
	static void fillWithBfs(int start) {
		LinkedList<Integer> queue = new LinkedList<Integer>();
		queue.add(start);
		inKosarajusStack[start] = false;

		while(!queue.isEmpty()) {
			int index = queue.removeFirst();
			for(int next : map[index])
				
				// as long as the target is still "in" the stack, we can go to it
				if(inKosarajusStack[next]) {
					queue.add(next);

					// "Remove" element from stack
					inKosarajusStack[next] = false;
				}

			// mark which scc this gate belongs
			whichScc[index] = numScc;
		}

		// Done filling this scc, so that adds one more to the total
		numScc++;
	}

	static long go(int index) {
		// we've been here before
		if(best[index] != -1)
			return best[index];

		long sum = sccPoints[index];
		long maxChild = 0;

		// try going to all other adjacent scc's
		for(int next:sccMap[index]){
			maxChild = Math.max(maxChild, go(next));
		}

		// save our place
		return best[index] = (sum + maxChild);
	}
}
