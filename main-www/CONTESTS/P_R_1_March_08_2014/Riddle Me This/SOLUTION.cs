using System;
using System.Text;

namespace CodeJam_032014
{
    public class RiddleMeThis
    {
        private static int[,] intersections;
        private static int[,] solution;
        private static int[,] paths;
        private static int bounds;

        static void Main(string[] args)
        {
            StringBuilder lines = new StringBuilder();

            int numTestCases = Int32.Parse(Console.ReadLine());
            do
            {
                int n = Int32.Parse(Console.ReadLine());
                bounds = n * n;
                MarkIntersections(n);

                string line;
                while ((line = Console.ReadLine()) != null)
                {
                    string[] tokens = line.Split(' ');
                    int a = Int32.Parse(tokens[0]);
                    if (tokens.Length == 1 && a == -1)
                        break;
                    int b = Int32.Parse(tokens[1]);
                    RemoveIntersection(a, b);
                }

                TraverseMatrix();
                lines.Append(display_result());
                numTestCases--;

                if (numTestCases != 0)
                    lines.AppendLine();
            }
            while (numTestCases > 0);

            Console.WriteLine(lines.ToString());
        }

        private static StringBuilder display_result()
        {
            var lines = new StringBuilder();
            for (var i = 0; i < bounds; i++)
            {
                if(i > 0)
                    lines.AppendLine();

                for (var j = 0; j < bounds; j++)
                {
                    if (j > 0)
                        lines.Append(" ");
                    lines.Append(solution[i, j]);
                }
            }
            return lines;
        }

        private static void TraverseMatrix()
        {
            solution = new int[bounds, bounds];
            Array.Copy(intersections, 0, solution, 0, intersections.Length);
            paths = new int[bounds, bounds];
            Array.Copy(intersections, 0, paths, 0, intersections.Length);

            // iterate until we visit all intersections
            for (var l = 1; l < bounds; l++)
            {
                for (var i = 0; i < bounds; i++)
                {
                    for (var j = 0; j < bounds; j++)
                    {
                        var counter = 0;
                        for (var k = 0; k < bounds; k++)
                        {
                            // ignore current intersections and ensure i -> k is a known intersection
                            if (k != j && k != i && intersections[i, k] != 0)
                            {
                                var currentPathCount = 0;
                                // if both paths exist, then update current path count
                                if (paths[i, k] != 0 && paths[j, k] != 0)
                                    currentPathCount = paths[i, k] + paths[j, k];

                                // if current path count is shorter than previously recorded OR this is the first recorded path 
                                //   update the path for i -> j
                                if ((currentPathCount > 0 && currentPathCount < paths[i, j]) || paths[i, j] == 0)
                                {
                                    paths[i, j] = currentPathCount;
                                    counter = solution[i, k] * solution[k, j];
                                } // if currentPathCount is the same as previous path count, then we increment the counter
                                else if (currentPathCount == paths[i, j])
                                {
                                    counter += solution[i, k] * solution[k, j];
                                }
                            }
                        }

                        // update solution with current counter
                        // if counter = 0, keep previous solution value 
                        if (counter > 0)
                            solution[i, j] = counter;
                    }
                }
            }
        }

        private static void RemoveIntersection(int a, int b)
        {
            // remove all paths between both intersections
            intersections[a, b] = 0;
            intersections[b, a] = 0;
        }

        private static void MarkIntersections(int n)
        {
            intersections = new int[bounds, bounds];

            for (var i = 0; i < bounds; i++)
            {
                intersections[i, i] = 1;

                // calculate right
                int j = i + 1;
                if (j % n != 0)
                {
                    intersections[i, j] = 1;
                    intersections[j, i] = 1; // mark left since all streets are two-way
                }

                // calculate top
                j = i + n;
                if (j < bounds)
                {
                    intersections[i, j] = 1;
                    intersections[j, i] = 1; // mark bottom since all streets are two-way
                }
            }
        }
    }
}
