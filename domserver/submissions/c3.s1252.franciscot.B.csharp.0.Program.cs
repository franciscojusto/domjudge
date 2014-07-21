using System;

namespace CodeJamB
{
    class Program
    {
        static void Main(string[] args)
        {
            string[] data;
            data = new string[Convert.ToInt32(100)];
            int k = 0;
            string input = Console.ReadLine();
            double aux = 100;
            double dst = 0.0;
            int n = 1;
            do
            {
                for (var i = 0; i < Convert.ToInt32(input); i++)
                {
                    data[k] = Console.ReadLine();
                    
                    k++;
                }
                for (var i = 0; i < Convert.ToInt32(input); i++)
                {
                    string[] numbers = data[i].Split(' ');
                    dst = Math.Sqrt(Math.Pow(Convert.ToDouble(numbers[0]), 2) + Math.Pow(Convert.ToDouble(numbers[1]), 2));

                    if (dst != 0.0)
                    {
                        if (dst < aux)
                        {
                            aux = dst;
                        }
                        
                    }
                    
                    //Math.Sqrt(
                }
                Console.WriteLine("Scenario #{0}", n++);
                Console.WriteLine("Frog Distance = {0}", dst);
                dst = 0;

            } while (Convert.ToInt32(input = Console.ReadLine()) != 0);

            //for (var i = 1; i < Convert.ToInt32(x); i++)
            //{
            //    data[i] = Console.ReadLine();
            //}
        }
    }
}
