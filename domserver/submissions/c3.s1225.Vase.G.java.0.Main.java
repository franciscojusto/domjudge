import java.util.Scanner;

class Main {
	public static void main(String...args) {
		Scanner in = new Scanner(System.in);

		int[] results = new int[10];
		int index = 0;
		try {
			int dataSet = Integer.parseInt(in.nextLine());
			while (dataSet != 0) {
				System.out.println(numShakes(dataSet) + "\n");
				results[index++] = numShakes(dataSet);
				if (in.hasNextLine()) in.nextLine(); else break;
				if (in.hasNextLine()) dataSet = Integer.parseInt(in.nextLine()); else break;
			}
		} catch (NumberFormatException nfe) {}
		for (int i=0; i<index; i++) {
			//System.out.println(results[i] + "\n");
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