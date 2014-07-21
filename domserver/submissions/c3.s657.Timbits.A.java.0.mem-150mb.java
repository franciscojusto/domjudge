public class Test
{
    static int test[];
    public static void main(String[] args) {
        System.out.println("150MB test");
        int s = 150 * 1024 * 1024 / (Integer.SIZE/Byte.SIZE);
        test = new int [s];
        for (int i=s-100000;i<s;i++)
            test[i]=i-500;
        System.out.println("done");
    }
}
