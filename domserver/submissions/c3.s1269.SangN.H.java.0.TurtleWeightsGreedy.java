import java.util.ArrayList;
import java.util.Collections;
import java.util.List;
import java.util.Scanner;

// Greedy Knapsack

public class TurtleWeightsGreedy {
	private static Scanner input;

	public static void main(String[] args) {
		List<Turtle> turtles = new ArrayList<Turtle>();
		
		input = new Scanner(System.in);
		Turtle bestT = null;
		while (input.hasNext()) {
			Turtle t = new Turtle(input.nextInt(), input.nextInt());
			if (bestT == null) {
				bestT = t;
			} else if (bestT.compareTo(t) < 0) {
				turtles.add(t);
			} else {
				turtles.add(bestT);
				bestT = t;
			}
		}

		int result = 0;
		Collections.sort(turtles);
		int i = 0;
		while (i < turtles.size()) {
			if (bestT.capacity() > turtles.get(i).capacity()
					&& bestT.weight + turtles.get(i).weight < bestT.str) {
				result++;
				i = 0;
				bestT = turtles.get(i);
				turtles.remove(bestT);
			} else {
				i++;
			}
		}
		
		System.out.print(result);

	}
}

class Turtle implements Comparable<Turtle> {
	Integer weight;
	Integer str;

	public Turtle(int weight, int str) {
		this.weight = weight;
		this.str = str;
	}

	public int capacity() {
		return str - weight;
	}

	public int compareTo(Turtle t) {
		return compare(this, t);
	}

	public int compare(Turtle t, Turtle s) {
		return -1 * (t.capacity()) + (s.capacity());
	}
}