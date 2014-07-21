
import java.util.Scanner;

public class ulticoder {
    public static void main(String[] args) {
        Scanner sc = new Scanner(System.in);
        
        int t = sc.nextInt();
        while(t-- > 0){
            int tl = sc.nextInt(), th = sc.nextInt(), hl = sc.nextInt(), hh = sc.nextInt();
            double ta = (tl + th)/2.0;
            double ha = (hl + hh)/2.0;
            double d = ta - ha;
            
            if(d > 0){
                if(t <= 0){
                    System.out.printf("%.1f DEGREE(S) ABOVE NORMAL", d);                    
                }
                else{
                    System.out.printf("%.1f DEGREE(S) ABOVE NORMAL\n", d);                    
                }
            } else{
                if(t <= 0){
                    System.out.printf("%.1f DEGREE(S) BELOW NORMAL", -1 * d);                    
                }
                else{
                    System.out.printf("%.1f DEGREE(S) BELOW NORMAL\n", -1 * d);                    
                }
            }
        }
    }
}
