using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;


namespace BombsAway
{
    class Program
    {
        //This solution follows the a* path finding algorithm as describe in https://www.youtube.com/watch?v=KNXfSOx4eEE
        static void Main(string[] args)
        {
            List<string> input = Read().ToList();       //takes a list of lines and parses them into BombsAway Scenarios. Each scenario consists
                                                        //of n number of levels where n >= 0. Each level consists of a bomb a player, which I call Jimmy,
                                                        //and m number of rectangles where 0 <= m <= 20.
            string line;
            line = input[0];
            Scanner scan = new Scanner(line);
            int numberOfScenarios = scan.nextInt();     //The first line of input contains the number of Scenarios to parse through
            int currentLineNumber = 1;                  
            for (int i = 0; i < numberOfScenarios; i++)
            {
                BombsAway currenTestCase = new BombsAway();
                currenTestCase.Solve(input, ref currentLineNumber);     //Prints to standard out the solution to each level in the scenario
                                                                        //Takes a reference to the current line number, so that increments to 
                                                                        //the line number will be visible outside of the solve method.
            }
        }

        #region Main Helper Methods


        //Takes Standard Input and make it into an enumeration of strings
        private static IEnumerable<string> Read()
        {
            var input = new List<string>();
            while (true)
            {
                string line = Console.ReadLine();
                if (line != null)
                {
                    input.Add(line);
                    continue;
                }
                break;
            }
            return input;
        }

        #endregion
    }

    internal class BombsAway
    {
        public void Solve(List<string> input, ref int lineNumber)
        {
            string line;
            line = input[lineNumber];
            lineNumber++;
            Scanner scan = new Scanner(line);
            int numberOfLevels = scan.nextInt();

            //Sentinel Values that indicate an error if not overwritten to proper grid coordinates
            int xCoorBomb = -1;     //keeps track of the x-coordinate of the bomb
            int yCoorBomb = -1;     //keeps track of the y-coordinate of the bomb
            int xCoorJimmy = -1;    //keeps track of the x-coordinate of Jimmy(or the character playing the game)
            int yCoorJimmy = -1;    //keeps track of the y-coordinate of Jimmy(or the character playing the game)

            //Parses each level and prints the solution to standard out
            for (int i = 0; i < numberOfLevels; i++)
            {
                //create a grid of nodes based on the input
                Node[,] grid = createGrid(input, ref lineNumber, ref xCoorBomb, ref yCoorBomb, ref xCoorJimmy, ref yCoorJimmy);
                findHValues(grid, xCoorBomb, yCoorBomb);    //pre calculate the H-value for each node
                Queue<Node> openList = new Queue<Node>();   //a list of potential candidates nodes to visit
                bool[,] closedList = new bool[grid.GetLength(0), grid.GetLength(1)];    //a list of already visited nodes
                for (int j = 0; j < grid.GetLength(0); j++)
                {
                    for (int k = 0; k < grid.GetLength(1); k++)
                    {
                        closedList[j, k] = false;   //by default we have not visited any nodes yet
                    }
                }
                //start at Jimmy and add his node to the closed list
                Node target = grid[xCoorJimmy - 1, yCoorJimmy - 1];
                closedList[target.X, target.Y] = true;  //add this node to the closed list, as it should not be revisited
                do
                {
                    List<Node> neighbors = orthogonalNeighbors(grid, target);

                    foreach (Node neighbor in neighbors)
                    {
                        //if node not on open or closed list, then it hasn't been touched yet 
                        if (!openList.Contains(neighbor) && closedList[neighbor.X, neighbor.Y] == false)
                        {
                            neighbor.Parent = target;                   //the current node you are looking it (i.e. target) should be the parent of this node
                            neighbor.G = neighbor.Parent.G + 1;         //the movement cost of this node should be the movement cost of it's parent + 1
                            neighbor.F = neighbor.H + neighbor.G;       
                            openList.Enqueue(neighbor);                 //add this node to the open list so it can be revisited 
                        }
                            //if neighbour on open list, then it has been touched before
                        else if (openList.Contains(neighbor))
                        {
                            int newGValue = target.G + 1;
                            //if travelling through this node is less expensive than travelling through it's parent node,
                            //then make the target node the parent node and update the G and F values 
                            if (newGValue < neighbor.G)
                            {
                                neighbor.Parent = target;                   //the current node (i.e. target) should be the parent
                                neighbor.G = neighbor.Parent.G + 1;         //update the movement cost with the faster movement cost 
                                neighbor.F = neighbor.H + neighbor.G;
                            }
                        }
                    }
                    if (openList.Any())
                        target = openList.Dequeue();       
                    else
                    { 
                        break;          //if no nodes on open list then we have run out neighbours to visit, we have hit a dead end
                    }
                    closedList[target.X, target.Y] = true;  //put the node we have just visited on the closed list so we don't visit it again
                } while (target.H != 0);
                string solution = "";

                //only the bomb node has an h-value of 0
                if (target.H != 0)
                    solution = "This level is impossible!";
                else
                    solution = "Set the timer to be " + (target.G + 5) + " seconds.";
                Console.WriteLine(solution);
    
                  #region Debug Printer
//                //####################################################################################################################################//
//                //                                                                                                                                    //
//                //                                                                                                                                    //
//                //        Below is an Optional Debug printer that will print the state of each cell as well as a trace of the shortest path           //
//                //        starting at the bomb going back towards where Jimmy started.                                                                //
//                //                                                                                                                                    //
//                //                                                                                                                                    //
//                //####################################################################################################################################//
//
//                Debug.WriteLine("\n\n");
//                Debug.WriteLine("{0,10}{1,10}{2,20}{3,10}{4,10}{5,10}{6,10}", "x:", "y:", "Resident", "H-value:", "G-value:", "parent x:", "parent y:");
//                int rows = grid.GetLength(0);
//                int columns = grid.GetLength(1);
//                for (int j = 0; j < rows; j++)
//                {
//                    for (int k = 0; k < columns; k++)
//                    {
//                        if (grid[j, k].Parent == null)
//                            Debug.WriteLine("{0,10}{1,10}{2,20}{3,10}{4,10}{5,10}{6,10}", grid[j, k].X + 1, grid[j, k].Y + 1, grid[j, k].NodeResident, grid[j, k].H, grid[j, k].G,  "null", "null");
//                        else
//                            Debug.WriteLine("{0,10}{1,10}{2,20}{3,10}{4,10}{5,10}{6,10}", grid[j, k].X + 1, grid[j, k].Y + 1, grid[j, k].NodeResident, grid[j, k].H, grid[j, k].G, grid[j, k].Parent.X, grid[j, k].Parent.Y);
//
//                    }
//                }
//
//                Debug.WriteLine("\n\n\n");
//
//                Debug.WriteLine("start debug:");
//                Debug.WriteLine("\tshortest path:");
//                Debug.WriteLine("\t\t"+ target);
//                while (target != null)
//                {
//                    Debug.WriteLine("\t\t" + target.Parent);
//                    target = target.Parent;
//                }
//                Debug.WriteLine("end debug");

                #endregion
            }
        }

        #region BombsAway Helper Methods

        //####################################################################################################################################//
        //                                                                                                                                    //
        //                                                                                                                                    //
        //        Helper method that parses through the input file and returns a 2d array of Nodes representing the game grid.                //
        //                                                                                                                                    //
        //                                                                                                                                    //
        //####################################################################################################################################//

        private static Node[,] createGrid(List<string> input, ref int lineNumber, ref int xCoorBomb, ref int yCoorBomb, ref int xCoorJimmy, ref int yCoorJimmy)
        {
            string line;
            Scanner scan;                                  
            line = input[lineNumber];
            lineNumber++;
            scan = new Scanner(line);
            int rows = scan.nextInt();                  //the first line of input always contains the dimensions of the grid
            int columns = scan.nextInt();
            Node[,] grid = new Node[rows, columns];
            line = input[lineNumber];
            lineNumber++;
            scan = new Scanner(line);
            int jimmyX = scan.nextInt();                //the next line of input always contains the x and y coordinates of Jimmy
            xCoorJimmy = jimmyX;
            int jimmyY = scan.nextInt();
            yCoorJimmy = jimmyY;
            line = input[lineNumber];
            lineNumber++;
            scan = new Scanner(line);
            int bombX = scan.nextInt();                
            xCoorBomb = bombX - 1;
            int bombY = scan.nextInt();                 //the next line always contains the x and y coordinates of the bomb
            yCoorBomb = bombY - 1;
            line = input[lineNumber];
            lineNumber++;
            scan = new Scanner(line);
            int numberOfRectangles = scan.nextInt();                //the next line off input always contains the number of rectangles
            int[,] rectangles = new int[numberOfRectangles, 4];     
            for (int j = 0; j < numberOfRectangles; j++)
            {
                line = input[lineNumber];
                lineNumber++;
                scan = new Scanner(line);
                for (int k = 0; k < 4; k++)         //store each rectangle an array of four integers (top left x, top left y, bottom right x, bottom right y)
                {
                    rectangles[j, k] = scan.nextInt();  
                }
            }
            for (int j = 0; j < rows; j++)
            {
                for (int k = 0; k < columns; k++)
                {
                    Node currentNode = null;
                    if (j + 1 == jimmyX && k + 1 == jimmyY)
                        currentNode = new Node(Node.Resident.JIMMY, j, k);  //Look for the Node with Jimmy's coordinates and mark it's state as Jimmy
                    else if (j + 1 == bombX && k + 1 == bombY)
                        currentNode = new Node(Node.Resident.BOMB, j, k);   //Look for the Node with the Bomb's coordinates and mark it's state as Bomb
                    for (int l = 0; l < numberOfRectangles; l++)
                    {
                        if ((j + 1 >= rectangles[l, 0] && j + 1 <= rectangles[l, 2]) &&
                            (k + 1 >= rectangles[l, 3] && k + 1 <= rectangles[l, 1]))
                        {
                            currentNode = new Node(Node.Resident.RECTANGLE, j, k);  //mark out all nodes that fall between the rectangles top left corner 
                                                                                    //and bottom right corner as having a state of Rectangle, which will make the node inaccessible to Jimmy
                            break;
                        }

                    }
                    if (currentNode == null)
                        currentNode = new Node(Node.Resident.NO_ONE, j, k); //If the node has no been initialized as a Jimmy node, or a Bomb node, or a Rectangle node, then no one is occupying it
                    grid[j, k] = currentNode;   //add the newly created node to the grid
                }
            }
            return grid;
        }

        //####################################################################################################################################//
        //                                                                                                                                    //
        //                                                                                                                                    //
        //        Helper method that computes the Heuristic Value of each node calculated in the Manhattan Format. Which is basically         //
        //        the shortest distance to the Bomb ignoring the obstacles.                                                                   //
        //                                                                                                                                    //
        //                                                                                                                                    //
        //####################################################################################################################################//

        private static void findHValues(Node[,] grid, int xBomb, int yBomb)
        {
            if (xBomb == -1 || yBomb == -1)
                throw new Exception("Error: no bomb in this level.");

            for (int i = 0; i < grid.GetLength(0); i++)
            {
                for (int j = 0; j < grid.GetLength(1); j++)
                {
                    Node current = grid[i, j];
                    if (current.NodeResident == Node.Resident.NO_ONE)
                    {
                        int xDistance = Math.Abs(i - xBomb);    
                        int yDistance = Math.Abs(j - yBomb);
                        int hValue = xDistance + yDistance;     //the h-value is the distance from the current node to the Bomb
                        current.H = hValue;
                    }
                    else if (current.NodeResident == Node.Resident.BOMB)
                        current.H = 0;
                    else
                        current.H = -1;
                }
            }
        }


        //####################################################################################################################################//
        //                                                                                                                                    //
        //                                                                                                                                    //
        //        Helper method that returns a List of Nodes that are Orthogonally adjacent from the target Node, meaning the nodes that      //
        //        are either directly above, below, to the right, or to the left of the target nodes. Only open nodes are returned.           //
        //                                                                                                                                    //
        //                                                                                                                                    //
        //####################################################################################################################################//

        private static List<Node> orthogonalNeighbors(Node[,] grid, Node target)
        {
            List<Node> neighbors = new List<Node>();
            int targetX = target.X;
            int targetY = target.Y;

            if (targetX < grid.GetLength(0) - 1)
            {
                Node eastNode = grid[targetX + 1, targetY];
                if (eastNode.NodeResident == Node.Resident.NO_ONE || eastNode.NodeResident == Node.Resident.BOMB)
                    neighbors.Add(eastNode);
            }
            if (targetY < grid.GetLength(1) - 1)
            {
                Node northNode = grid[targetX, targetY + 1];
                if (northNode.NodeResident == Node.Resident.NO_ONE || northNode.NodeResident == Node.Resident.BOMB)
                    neighbors.Add(northNode);
            }
            if (targetY > 0)
            {
                Node southNode = grid[targetX, targetY - 1];
                if (southNode.NodeResident == Node.Resident.NO_ONE || southNode.NodeResident == Node.Resident.BOMB)
                    neighbors.Add(southNode);
            }
            if (targetX > 0)
            {
                Node westNode = grid[targetX - 1, targetY];
                if (westNode.NodeResident == Node.Resident.NO_ONE || westNode.NodeResident == Node.Resident.BOMB)
                    neighbors.Add(westNode);
            }

            return neighbors;
        }

        #endregion
    }

    #region Scanner class
    internal class Scanner : System.IO.StringReader
    {
        string currentWord;

        public Scanner(string source)
            : base(source)
        {
            readNextWord();
        }

        private void readNextWord()
        {
            System.Text.StringBuilder sb = new StringBuilder();
            char nextChar;
            int next;
            do
            {
                next = this.Read();
                if (next < 0)
                    break;
                nextChar = (char)next;
                if (char.IsWhiteSpace(nextChar))
                    break;
                sb.Append(nextChar);
            } while (true);
            while ((this.Peek() >= 0) && (char.IsWhiteSpace((char)this.Peek())))
                this.Read();
            if (sb.Length > 0)
                currentWord = sb.ToString();
            else
                currentWord = null;
        }

        public bool hasNextInt()
        {
            if (currentWord == null)
                return false;
            int dummy;
            return int.TryParse(currentWord, out dummy);
        }

        public int nextInt()
        {
            try
            {
                return int.Parse(currentWord);
            }
            finally
            {
                readNextWord();
            }
        }

        public bool hasNextDouble()
        {
            if (currentWord == null)
                return false;
            double dummy;
            return double.TryParse(currentWord, out dummy);
        }

        public double nextDouble()
        {
            try
            {
                return double.Parse(currentWord);
            }
            finally
            {
                readNextWord();
            }
        }

        public bool hasNext()
        {
            return currentWord != null;
        }
    }
#endregion

    #region Node class

    internal class Node
    {
        public enum Resident
        {
            JIMMY, BOMB, RECTANGLE, NO_ONE
        }

        public int H { get; set; }
        public int G { get; set; }
        public int F { get; set; }
        public int X { get; set; }
        public int Y { get; set; }
        public Resident NodeResident { get; set; }
        public Node Parent { get; set; }


        public Node(Resident resident, int x, int y)
        {
            H = 0;
            G = 0;
            F = 0;
            NodeResident = resident;
            Parent = null;
            X = x;
            Y = y;
        }

        public override bool Equals(object obj)
        {
            Node parameterNode = (Node)obj;
            return (this.X == parameterNode.X && this.Y == parameterNode.Y);
        }

        public override string ToString()
        {
            return "Node at [" + (this.X + 1) + "," + (this.Y + 1) + "]";
        }
    }
    #endregion
}
