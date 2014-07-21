import java.util.Arrays;
import java.util.Scanner;


public class B {
	public static void main(String[] args){
		Scanner br = new Scanner(System.in);
		int stones;
		int c = 1;
		while((stones = br.nextInt()) != 0){
			if(c!=1){
				System.out.println();
			}
			int[][] points = new int[stones][2];
			for(int i=0;i<points.length;i++){
				points[i][0] = br.nextInt();
				points[i][1] = br.nextInt();
			}

			double[][] dists = new double[stones][stones];
			for(int i=0;i<stones;i++)
				for(int j=0;j<stones;j++)
					dists[i][j] = Math.sqrt(Math.pow(points[i][0] - points[j][0], 2) + Math.pow(points[i][1] - points[j][1], 2));
			
			for(int k=0;k<stones;k++)
				for(int i=0;i<stones;i++)
					for(int j=0;j<stones;j++)
					{
						if(i != j && j != k && i != k)
							dists[i][j] = Math.min(dists[i][j], Math.min(dists[i][k], dists[k][j]));
					}
			
			System.out.printf("Scenario #%d%nFrog Distance = %.3f%n", c++, dists[0][1]);
		}
	}
}
