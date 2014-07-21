import java.awt.Point;
import java.io.File;
import java.util.ArrayList;
import java.util.PriorityQueue;
import java.util.Scanner;


public class Frogger {

	public static void main(String[] args) throws Exception {
		Scanner fin = new Scanner(System.in);
		int caseNum = 0;
		boolean first = true;
		
		while(true){
			caseNum++;
			int numStones = fin.nextInt();
			
			if(numStones == 0)
				break;
			
			double[][] arr = new double[numStones][numStones];	
			Point[] pArr = new Point[numStones];
			
			for(int i = 0; i < numStones; i++){
				pArr[i] = new Point(fin.nextInt(), fin.nextInt());
			}
			
			//Fill distances
			for(int i = 0; i < numStones; i++){
				for(int j = 0; j < numStones; j++){
					arr[i][j] = pArr[i].distance(pArr[j]);
				}
			}
			for(int i = 0; i < numStones; i++)
			{
				arr[i][i] = Double.MAX_VALUE;
			}
			
			for(int k = 0; k < numStones; k++){
				for(int i = 0; i < numStones; i++)
				{
					for(int j = 0; j < numStones; j++){
						arr[j][i] = arr[i][j] = Math.min(arr[i][j],  Math.min(arr[i][k], arr[k][j]));
					}
				}
			}
			
			if(first){
				first = false;
			}
			else{
				System.out.println("\n");
			}
			
			System.out.println("Scenario #" + caseNum);
			System.out.printf("Frog Distance = %.3f", arr[0][1]);
		}
	}
}

