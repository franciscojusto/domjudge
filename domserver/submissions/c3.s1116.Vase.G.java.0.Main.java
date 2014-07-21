import java.util.Scanner;

class Main {
	public static void main(String...args) {
		Scanner in = new Scanner(System.in);

		try {
			int dataSet = Integer.parseInt(in.nextLine());
			while (dataSet != 0) {
				System.out.println(numShakes(dataSet) + "\n");
				dataSet = Integer.parseInt(in.nextLine());
			}
		} catch (NumberFormatException nfe) {}
	}

	public static int numShakes(int pairs) {
		if (pairs == 1) return 1;
		else if (pairs == 2) return 2;
		else {
			return (numShakes(pairs - 1) + (int)Math.pow(3, pairs-2));
		}
	}
}