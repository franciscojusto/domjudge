import java.util.Scanner;


public class D {
	public static void main(String[] args){
		Scanner br = new Scanner(System.in);
		int cases = br.nextInt();
		for(int c=0;c<cases;c++)
		{
			int actualHigh = br.nextInt(),
					actualLow = br.nextInt(),
					normalHigh = br.nextInt(),
					normalLow = br.nextInt();
			
			double average = ((actualHigh - normalHigh) + (actualLow - normalLow)) / 2.0;
			
			System.out.printf("%.1f DEGREE(S) %s NORMAL%n", Math.abs(average), average < 0 ? "BELOW" : "ABOVE");
		}
	}
}
