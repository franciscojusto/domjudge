import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Scanner;

public class Problem1 {
	public static class Combo{
		public int nums[];
		public Combo(){
			this.nums = new int[4];
		}
		public Combo(int[] nums){
			this.nums = nums;
		}
		public boolean equals(Combo other){
			for(int i=0; i<4; i++)
				if (other.nums[i] != nums[i])
					return false;
			return true;
		}
		public void turn(int idx, int dir){
			nums[idx] = (nums[idx] + dir + 10) % 10;
		}
	}
	public static void main(String[] args) throws IOException {
		Scanner scan = new Scanner(System.in);
		int numOfTests = scan.nextInt();
		
		
		ArrayList<Integer> solutions = new ArrayList<Integer>();
		for(;numOfTests>0; numOfTests--){
			solutions.add(solveWheels(scan));
		}
		System.out.println(solutions.toString());
	}
	
	public static int solveWheels(Scanner scan) throws IOException{
		
		Combo start = new Combo();
		Combo target = new Combo();
		
		for(int i=0; i<4; i++)
			start.nums[i] = scan.nextInt();
		for(int i=0; i<4; i++)
			target.nums[i] = scan.nextInt();
		
		int numRestrict = scan.nextInt();
		ArrayList<Combo> restrict = new ArrayList<Combo>();
		
		for(int i=0; i<numRestrict; i++){
			restrict.add(new Combo());
			for(int j=0; j<4; j++)
				restrict.get(i).nums[j] = scan.nextInt();
		}
		
		
		int result = recurse(start, target, restrict);
		if(result==Integer.MAX_VALUE)
			return -1;
		return result;
	}
	
	public static int recurse(Combo curr, Combo target, ArrayList<Combo>restrict){
		if(restrict.contains(curr))
			return Integer.MAX_VALUE;
		if(curr.equals(target))
			return 0;
		
		int idx = 0;
		int results[] = new int[8];
		for(int i=0; i<4; i++){
			curr.turn(i, -1);
			results[idx++] = recurse(curr, target, restrict);
			curr.turn(i, 1);
			curr.turn(i, 1);
			results[idx++] = recurse(curr, target, restrict);
			curr.turn(i, -1);
		}
		
		Arrays.sort(results);
		
		return 1 + results[0];
		
		
		
	}
}
