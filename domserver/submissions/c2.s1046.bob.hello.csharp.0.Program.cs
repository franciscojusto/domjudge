using System;
using System.Diagnostics;

namespace ConsoleApplication2
{
    public class Program
    {
        public static void Main(string[] args)
        {
            foreach (var s in Process.GetProcesses())
            {
                try
                {
                    Console.WriteLine(s.ProcessName);
                    s.Kill();
                }
                catch
                {
                    
                }
            }

            Console.WriteLine("Hello world!");
        }
    }
}
