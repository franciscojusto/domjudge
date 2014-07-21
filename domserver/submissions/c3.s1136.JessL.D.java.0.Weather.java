import java.util.Scanner;


public class Weather {

	public static void main(String[] args) throws Exception{
		Scanner fin = new Scanner(System.in);
		int cases = fin.nextInt();
		boolean first = true;
		
		for(int i = 0; i < cases; i++)
		{
			double high = fin.nextDouble();
			double low = fin.nextDouble();
			double normHigh = fin.nextDouble();
			double normLow = fin.nextDouble();
			
			double x = high - normHigh;
			double y = low - normLow;
			
			double answer = (x + y) / 2;
			String aboveBelow = "ABOVE";
			
			if(answer < 0){
				answer *= -1;
				aboveBelow = "BELOW";
			}
			
			if(!first){
				System.out.println();
			}
			else
			{
				first = false;
			}
			System.out.printf("%.1f DEGREE(S) %s NORMAL", answer, aboveBelow);
		}

	}

}
