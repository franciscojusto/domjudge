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
			return weight - ((Turtle)other).weight;
		}

		public String toString() {
			return "(w=" + weight + ", s=" + str + ")";
		}
	}

	@SuppressWarnings("unchecked")
	public static void main(String[] args) {
		ArrayList<Turtle> turtles = new ArrayList<Turtle>();
		Scanner scan = new Scanner("300 1000\n1000 1200\n200 600\n100 101\n");
		while (scan.hasNextInt()) {
			turtles.add(new Turtle(scan.nextInt(), scan.nextInt()));
		}
		Collections.sort(turtles);
		int answer = 0;
		for(int i=0; i<5000 && !turtles.isEmpty(); i++){
			int n = solve(turtles);
			if(n>answer)
				answer = n;
		}
		
		System.out.println(answer);
			
	}
	
	public static int solve(ArrayList<Turtle> turtles){
		int totalWeight=0;
		int totalTurtles=0;
		for(int i=0; i<turtles.size();i++){
			Turtle t = turtles.get(i);
			if(t.str >= t.weight+totalWeight){
				totalWeight+=t.weight;
				totalTurtles++;
				turtles.remove(i--);
			}
				
		}
		return totalTurtles;
	}
}
