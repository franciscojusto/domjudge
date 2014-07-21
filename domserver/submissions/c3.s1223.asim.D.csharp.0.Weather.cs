using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace TechStarCodingComp1
{
    class Weather
    {
        static void Main(string[] args)
        {
            int NumberOfCases = Convert.ToInt16(Console.ReadLine());

            for (int i = 0; i < NumberOfCases; i++)
            {
                string[] input = Console.ReadLine().Split();

                double TH = Convert.ToDouble(input[0]);
                double TL = Convert.ToDouble(input[1]);
                double NH = Convert.ToDouble(input[2]);
                double NL = Convert.ToDouble(input[3]);

                double average = ((TH - NH) + (TL - NL)) / 2;

                double signlessav = Math.Abs(average);

                if (average >= 0)
                {
                    Console.WriteLine(string.Format("{0:0.0} DEGREE(S) ABOVE NORMAL", signlessav));
                }
                else
                {
                    Console.WriteLine(string.Format("{0:0.0} DEGREE(S) BELOW NORMAL", signlessav));
                }
            }

            System.Threading.Thread.Sleep(3000);
        }
    }
}
