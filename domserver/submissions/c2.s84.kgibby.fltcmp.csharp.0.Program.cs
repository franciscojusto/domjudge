using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace fltcmp2
{
    class Program
    {
        static void Main(string[] args)
        {
            Recurse(10);
        }

        private static void Recurse(int x)
        {
            if(true)
                Recurse(x);
            else
            {
                Recurse(x);
            }
        }
    }
}
