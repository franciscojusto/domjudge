import java.util.Scanner;


/*
 The math for this is pretty simple.
 
 We know we have two consecutive numbers, x and y.
 We know y^2 + x^2 = some number A
 We know y^2 - x^2 = some number B
 We are given A - B, call it T
 
 If we subtract the first two equations, we get 2x^2 = A - B, or 2x^2 = T
 
 Now we can solve for x:
 x = sqrt(T / 2)
 And y is simply x + 1.
 
 
 */
public class FreakyNumbers {
	public static void main(String[] args) {
		Scanner br = new Scanner(System.in);
		
		int cases = br.nextInt();
		String format = "The DSDS roots of %d are %d and %d%n";
		
		for(int c=0; c<cases; c++){
			long target = br.nextLong(),
					x = (long)Math.sqrt(target / 2),
					y = x + 1;
			
			System.out.printf(format, target, x, y);
		}
	}
}
