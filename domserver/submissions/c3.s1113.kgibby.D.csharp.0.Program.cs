using System;
using System.Collections.Generic;
using System.Data.Odbc;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace TechStar_Ulitcoder_Competition
{
    class Program
    {
        static void Main(string[] args)
        {
            int Number_Of_Datasets = int.Parse(Console.ReadLine());
            string[] dataLines = new string[Number_Of_Datasets];
            for (int i = 0; i < Number_Of_Datasets; i++)
            {
                dataLines[i] = Console.ReadLine();
            }
            for (int i = 0; i < Number_Of_Datasets; i++)
            {
                string Dataset = dataLines[i];
                string[] data = Dataset.Split(' ');
                int Actual_High = int.Parse(data[0]);
                int Actual_Low = int.Parse(data[1]);
                int Avg_High = int.Parse(data[2]);
                int Avg_Low = int.Parse(data[3]);

                double Actual_Avg = (Actual_High + Actual_Low)/2.0;
                double Avg_Avg = (Avg_High + Avg_Low)/2.0;
                double difference = Actual_Avg - Avg_Avg;

                if (difference > 0)
                {
                    Console.WriteLine("{0} DEGREE(S) ABOVE NORMAL", difference.ToString("F1"));
                }
                else
                {
                    Console.WriteLine("{0} DEGREE(S) BELOW NORMAL", Math.Abs(difference).ToString("F1"));
                }
            }
        }
    }
}
