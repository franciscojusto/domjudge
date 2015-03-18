using System;

// Namespace can be whatever you like
namespace Echo.Automation.Platform.PlatformConfig
{
    // Class name can be anything you'd like as well
    public class Winner
    {
        public static void Main()
        {
            int games = int.Parse(Console.ReadLine());

            for (int game = 0; game < games; game++)
            {
                int contestants = int.Parse(Console.ReadLine());
                int max = 0;
                for (int c = 0; c < contestants; c++)
                {
                    max = Math.Max(max, int.Parse(Console.ReadLine()));
                }

                Console.WriteLine("The winning score is... {0} points!", max);
            }
        }
    }
}
