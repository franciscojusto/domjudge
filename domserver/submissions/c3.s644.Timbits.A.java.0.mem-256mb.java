public class Test
{
    static int test[];
    public static void main(String[] args) {
        System.out.println("256MB test");
        test = new int [256 * 1024 * 1024 / Integer.SIZE];
        for (int i=0;i<100;i++)
            test[i]=i*i;
    }
}
