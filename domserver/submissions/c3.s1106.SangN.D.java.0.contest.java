import java.util.Scanner;

public class contest {
	public static void main(String[] arg) {
		Scanner input = new Scanner(System.in);
		int days = input.nextInt();

		while (days != 0) {
			days--;
			float todayHigh = input.nextInt();
			float todayLow = input.nextInt();
			float normalHigh = input.nextInt();
			float normalLow = input.nextInt();

			float average = (todayHigh - normalHigh) / 2
					+ (todayLow - normalLow) / 2;
			if (average < 0) {
				average = average * -1;
				if (days == 1) {
					System.out.println(average + " DEGREE(S) BELOW NORMAL");
				} else {
					System.out.println(average + " DEGREE(S) BELOW NORMAL");
				}
			} else {
				if (days == 1) {
					System.out.println(average + " DEGREE(S) ABOVE NORMAL");
				} else {
					System.out.println(average + " DEGREE(S) ABOVE NORMAL");
				}
			}
		}

	}
}
