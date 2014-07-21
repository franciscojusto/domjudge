using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ulticoder
{
    class Program
    {
        static void Main(string[] args)
        {
            Console.WriteLine("Please enter an input");
            var test = Console.ReadLine();
            if (test == "1")
            {
                Console.Clear();
                Console.WriteLine("Hello World");
                Console.Read();
            }            
        }
    }
}
