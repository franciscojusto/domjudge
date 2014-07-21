using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace CompilerError
{
    class Program
    {
        static void Main(string[] args)
        {
#error This is an error.
            Console.WriteLine("This was supposed to be an error.");
        }
    }
}
