import java.io.IOException;
import java.util.ArrayList;
import java.util.Scanner;


public class ProblemD {
	public static void main(String[] args) throws IOException {
		Scanner scan = new Scanner(System.in);
		int numOfTests = scan.nextInt();
		
		
		ArrayList<Double> solutions = new ArrayList<Double>();
		for(;numOfTests>0; numOfTests--){
			double solution = solve(scan);
			solutions.add(solution);
		}
		for(int i=0; i<solutions.size()-1; i++){
			double val = solutions.get(i);
			System.out.printf("%.1f", Math.abs(val));
			System.out.println(" DEGREE(S) "+ (val<0?"BELOW " : "ABOVE ") + "NORMAL");
		}
		int i = solutions.size()-1;
		double val = solutions.get(i);
		System.out.printf("%.1f", Math.abs(val));
		System.out.print(" DEGREE(S) "+ (val<0?"BELOW " : "ABOVE ") + "NORMAL");
	}
	
	public static double solve(Scanner scan){
		int today = scan.nextInt() + scan.nextInt();
		int norm = scan.nextInt() + scan.nextInt();
		
		return ((today-(double)norm) / 2.0);
	}
}
