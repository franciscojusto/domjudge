import java.util.Arrays;
import java.util.Scanner;


public class C {
	public static void main(String[] args){
		Scanner br = new Scanner(System.in);
		int nodes = br.nextInt(), edges = br.nextInt(), queries = br.nextInt();
		int c = 1;
		while(nodes != 0 || edges != 0 || queries != 0){
			int[][] mins = new int[nodes][nodes];
			boolean[][] map = new boolean[nodes][nodes];
			for(int i=0;i<nodes;i++)
				for(int j=0;j<nodes;j++)
					mins[i][j] = 987654321;
			
			for(int i=0;i<edges;i++){
				int a = br.nextInt() - 1,
						b = br.nextInt() - 1;
				mins[a][b] = Math.min(mins[a][b], br.nextInt());
				mins[b][a] = mins[a][b];
				map[a][b] = map[b][a] = true;
			}
			
//			for(int[] m:mins)
//				System.out.println(Arrays.toString(m));
			
			for(int k=0;k<nodes;k++)
				for(int i=0;i<nodes;i++)
					for(int j=0;j<nodes;j++)
						if(map[i][k] && map[k][j]){
							if(!map[i][j]){
								mins[i][j] = Math.max(mins[i][k], mins[k][j]);
							}
							
							mins[i][j] = Math.min(mins[i][j], Math.max(mins[i][k], mins[k][j]));
							map[i][j] |= (map[i][k] && map[k][j]);
						}
			
			if(c != 1)
				System.out.print("\n");
			System.out.printf("Case #%d%n", c++);
			for(int q=0;q<queries;q++){
				int a = br.nextInt(),
						b = br.nextInt();
				if(!map[a-1][b-1]){
					System.out.println("No path");
				} else {
					System.out.println(mins[a-1][b-1]);
				}
			}
			
			nodes = br.nextInt(); edges = br.nextInt(); queries = br.nextInt();
		}
	}
}
