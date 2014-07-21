import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.Scanner;


public class Weights {
	private static Object[] turtle;

	public static void main (String [] args){
		int n = 0;
		int weight, strength;
		String test = "300 1000\r\n1000 1200\r\n200 600\r\n100 101";
		ArrayList<int[]> turtles= new ArrayList<int[]>();
		Scanner d = new Scanner(test);
		while (d.hasNextInt()){
			weight = d.nextInt();
			strength = d.nextInt();
			//System.out.print(weight);
			//System.out.print(strength);
			int temp [] = {weight, strength, (strength-weight)};
			turtles.add(n,temp);
			n++;
		}
		/*for (int t = 0; t<n; t++){
			System.out.println(turtles.get(t)[1]);
			System.out.println(turtles.get(t)[2]);
		}*/
		Collections.sort(turtles, new Comparator<int[]>()
				{
			public int compare (int [] a, int [] b)
			{
				return Integer.compare(b[2], a[2]);

			}
		});

		/*System.out.println("---sorted---");
		for (int t = 0; t<n; t++){
			System.out.println(turtles.get(t)[2]);
		}

		int [] stack = new int[n];*/
		int strengthLeft = turtles.get(0)[2];
		int numberStacked = 1;
		//calculate now
		for (int t = 1; t < n; t++){
			//take best strength-weight first always
			//array is already sorted as such
			if (strengthLeft >= turtles.get(t)[0]){
				numberStacked++;
				strengthLeft = strengthLeft - turtles.get(t)[2];
			}
		}
		d.close();

		System.out.print(numberStacked);
	}

}
