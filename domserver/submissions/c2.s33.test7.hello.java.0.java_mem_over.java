import java.io.*;

class Main
{
	public static void main(String[] args) throws IOException
	{
		int[] a = new int[1024*1024*16*2];
		a[10000]=5;
		System.out.println(a[10000]);
	}
}
