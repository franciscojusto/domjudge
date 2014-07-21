import java.util.Scanner;


public class Weather {

	public static void main(String[] args) throws Exception{
		Scanner fin = new Scanner(System.in);
		int cases = fin.nextInt();
		
		for(int i = 0; i < cases; i++)
		{
			int high = fin.nextInt();
			int low = fin.nextInt();
			int normHigh = fin.nextInt();
			int normLow = fin.nextInt();
			
			int x = high - normHigh;
			int y = low - normLow;
			
			double answer = (double)(x + y) / 2;
			String aboveBelow = "ABOVE";
			
			if(answer < 0){
				answer *= -1;
				aboveBelow = "BELOW";
			}
			
			System.out.printf("%.1f DEGREE(S) %s NORMAL\n", answer, aboveBelow);
		}

	}

}
