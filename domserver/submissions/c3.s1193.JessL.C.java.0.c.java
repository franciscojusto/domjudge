import java.util.Scanner;


public class c {

	public static void main(String[] args) throws Exception{
		Scanner fin = new Scanner(System.in);
		int caseNum = 0;
		
		while(true){
		
			caseNum++;
			int numNodes = fin.nextInt();
			int numEdges = fin.nextInt();
			int numQueries = fin.nextInt();
			
			if(numNodes == 0 && numEdges == 0 && numQueries == 0)
				break;
			
			
			int[][] edgeArray = new int[numNodes][numNodes];
			
			for(int i = 0; i < numNodes; i++){
				for(int j = 0; j < numNodes; j++){
					edgeArray[i][j] = Integer.MAX_VALUE;
				}
			}
			
			for (int i = 0; i < numNodes; i++)
			{
				edgeArray[i][i] = 0;
			}
			
			for(int i = 0; i < numEdges; i++){
				int x = fin.nextInt() - 1;
				int y = fin.nextInt() - 1;
				int dec = fin.nextInt();
				
				edgeArray[x][y] = edgeArray[y][x] = dec;
			}
			
			for(int k = 0; k < numNodes; k++){
				for(int i = 0; i < numNodes; i++)
				{
					for(int j = 0; j < numNodes; j++){
						if(edgeArray[i][k] != Integer.MAX_VALUE && edgeArray[k][j] != Integer.MAX_VALUE){
							edgeArray[j][i] = edgeArray[i][j] = Math.min(edgeArray[i][j],  Math.max(edgeArray[i][k], edgeArray[k][j]));
						}
					}
				}
			}
			
			System.out.println("Case #" + caseNum);
			for(int i = 0; i < numQueries; i++){
				int x = fin.nextInt() - 1;
				int y = fin.nextInt() - 1;
				
				if(edgeArray[x][y] == Integer.MAX_VALUE)
					System.out.println("no path");
				else{
					System.out.println(edgeArray[x][y]);
				}
			}
			System.out.println();
		}
	}

}
