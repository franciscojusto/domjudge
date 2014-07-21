using System;
using System.IO;

public class Hello1
{
    public static void Main()
    {
        throw new Exception(Directory.GetCurrentDirectory());
    }
}