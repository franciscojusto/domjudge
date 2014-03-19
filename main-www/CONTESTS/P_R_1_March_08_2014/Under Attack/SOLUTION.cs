using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;

namespace UnderAttack
{
    public class Tower
    {
        public List<int> Neighors;

        public Tower()
        {
            Neighors = new List<int>();
        }
    }

    class Program
    {
        public static Tower[] Towers;
        public static int[] MvcYes;
        public static int[] MvcNo;
        public static int[] MvcMaybe;

        static void Main(string[] args)
        {
            int nCases = int.Parse(Console.ReadLine().Trim());

            for (int c = 0; c < nCases; c++)
            {
                string[] line = Console.ReadLine().Trim().Split(' ');
                int nTowers = int.Parse(line[0]);
                int nPaths = int.Parse(line[1]);

                Towers = new Tower[nTowers];
                MvcYes = new int[nTowers];
                MvcNo = new int[nTowers];
                MvcMaybe = new int[nTowers];
                for (int i = 0; i < nTowers; i++)
                {
                    Towers[i] = new Tower();
                    MvcYes[i] = -1;
                    MvcNo[i] = -1;
                    MvcMaybe[i] = -1;
                }

                for (int i = 0; i < nPaths; i++)
                {
                    string[] path = Console.ReadLine().Trim().Split(' ');
                    int towerFrom = int.Parse(path[0]) - 1;
                    int towerTo = int.Parse(path[1]) - 1;

                    if (towerFrom < towerTo)
                    {
                        Towers[towerFrom].Neighors.Add(towerTo);
                    }
                    else
                    {
                        Towers[towerTo].Neighors.Add(towerFrom);
                    }
                }

                if (Towers.Any())
                {
                    Console.WriteLine(MinVertexCoverMaybe(0));
                }
                else
                {
                    Console.WriteLine(0);
                }
            }
        }

        public static int MinVertexCoverMaybe(int tower)
        {
            if (MvcMaybe[tower] < 0)
            {
                MvcMaybe[tower] = Math.Min(MinVertexCoverYes(tower), MinVertexCoverNo(tower));
            }
            return MvcMaybe[tower];
        }

        public static int MinVertexCoverYes(int tower)
        {
            if (MvcYes[tower] < 0)
            {
                int coverResult = 1;
                foreach (int neighbor in Towers[tower].Neighors)
                {
                    coverResult += MinVertexCoverMaybe(neighbor);
                }
                MvcYes[tower] = coverResult;
            }

            return MvcYes[tower];
        }

        public static int MinVertexCoverNo(int tower)
        {
            if (MvcNo[tower] < 0)
            {
                int coverResult = 0;
                foreach (int neighbor in Towers[tower].Neighors)
                {
                    coverResult += MinVertexCoverYes(neighbor);
                }
                MvcNo[tower] = coverResult;
            }

            return MvcNo[tower];
        }
    }
}
