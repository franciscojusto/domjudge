import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.Scanner;

public class Problem3 {
	public static class Turtle implements Comparable {
		public int weight, str;

		public Turtle(int weight, int str) {
			this.weight = weight;
			this.str = str;
		}

		@Override
		public int compareTo(Object other) {
			return weight - ((Turtle) other).weight;
		}

		public String toString() {
			return "(w=" + weight + ", s=" + str + ")";
		}
	}

	@SuppressWarnings("unchecked")
	public static void main(String[] args) {
		ArrayList<Turtle> turtles = new ArrayList<Turtle>();
		Scanner scan = new Scanner(System.in);
		while (scan.hasNextInt()) {
			turtles.add(new Turtle(scan.nextInt(), scan.nextInt()));
		}
		Collections.sort(turtles);

		System.out.print(solve(turtles));
	}
	
	public static int solve(ArrayList<Turtle> turtles){
		int totalWeight=0;
		int totalTurtles=0;
		while(!turtles.isEmpty()){
			Turtle t = turtles.get(0);
			turtles.remove(0);
			if(t.str >= t.weight+totalWeight){
				totalWeight+=t.weight;
				totalTurtles++;
			}
				
		}
		return totalTurtles;
	}
}
