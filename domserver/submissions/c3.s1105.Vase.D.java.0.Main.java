import java.util.Scanner;

class Main {
	public static void main(String...args) {
		Scanner in = new Scanner(System.in);

		int nData = Integer.parseInt(in.nextLine());
		for (int i=0; i<nData; i++) {
			int todayHigh = Integer.parseInt(in.next());
			int todayLow = Integer.parseInt(in.next());
			int normHigh = Integer.parseInt(in.next());
			int normLow = Integer.parseInt(in.next());

			float todayAvg = (todayHigh + todayLow) / 2.0f;
			float normAvg = (normHigh + normLow) / 2.0f;
			
			float avgDiff = Math.abs(todayAvg - normAvg);
			if (todayAvg > normAvg) System.out.print(avgDiff + " DEGREE(S) ABOVE NORMAL");
			else System.out.print(avgDiff + " DEGREE(S) BELOW NORMAL");
			if (i<nData-1) System.out.println();
		}
	}
}