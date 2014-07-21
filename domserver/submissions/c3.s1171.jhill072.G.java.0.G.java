import java.util.Arrays;
import java.util.Scanner;


public class G {
	private static int[] memo;
	public static void main(String[] args){
		memo = new int[11];
		Arrays.fill(memo, -1);
		
		Scanner br = new Scanner(System.in);
		while(br.hasNext()){
			System.out.println(go(br.nextInt()) + "\n");
		}
	}
	
	public static int go(int n){
		if(memo[n] != -1){
			return memo[n];
		}
		
		if(n <= 1){
			return memo[n] = 1;
		}
		
		int sum = 0;
		for(int i=0;i<n;i++){
			sum += go(i) * go(n-i-1);
		}
		
		return memo[n] = sum;
	}
}
