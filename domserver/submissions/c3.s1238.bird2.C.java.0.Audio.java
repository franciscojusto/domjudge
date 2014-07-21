import java.util.*;

class Main {
	public static void main (String [] args) {
		Scanner sc = new Scanner(System.in);
		StringBuilder out = new StringBuilder();
		
		for (int h = 1;; h++) {
			
			int numNodes = sc.nextInt();
			int numConnections = sc.nextInt();
			int numQueries = sc.nextInt();
			if (numNodes == 0 && numConnections == 0 && numQueries == 0) {
				break;
			}
			out.append(String.format("Case #%d\n", h));
			int [][] nodes = new int [numNodes+1][numNodes+1];
			for (int i = 0; i <= numNodes; i++) {
				for (int k = 0; k <= numNodes; k++) {
					if (i != k) {
						nodes[i][k] = -1;
					}
				}
			}	
			
			for (int i = 0; i < numConnections; i++) {
				int first = sc.nextInt();
				int second = sc.nextInt();
				int weight = sc.nextInt();
				
				if (nodes[first][second] == -1)
					nodes[first][second] = weight;
				else 
					nodes[first][second] = Math.min(weight, nodes[first][second]);
					
				if (nodes[second][first] == -1)
					nodes[second][first] = weight;
				else 
					nodes[second][first] = Math.min(weight, nodes[second][first]);
			}
			
			PriorityQueue<Edge> que = new PriorityQueue<Edge>();
			for (int i = 1; i <= numNodes; i++) {
				for (int k = 1; k <= numNodes; k++) {
					if (i == k)
						continue;
					if (nodes[i][k] != -1) {
						que.add(new Edge(k, i, i, nodes[i][k]));
					}
				}
				
				while (!que.isEmpty()) {
					Edge e = que.poll();
					//System.out.printf("Origin: %d To: %d\n", e.origin, e.to);
					if (e.weight <= nodes[e.origin][e.to]) {
						//nodes[e.origin][e.to] = e.weight;
						//nodes[e.to][e.origin] = e.weight;
						for (int k = 0; k <= numNodes; k++) {
							if (k == e.to || nodes[e.to][k] == -1)
								continue;
							int w = Math.max(e.weight, nodes[e.to][k]);
							if (w < nodes[e.origin][k] || nodes[e.origin][k] == -1) {
								nodes[e.origin][k] = w;
								nodes[k][e.origin] = w;
								e.from = e.to;
								e.to = k;
								e.weight = w;
								que.add(e);
							}
						}
					}
				}
			}
			for (int i = 0; i < numQueries; i++) {
				int a = sc.nextInt();
				int b = sc.nextInt();
				if (nodes[a][b] != -1)
					out.append(nodes[a][b]+"\n");
				else 
					out.append("no path\n");
				
			}
			//out.append("\n");
		}
		System.out.print(out.toString().trim());
	}
}
class Edge implements Comparable<Edge> {
	
	int to;
	int from;
	int weight;
	int origin;
	public Edge(int a, int b, int c, int d) {
		to = a;
		from = b;
		origin = c;
		weight = d;

	}
	
	public int compareTo(Edge e) {
		return weight - e.weight;
	}
}
