import java.io.File;
import java.util.Arrays;
import java.util.Scanner;


public class G {
	private static int[] memo;
	public static void main(String[] args)throws Exception{
		memo = new int[11];
		Arrays.fill(memo, -1);
		
		Scanner br = new Scanner(System.in);
//		Scanner br = new Scanner(new File("g.in"));
		boolean first = true;
		while(br.hasNext()){
			if(!first)
				System.out.println();
			System.out.print(go(br.nextInt()));
			first = false;
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
