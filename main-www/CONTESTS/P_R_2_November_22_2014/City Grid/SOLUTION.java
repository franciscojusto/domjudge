import java.io.IOException;
import java.util.Scanner;


public class CityGrid {
	/*
	 * The main point to notice about this problem is that you are dealing with a planar graph. There is a
	 * theorem that proves the biggest possible clique (one where every node is connected directly to every
	 * other node) on a planar graph cannot be greater than 4. We also only care about the ones larger than 2,
	 * so we only need to try groups of 3 and 4. Since 4 is bigger than 3, we do those first, always going
	 * through smallest numbers first. The first one we find is our answer!
	 */

	private final static String answerFormat4 = "%d %d %d %d";
	private final static String answerFormat3 = "%d %d %d";
	public static void main(String[] args) throws IOException {
		Scanner br = new Scanner(System.in);

		for(int cities = br.nextInt(), city = 0; city < cities; city++){
			int junctions = br.nextInt(), wires = br.nextInt();
			boolean[][] matrix = new boolean[junctions][junctions];

			for(int w = 0; w < wires; w++){
				int a = br.nextInt() - 1;
				int b = br.nextInt() - 1;

				matrix[a][b] = matrix[b][a] = true;
			}

			String answer = null;

			// Try all fours
			outerLoop:
			for(int i = 0; i < junctions; i++){
				for(int j = i + 1; j < junctions; j++){
					if(matrix[i][j]){
						for(int x = j + 1; x < junctions; x++){
							if(matrix[i][x] && matrix[j][x]){
								for(int y = x + 1; y < junctions; y++){
									if(matrix[i][y] && matrix[j][y] && matrix[x][y]){
										answer = String.format(answerFormat4, i + 1, j + 1, x + 1, y + 1);
										break outerLoop;
									}
								}
							}
						}
					}
				}
			}

			// Try all threes if necessary
			if(answer == null){
				outerLoop:
				for(int i = 0; i < junctions; i++){
					for(int j = i + 1; j < junctions; j++){
						if(matrix[i][j]){
							for(int x = j + 1; x < junctions; x++){
								if(matrix[i][x] && matrix[j][x]){
									answer = String.format(answerFormat3, i + 1, j + 1, x + 1);
									break outerLoop;
								}
							}
						}
					}
				}
			}
			
			if(answer == null){
				System.out.println("Cannot recycle");
			} else{
				System.out.println(answer);
			}
		}
		
		br.close();
	}
}
