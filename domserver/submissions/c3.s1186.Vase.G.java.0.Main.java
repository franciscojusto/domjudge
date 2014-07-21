import java.util.Scanner;

class Main {
	public static void main(String...args) {
		Scanner in = new Scanner(System.in);

		int nSets = Integer.parseInt(in.nextLine());

		for (int i=0; i<nSets; i++) {
			int dataSet = Integer.parseInt(in.nextLine());
			System.out.println(numShakes(dataSet));
		}
	}

	public static int numShakes(int pairs) {
		if (pairs == 1) return 1;
		else if (pairs == 2) return 2;
		else {
			return (numShakes(pairs - 1) + (int)Math.pow(3, pairs-2));
		}
	}
}