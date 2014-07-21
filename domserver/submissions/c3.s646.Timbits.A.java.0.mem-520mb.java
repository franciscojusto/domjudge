public class Test
{
    static int test[];
    public static void main(String[] args) {
        System.out.println("520MB test");
        int s = 520 * 1024 * 1024;
        test = new int [s / Integer.SIZE];
        for (int i=s-100000;i<s;i++)
            test[i]=i-500;
        System.out.println("done");
    }
}
