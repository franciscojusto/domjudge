import java.util.HashSet;
import java.util.Scanner;


public class EfficiencyOverload {
	public static void main(String[] args) {
		Scanner br = new Scanner(System.in);
		
		int cases = br.nextInt();
		br.nextLine();
		for(int c=0; c<cases; c++){
			char[] path = br.nextLine().toCharArray();
			
			// If the path has both 'U' and 'D', or the path has both 'L' and 'R',
			// then that means he could have taken less of the other.
			// E.g.: UULLLD -> He could have just taken 1 U instead of 2 U's and a D
			// To solve, throw all characters into a hashset, and see if it contains either pair
			HashSet<Character> characters = new HashSet<Character>();
			for(char ch : path){
				characters.add(ch);
			}
			
			if(characters.contains('D') && characters.contains('U') ||
			   characters.contains('L') && characters.contains('R')){
				System.out.println("Just won't do.");
			} else {
				System.out.println("Success!");
			}
		}
	}
}
