using System;
using System.IO;

public class Hello1
{
    public static void Main()
    {
       File.WriteAllText("output.txt", "Hello, world!");
    }
}