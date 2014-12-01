import java.io.IOException;
import java.util.Scanner;


public class ClownBrothers {
	public static void main(String[] args) throws IOException
	{
		Scanner br = new Scanner(System.in);
		int cases = br.nextInt();
		String larry = "Larry", gary = "Gary", format = "%s won the argument!%n";
		
		for(; cases > 0; cases--){
			int count = 0;
			while(br.next().equals("Ha!")){
				count++;
			}
			
			System.out.printf(format, count % 2 == 0 ? gary : larry);
		}
	}
}
