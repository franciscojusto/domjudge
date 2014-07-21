import java.util.*;

class Main {
	public static void main (String [] args) {
		Scanner sc = new Scanner(System.in);
		
		int numCases = sc.nextInt();
		while(numCases-- > 0) {
			double toHigh = sc.nextDouble();
			double toLow = sc.nextDouble();
			double norHigh = sc.nextDouble();
			double norLow = sc.nextDouble();
			
			double toAvg = (toHigh - norHigh);
			double norAvg = (toLow - norLow);
			
			String str = "";
			double avg = (toAvg + norAvg) / 2;
			if (avg > 0) 
				str = "ABOVE";
			else 
				str = "BELOW";
			
			double diff = Math.abs(avg);
			System.out.printf("%.1f DEGREE(S) %s NORMAL", diff, str);
			if (numCases != 0) 
				System.out.println();
		}
	}
}