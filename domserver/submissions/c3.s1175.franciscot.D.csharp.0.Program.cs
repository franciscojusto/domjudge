using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Security.Cryptography.X509Certificates;
using System.Text;
using System.Threading.Tasks;
using System.Xml;

namespace CodeJamD
{
    class Program
    {
        static void Main(string[] args)
        {
            int[] values;
            values = new int[4];
            //try
            //{
                //using (var sr = new StreamReader("in.txt"))
                //{
                   // var line = sr.ReadToEnd();
                    string[] data;
                    string x = Console.ReadLine();
             
                    data = new string[Convert.ToInt32(x)*4+1];
                    data[0] = x;
                for (var i = 1; i < Convert.ToInt32(data[0])*4+1; i++)
                {
                    data[i] = Console.ReadLine();
                }
                for (var i = 1; i < Convert.ToInt32(data[0])*4+1; i++)
                    {
                        for (var j = 0; j < 4; j++)
                        {
                            values[j] = Convert.ToInt32(data[i]);
                           if (j != 3)
                                i++;
                        }
                        double diffH = Convert.ToDouble(values[0] - values[2])/2;
                        double diffL = Convert.ToDouble(values[1] - values[3])/2;
                        var avg1 = diffH + diffL;
                        if (avg1 >= 0)
                        {
                            Console.WriteLine("{0:F1} DEGREE(S) ABOVE NORMAL", avg1);
                        }
                        else
                        {
                            Console.WriteLine("{0:F1} DEGREE(S) BELOW NORMAL", Math.Abs(avg1));
                        }
                    }
                //}
            //}
            //catch (Exception e)
            //{
            //    Console.WriteLine("The file could not be read:");
            //    Console.WriteLine(e.Message);
            //}
        }
    }
}
