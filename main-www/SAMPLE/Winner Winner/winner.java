import java.util.Scanner;


public class Winner {
	public static void main(String[] args){
		
		// I prefer using Scanner, but another method would be to use BufferedReader
		// (http//docs.oracle.com/javase/7/docs/api/java/io/BufferedReader.html)
		// as well as the StringTokenizer to separate the input into usable pieces
		// (http://docs.oracle.com/javase/7/docs/api/java/util/StringTokenizer.html)
		// The important part here is to remember to use System.in either way
		Scanner br = new Scanner(System.in);
		int games = br.nextInt();
		for(int game = 0; game < games; game++){
			int maxScore = 0;
			int contestants = br.nextInt();
			for(int i = 0; i < contestants; i++){
				maxScore = Math.max(maxScore, br.nextInt());
			}
			
			// Print output to standard out (System.out)
			System.out.printf("The winning score is... %d points!%n", maxScore);
		}
	}
}
