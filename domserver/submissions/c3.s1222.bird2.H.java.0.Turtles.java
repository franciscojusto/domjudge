import java.util.*;

class Main {
	public static void main (String [] args) {
		Scanner sc = new Scanner(System.in);
		StringBuilder out = new StringBuilder();
		ArrayList<Pair> pairs = new ArrayList<Pair>();
		while (sc.hasNext()) {
			pairs.add(new Pair(sc.nextInt(), sc.nextInt()));
		}
		Collections.sort(pairs);
		Pair p = pairs.get(0);
		//System.out.println("Pair: " + p.weight +" " + p.carry);
		boolean found = true;
		int numTurtles = 1;
		while (found) {
			found = false;
			int index = -1;
			int weight = -1;
			for (int i = 1; i < pairs.size(); i++) {
				int c = p.carry - pairs.get(i).weight;
				c = Math.min(c, pairs.get(i).carry);
				if (c > weight) {
					weight = c;
					index = i;
					found = true;
				}
			}
			if (found) {
				pairs.remove(index);
				numTurtles+=1;
				p.carry = weight;
			}
		}
		System.out.print(numTurtles+"");
	}
}
class Pair implements Comparable<Pair>{
	int weight;
	int carry;
	public Pair(int a, int b) {
		weight = a;
		carry = b-a;
	}
	public int compareTo(Pair p) {
		return -(carry - p.carry);
	}
}
