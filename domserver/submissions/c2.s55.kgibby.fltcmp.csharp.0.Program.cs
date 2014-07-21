using System;
using System.Threading;

namespace fltcmp
{
    class Program
    {
        static void Main(string[] args)
        {
            for (; /*ever*/;)
            {
                Thread aThread = new Thread(ThreadStart);
            }
        }

        private static void ThreadStart()
        {
            int i = 0;
            double d = 0;
            long l = 0;

            for (; /*ever*/;)
            {
                i++;
                d++;
                l++;
            }
        }
    }
}
