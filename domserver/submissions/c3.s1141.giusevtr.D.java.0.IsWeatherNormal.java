import java.util.Scanner;



public class IsWeatherNormal {

	
	void start(){
		Scanner scan = new Scanner(System.in);
		int n = scan.nextInt();
		
		for(int i = 0 ; i < n ; i++){
			int high = scan.nextInt();
			int low = scan.nextInt();
			int highNormal = scan.nextInt();
			int lowNormal = scan.nextInt();
			
			double avg = (high + low)/2.0 ;
			double avgNormal = (highNormal + lowNormal)/2.0;
			if(avg > avgNormal){
				System.out.printf("%.1f DEGREE(S) ABOVE NORMAL", avg - avgNormal);
			}else{
				System.out.printf("%.1f DEGREE(S) BELOW NORMAL", avgNormal - avg);
			}
			System.out.println();
		}
	}
	
	public static void main(String[] args) {
		new IsWeatherNormal().start();
		
	}
}
