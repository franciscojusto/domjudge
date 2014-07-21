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
			
			double highDiff = (high - highNormal) ;
			double lowDiff = (low - lowNormal);
			
			double avg = (highDiff + lowDiff) /2;
			if(avg > 0){
				System.out.printf("%.1f DEGREE(S) ABOVE NORMAL", avg);
			}else{ 
				avg *= -1;
				System.out.printf("%.1f DEGREE(S) BELOW NORMAL",avg);
			}
			System.out.println();
		}
	}
	
	public static void main(String[] args) {
		new IsWeatherNormal().start();
		
	}
}
