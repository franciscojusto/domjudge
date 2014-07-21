import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.Scanner;


public class Weights {
	public static void main (String [] args){
		int n = 0;
		int weight, strength;
		//String test = "300 1000\r\n1000 1200\r\n200 600\r\n100 101";
		ArrayList<int[]> turtles= new ArrayList<int[]>();
		String tempLine;
		String [] tokens;
		Boolean keepGoing = true;
		Scanner d = new Scanner(System.in);
		/*while (keepGoing){
			tempLine = d.nextLine();
			if (tempLine.equals("")){ //|| tempLine.equals("")){ //|| tempLine == "\n" || tempLine == "\r\n" || tempLine == null){
				keepGoing = false;
			}
			else
			{
				tokens = tempLine.split(" ");
				weight = Integer.parseInt(tokens[0]);
				strength = Integer.parseInt(tokens[1]);
				//System.out.print(weight);
				//System.out.print(strength);
				int temp [] = {weight, strength, (strength-weight)};
				turtles.add(n,temp);
				n++;
			}
		}*/
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
			System.out.print(turtles.get(t)[0]);
			System.out.print(turtles.get(t)[1]);
			System.out.println(turtles.get(t)[2]);
		}*/
		//sort weight first
		Collections.sort(turtles, new Comparator<int[]>()
				{
			public int compare (int [] a, int [] b)
			{
				//return Integer.compare(b[2], a[2]);
				return Integer.valueOf(b[0]).compareTo(Integer.valueOf(a[0]));

			}
				});
		// then the diff
		Collections.sort(turtles, new Comparator<int[]>()
				{
			public int compare (int [] a, int [] b)
			{
				//return Integer.compare(b[2], a[2]);
				return Integer.valueOf(b[2]).compareTo(Integer.valueOf(a[2]));

			}
				});

		/*System.out.println("---sorted---");
		for (int t = 0; t<n; t++){
			System.out.print(turtles.get(t)[0]);
			System.out.print(turtles.get(t)[1]);
			System.out.println(turtles.get(t)[2]);
		}*/

		
		int [] strengthLeft = new int [n];
		strengthLeft [0] = turtles.get(0)[2];
		int numberStacked = 1;
		boolean isOkay = true;
		//calculate now
		for (int t = 1; t < n; t++){
			//take best strength-weight first always
			//array is already sorted as such
			for (int z = 0; z < t; z++){
				if (strengthLeft[z] < turtles.get(t)[0]){
					isOkay = false;
				}
			}
			if (isOkay){
				for (int z = 0; z < t; z++){
					strengthLeft[z] = strengthLeft[z] - turtles.get(t)[2];
				}
				numberStacked++;
			}
		}
		d.close();

		System.out.print(numberStacked);
	}

}
