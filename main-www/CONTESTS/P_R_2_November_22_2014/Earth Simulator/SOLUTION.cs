// This is the solution to Earth simulation problem 
// This solution uses preflow-relabel algorithm to solve maxflow

namespace EarthSimulatorMaxFlow
{
    using System;
    using System.Collections.Generic;
    using System.Linq;

    public class Program
    {
        public static void Main(string[] args)
        {
            var earthSim = new EarthSim();
            IList<string> output = earthSim.GetResult(Read().ToList());
            WriteFile(output);
        }

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

        private static void WriteFile(IEnumerable<string> output)
        {
            foreach (string str in output)
            {
                Console.WriteLine(str);
            }
        }
    }

    /// <summary>
    /// The Earth simulation
    /// </summary>
    internal class EarthSim
    {

        private const string _PLANET_LIVES = "The planet lives!";
        private const string _PLANET_DIES = "Death to them all.";

        /// <summary>
        /// Takes a list of strings and parses into planets
        /// Then it runs the simulation on each planets and returns the result in a list format
        /// </summary>
        /// <param name="input"></param>
        /// <returns></returns>
        public IList<string> GetResult(List<string> input)
        {
            // Result
            var output = new List<string>();
            int newPlanetLine = 1;

            // Iterate through the list and parses it into planets
            for (int planet = 0; planet < input[0].ToInt(); planet++)
            {
                // animalnumber animalfood number
                string[] planetDescription = input[newPlanetLine].Split();
                
                // Takes all the lines after the description
                int planetLineNumber = planetDescription[0].ToInt() + planetDescription[1].ToInt();
                List<string> planetLines = input.GetRange(newPlanetLine, planetLineNumber + 1);

                // Add the result of the analyzed planet
                output.Add(AnalyzePlanet(planetLines));

                // Goto next planet
                newPlanetLine += planetLines.Count;
            }

            return output;
        }

        /// <summary>
        /// Takes a list of string that represent one planet 
        /// Generates a graph with vertices and Edges
        /// Then apply preflow-relabel algorithm to find the max flow of the planet inorder to determine
        /// if the planet is stable or not
        /// </summary>
        /// <param name="planet"></param>
        /// <returns></returns>
        private static string AnalyzePlanet(List<string> planet)
        {
            // Get the planet information
            string[] animalInfo = planet[0].Split();

            // Dictionary that contains all the animals and their informations
            Dictionary<string, Animal> animalDictionary =
                Helper.GetAnimalDictionary(planet.GetRange(1, animalInfo[0].ToInt()),
                    planet.GetRange(animalInfo[0].ToInt() + 1, animalInfo[1].ToInt()));

            // List of Predator
            List<Vertex> predatorList = animalDictionary.Values.Select(animal => new Vertex
            {
                Name = animal.Name
            }).ToList();

            var preyDictionary = new Dictionary<string, Vertex>();
            // Dictioary of Prey
            foreach (Animal predator in animalDictionary.Values)
            {
                if (predator.FoodNumber > 0)
                {
                    foreach (Animal prey in predator.PreyList)
                    {
                        if (!preyDictionary.ContainsKey(prey.Name))
                            preyDictionary.Add(prey.Name, new Vertex
                            {
                                Name = prey.Name
                            });
                    }
                }
            }

            if (predatorList.Count != preyDictionary.Count)
                return _PLANET_DIES;

            // This is the list of every prey and the predator
            List<Vertex> allVertexList = new List<Vertex>(predatorList);
            allVertexList.AddRange(preyDictionary.Values);

            // Indexes the animal based on the position of the animal in the list
            for (int index = 0; index < allVertexList.Count; index++)
            {
                allVertexList[index].Index = index;
            }

            // Height of the vertex is the |V|
            // Base vertices
            Vertex sourceVertex = new Vertex
            {
                Height = predatorList.Count + preyDictionary.Keys.Count + 2,
                isSpecial = true
            };
            Vertex targetVertex = new Vertex
            {
                isSpecial = true

            };

            // OutEdge for source and inEdge for preadtor
            // Deals with initalize as well
            foreach (Vertex predator in predatorList)
            {
                int edgeFlow = animalDictionary[predator.Name].FoodNumber;
                Edge inEdge = new Edge
                {
                    Capacity = edgeFlow,
                    Flow = edgeFlow,
                    FromVertex = sourceVertex,
                    ToVertex = predator,
                };
                sourceVertex.OutEdge.Add(inEdge);
                sourceVertex.OutFlowValue += edgeFlow;
                predator.InEdge.Add(inEdge);
                predator.InFlowValue += edgeFlow;
            }

            // Add the edge linking the predator list to prey list
            // This edge will have max capacity
            foreach (Vertex predator in predatorList)
            {
                foreach (Animal prey in animalDictionary[predator.Name].PreyList)
                {
                    Edge edge = new Edge
                    {
                        Capacity = Int32.MaxValue,
                        FromVertex = predator,
                        ToVertex = preyDictionary[prey.Name]
                    };
                    preyDictionary[prey.Name].InEdge.Add(edge);
                    predator.OutEdge.Add(edge);
                }
            }

            foreach (Vertex prey in preyDictionary.Values)
            {
                Edge edge = new Edge
                {
                    Capacity = animalDictionary[prey.Name].Number,
                    FromVertex = prey,
                    ToVertex = targetVertex
                };
                prey.OutEdge.Add(edge);
                targetVertex.InEdge.Add(edge);
            }

            // record the needed max flow
            int outflowCap = sourceVertex.OutFlowValue;

            // Run the preflow-relabel algorithm
            PreflowRelabel(allVertexList);
            
            // Check if the max flow is the same as the required foods, if yes, the planet lives
            // else, planet dies

            // If any animal does not have predator, then the planet dies
            if (targetVertex.InEdge.Any(edge => edge.Flow < edge.Capacity))
                return _PLANET_DIES;

            return outflowCap <= targetVertex.InFlowValue ? _PLANET_LIVES : _PLANET_DIES;
        }

        // Apply the PreflowRelabel algorithm to obatin the fax flow of the graph
        // Notation:
        // s = sum
        // f = flow
        // c = capacity
        // e' = overflow
        // h = height
        private static void PreflowRelabel(List<Vertex> vertexList)
        {
            // If there are still active vertex in the graph
            // Vertex v is active iff: s(f(inEdge(v))) - s(f(outEdge(v))) > 0
            // Repeat until there is no active vertex left
            
            // Grab all the vertices that has more flow in than flow out
            var actiQ = new Queue<int>(vertexList.Where(vertex => vertex.InFlowValue > vertex.OutFlowValue).Select(v =>
            {
                v.isEnQue = true;
                return v.Index;
            }));

            // While the qeue still have active vertex left in it
            while (actiQ.Any())
            {

                // FIFO
                Vertex activeVertex = vertexList[actiQ.Dequeue()];
                
                // Set the vertex's isEnque property to flase to prevent multiple stacking
                activeVertex.isEnQue = false;

                // If the vertex is source or target vertex, or if the vertex's flow is evened out, continue
                if (activeVertex.isSpecial || activeVertex.InFlowValue == activeVertex.OutFlowValue)
                    continue;

                // Check if overflow can be pushed forward
                // In order for flow to be push forward (ie. v -> w), the following must be satisfied:
                // h(v) > h(w) && f(e(v,w)) <= c(e(v,w))

                //Resolve each vertex by looping until the inflow is the same as outflow
                while (activeVertex.InFlowValue > activeVertex.OutFlowValue)
                {
                    // min height
                    int minHeight = int.MaxValue;

                    // iterate through each of the out edges
                    foreach (var edge in activeVertex.OutEdge)
                    {

                        // If there the in flow is more than outflow
                        if (activeVertex.InFlowValue > activeVertex.OutFlowValue)
                        {

                            // If the destination vertex height is lower than the current vertex 
                            if (edge.FromVertex.Height > edge.ToVertex.Height && edge.Flow < edge.Capacity)
                            {

                                // the ammount can be pushed will be min{ c(e(v,w)) - f(e(v,w)), e'(v)}
                                int pushAmmount = Math.Min(activeVertex.InFlowValue - activeVertex.OutFlowValue,
                                    edge.Capacity - edge.Flow);
                                edge.Flow += pushAmmount;
                                edge.ToVertex.InFlowValue += pushAmmount;
                                edge.FromVertex.OutFlowValue += pushAmmount;

                                // add the destination que to the que
                                if (edge.ToVertex.InFlowValue > edge.ToVertex.OutFlowValue && !edge.ToVertex.isEnQue)
                                {
                                    actiQ.Enqueue(edge.ToVertex.Index);
                                    edge.ToVertex.isEnQue = true;
                                }
                            }
                            else if (edge.Flow < edge.Capacity)
                            {
                                // If the edge has capacity but the height of the destination vertex is higher, record the min height
                                minHeight = Math.Min(edge.ToVertex.Height, minHeight);
                            }

                        }
                        else
                        {
                            break;
                        }
                    }

                    // Check to see if the flow is evened out
                    if (activeVertex.InFlowValue == activeVertex.OutFlowValue)
                        break;

                    // Check if overflow can be push backward
                    // In order for flow to be pushed backward (ie v <- w), the following must be satisfied:
                    // h(v) -> h(w) && s(f(inEdge(v))) > s(f(outEdge(v))) && every outbound edge is saturated
                    // var edgeL = activeVertex.InEdge.Where(e => e.FromVertex.Height < e.ToVertex.Height && e.Flow > 0);
                    // The algorithm is almost identical to the one above, just for in edge rather than outedge 
                    int minIncomingHeight = int.MaxValue;
                    foreach (var edge in activeVertex.InEdge)
                    {
                        if (activeVertex.InFlowValue > activeVertex.OutFlowValue)
                        {
                            if (edge.FromVertex.Height < edge.ToVertex.Height && edge.Flow > 0)
                            {
                                // the ammount can be pushed will be min{ f(e(v,w)), e'(v)}
                                int pushAmmount = Math.Min(edge.Flow,
                                    activeVertex.InFlowValue - activeVertex.OutFlowValue);
                                edge.Flow -= pushAmmount;
                                edge.FromVertex.OutFlowValue -= pushAmmount;
                                edge.ToVertex.InFlowValue -= pushAmmount;
                                if (edge.FromVertex.InFlowValue > edge.FromVertex.OutFlowValue &&
                                    !edge.FromVertex.isEnQue)
                                {
                                    actiQ.Enqueue(edge.FromVertex.Index);
                                    edge.FromVertex.isEnQue = true;
                                }
                            }
                            else if (minHeight == int.MaxValue && edge.Flow > 0)
                            {
                                minIncomingHeight = Math.Min(minIncomingHeight, edge.FromVertex.Height);
                            }
                        }
                        else
                            break;
                    }

                    // Check again
                    if (activeVertex.InFlowValue == activeVertex.OutFlowValue)
                        break;

                    // Relabel if necessary
                    // The min height is the 1 + min(all outbound edges) || in the case of all saturated outbound edge, min(all in coming edge) + 1
                    int minVertexHeight;
                    if (minHeight != int.MaxValue)
                    {
                        minVertexHeight = minHeight + 1;
                    }
                    else
                    {
                        minVertexHeight = minIncomingHeight + 1;
                    }
                    activeVertex.Height = minVertexHeight;
                    actiQ.Enqueue(activeVertex.Index);
                    activeVertex.isEnQue = true;
                }
            }
        }

    }

    // Vertex pbject
    internal class Vertex
    {
        public List<Edge> OutEdge { get; set; }
        public List<Edge> InEdge { get; set; }
        public int InFlowValue { get; set; }
        public int OutFlowValue { get; set; }
        public int Height { get; set; }
        public bool isSpecial { get; set; }
        public bool isEnQue { get; set; }
        public string Name { get; set; }
        public int Index { get; set; }

        public Vertex()
        {
            OutEdge = new List<Edge>();
            InEdge = new List<Edge>();
        }
    }

    // Edge object
    internal class Edge
    {
        public Vertex FromVertex { get; set; }
        public Vertex ToVertex { get; set; }
        public int Flow { get; set; }
        public int Capacity { get; set; }
    }

    // Animal object
    internal class Animal
    {
        public string Name { get; set; }
        public int Number { get; set; }
        public int FoodNumber { get; set; }
        public List<Animal> PreyList { get; set; }


        public Animal()
        {
            PreyList = new List<Animal>();
        }
    }

    // Helper object
    internal static class Helper
    {
        /// <summary>
        /// Parse the list of string into dictionary with key = animal name and value = animal object
        /// </summary>
        /// <param name="animalStatusList"></param>
        /// <param name="edibilityList"></param>
        /// <returns></returns>
        public static Dictionary<string, Animal> GetAnimalDictionary(IEnumerable<string> animalStatusList,
            IEnumerable<string> edibilityList)
        {
            Dictionary<string, Animal> animalDictionary = animalStatusList.Select(animalStatus => animalStatus.Split())
                .Select(animalArray => new Animal
                {
                    Name = animalArray[0],
                    Number = ToInt(animalArray[1]),
                    FoodNumber = ToInt(animalArray[2])
                })
                .ToDictionary(animal => animal.Name);

            foreach (string edibility in edibilityList)
            {
                string[] edidiableArray = edibility.Split();

                // Get the animal and the prey
                Animal animal = animalDictionary[edidiableArray[0]];
                Animal prey = animalDictionary[edidiableArray[1]];

                // Add the prey to the animal's prey list
                animal.PreyList.Add(prey);
            }

            return animalDictionary;
        }

        // To int
        public static int ToInt(this string str)
        {
            return Int32.Parse(str);
        }
    }
}
