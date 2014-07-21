package weather;

import java.util.Scanner;

public class weather {
	public static void main (String [] args){
		Scanner d = new Scanner(System.in);
		int testCases = d.nextInt();
		int high;
		int low;
		int nhigh;
		int nlow;
		double avg;
		for (int n = 0; n < testCases; n++){
			high = d.nextInt();
			low = d.nextInt();
			nhigh = d.nextInt();
			nlow = d.nextInt();
			avg = high - nhigh;
			avg = avg + low - nlow;
			avg = avg/2;
			avg = (double)Math.round(avg * 10) / 10;
			if (n == testCases-1){
				//last one
				if (avg > 0){
					System.out.print(avg + " DEGREE(S) ABOVE NORMAL");
				}
				else if (avg < 0){
					avg = avg * -1;
					System.out.print(avg + " DEGREE(S) BELOW NORMAL");
				}
			}
			else{
				if (avg > 0){
					System.out.println(avg + " DEGREE(S) ABOVE NORMAL");
				}
				else if (avg < 0){
					avg = avg * -1;
					System.out.println(avg + " DEGREE(S) BELOW NORMAL");
				}
			}
		}
	}
}
