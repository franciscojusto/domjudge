import java.util.Arrays;
import java.util.LinkedList;
import java.util.Scanner;


/*
 * The object of this program is to determine the fastest path between two points.
 * A quick and easy solution for this is to use a Breadth First Search, which visits
 * every possible grid point but in the fastest way possible.
 * 
 * There aren't a lot of rectangles allowed in this problem, so I take the route of
 * marking where I cannot go as soon as I read each rectangle. From then on, I only
 * go where I haven't been already AND is not on a rectangle.
 */
public class BombsAway {
	
	// Helper arrays for the number of moves
	//                          R  U  L   D
	static int[] dx = new int[]{1, 0, -1, 0};
	static int[] dy = new int[]{0, 1, 0, -1};
	
	public static void main(String[] args){
		Scanner br = new Scanner(System.in);
		int levels = br.nextInt();
		
		String answerFormat = "Set the timer to be %d seconds.%n";
		String impossible = "This level is impossible!";
		
		for(int l = 0; l < levels; l++){
			int width = br.nextInt(),
				height = br.nextInt();
			
			// Computers like grids to be "height-first"
			boolean[][] canWalk = new boolean[height][width];
			
			// Initially, I can walk anywhere
			for(boolean[] walk : canWalk){
				Arrays.fill(walk, true);
			}
			
			// Starting/Ending coordinates, -1 for 0-indexing
			int sx = br.nextInt() - 1,
				sy = br.nextInt() - 1,
				tx = br.nextInt() - 1,
				ty = br.nextInt() - 1;
			
			int rects = br.nextInt();
			for(int r = 0; r < rects; r++){
				// Corner coordinates, -1 for 0-indexing
				int tlx = br.nextInt() - 1,
					tly = br.nextInt() - 1,
					brx = br.nextInt() - 1,
					bry = br.nextInt() - 1;
				
				// Go through each grid point within the rectangle
				// and mark as unwalkable
				for(int i = tlx; i <= brx; i++)
					for(int j = tly; j >= bry; j--)
						canWalk[j][i] = false;
			}
			
			// Initialize the queue with the starting coordinates and
			// an initial distance of 5 (since we have to add 5 to the answer anyways)
			// This queue will always be in the format (y, x, distance, y, x, distance...)
			// so each triplet denotes a single possible state, and tells me the fastest
			// way to any coordinate I've visited
			LinkedList<Integer> queue = new LinkedList<Integer>();
			queue.add(sy);
			queue.add(sx);
			queue.add(5);
			
			canWalk[sy][sx] = false;
			while(!queue.isEmpty()){
				int y = queue.removeFirst(),
					x = queue.removeFirst(),
					dist = queue.removeFirst();
				
				// We have reached our destination, so print and exit
				if(y == ty && x == tx){
					System.out.printf(answerFormat, dist);
					break;
				}
				
				// Run through each of our four possible moves
				for(int i = 0; i < dx.length; i++){
					int nx = x + dx[i],
						ny = y + dy[i];
					
					// If the coordinates are within range           AND we haven't been here/no rectangle
					if(nx >= 0 && nx < width && ny >=0 && ny < height && canWalk[ny][nx]){
						
						// Mark we have been here
						canWalk[ny][nx] = false;
						
						// Add this current state to the queue
						queue.add(ny);
						queue.add(nx);
						queue.add(dist + 1);
					}
				}
				
				// Since we only add newly visited states to the queue, we can guarantee we won't
				// visit a state twice.
			}
			
			// If we haven't reached our destination by now, it is impossible
			if(canWalk[ty][tx]){
				System.out.println(impossible);
			}
		}
	}
}
