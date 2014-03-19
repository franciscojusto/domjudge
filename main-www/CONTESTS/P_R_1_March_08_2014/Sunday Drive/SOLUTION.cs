using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Linq;
using System.Text;

// Other references
// http://en.wikipedia.org/wiki/Zero_of_a_function
// http://en.wikipedia.org/wiki/Root-finding_algorithm
// http://www.codeproject.com/Articles/79541/Three-Methods-for-Root-finding-in-C

namespace SundayDriveNameSpace
{
    class Program
    {
        static void Main(string[] args)
        {
            SundayDrive sundayDrive = new SundayDrive();
            sundayDrive.GetInput();
            sundayDrive.ProcessInput();
            sundayDrive.DetermineOutput();
            foreach (var drive in sundayDrive.Drives)
            {
                Console.WriteLine(drive.FormattedResult());
            }

            // Console.ReadLine();
        }
    }

    public class SundayDrive
    {
        public String InputString { get; set; }

        public List<Drive> Drives { get; set; }

        public void ProcessInput()
        {
            if (String.IsNullOrEmpty(InputString))
            {
                return;
            }

            string[] splitStringsRaw = InputString.Split(new[] { Environment.NewLine }, StringSplitOptions.RemoveEmptyEntries);
            string[] splitStrings = splitStringsRaw.Where(x => !string.IsNullOrEmpty(x.Trim())).ToArray();
            if (splitStrings.Any())
            {
                int numberOfDrives = int.Parse(splitStrings[0]);
                List<Drive> drives = new List<Drive>();
                for (int i = 0; i < numberOfDrives; i++)
                {
                    // for each drive parse out the Drive class
                    Drive currentDrive = new Drive();
                    currentDrive.DriveNumber = i + 1;
                    string[] valuesOnFirstLine = splitStrings[i * 3 + 1].Trim().Split(new char[0], StringSplitOptions.RemoveEmptyEntries);
                    currentDrive.SlopeOfTheWind = int.Parse(valuesOnFirstLine[0]);
                    currentDrive.Direction = valuesOnFirstLine[1];

                    string[] valuesOnSecondLine = splitStrings[i * 3 + 2].Trim().Split(new char[0], StringSplitOptions.RemoveEmptyEntries);
                    currentDrive.RoadFunction = new List<int>();
                    foreach (var source in valuesOnSecondLine.Reverse())
                    {
                        // for my list the index will represent the exponent, hence the reverse
                        currentDrive.RoadFunction.Add(int.Parse(source));
                    }

                    var valuesOnThirdLine = splitStrings[i * 3 + 3].Trim().Split(new char[0], StringSplitOptions.RemoveEmptyEntries);
                    currentDrive.StartCoordinate = double.Parse(valuesOnThirdLine[0]);
                    currentDrive.StopCoordinate = double.Parse(valuesOnThirdLine[1]);

                    drives.Add(currentDrive);
                }

                Drives = drives;
            }
        }

        public void DetermineOutput()
        {
            foreach (Drive drive in Drives)
            {
                drive.Roots = new List<double>();
                foreach (var factorOfLast in drive.FactorsOfLast())
                {
                    foreach (var factorOfFirst in drive.FactorsOfFirst())
                    {
                        double testValueForX = (factorOfFirst == 0 || factorOfLast == 0) ?
                            0 :
                            (double)factorOfFirst / (double)factorOfLast;

                        Debug.WriteLine(testValueForX);
                        if (Math.Abs(CalculateFunctionReturnValue(drive.DerivedRoadFunctionWithWindAdjustment(), testValueForX)) < double.Epsilon)
                        {
                            drive.Roots.Add(testValueForX);
                        }

                        if (Math.Abs(CalculateFunctionReturnValue(drive.DerivedRoadFunctionWithWindAdjustment(), -testValueForX)) < double.Epsilon)
                        {
                            drive.Roots.Add(-testValueForX);
                        }
                    }
                }

                drive.Roots = drive.Roots.Distinct().ToList();
            }
        }

        public void GetInput()
        {
            if (String.IsNullOrEmpty(InputString))
            {
                StringBuilder stringBuilder = new StringBuilder();
                string line;
                int numberOfDrives = -1;
                int countForCurrentDrive = 0;
                while ((line = Console.ReadLine()) != null)
                {
                    if (!string.IsNullOrEmpty(line.Trim()))
                    {
                        if (string.IsNullOrEmpty(stringBuilder.ToString().Trim()))
                        {
                            // This is the first item in the input, which is the number of drives
                            numberOfDrives = int.Parse(line.Trim());
                        }
                        else
                        {
                            countForCurrentDrive++;
                            if (countForCurrentDrive == 3)
                            {
                                numberOfDrives--;
                                countForCurrentDrive = 0;
                            }
                        }

                        stringBuilder.Append(line.Trim() + Environment.NewLine);

                        if (numberOfDrives == 0)
                        {
                            break;
                        }
                    }
                }

                InputString = stringBuilder.ToString();
            }
        }

        public static List<int> CalculateDerivative(List<int> roadFunction)
        {
            List<int> derivedRoadFunction = new List<int>();

            for (int i = 1; i < roadFunction.Count; i++)
            {
                derivedRoadFunction.Add(roadFunction[i] * i);
            }

            return derivedRoadFunction;
        }

        public static List<int> AdjustDerivativeBasedOnWindDirection(List<int> derivedRoadFunction, int slopeOfTheWind)
        {
            if (derivedRoadFunction.Count == 0)
            {
                return derivedRoadFunction;
            }

            derivedRoadFunction[0] = derivedRoadFunction[0] - slopeOfTheWind;

            return derivedRoadFunction;
        }

        public static double CalculateFunctionReturnValue(List<int> coefficients, double valueOfX)
        {
            double finalResult = 0;
            for (int i = 0; i < coefficients.Count; i++)
            {
                finalResult += (coefficients[i] * Math.Pow(valueOfX, i)); // todo use long with loop
            }

            return finalResult;
        }

        public static long MyMathPow(int value, int exponent)
        {
            long result = value;
            for (int i = 0; i < exponent - 1; i++)
            {
                result = result * value;
            }

            if (exponent == 0)
            {
                result = 1;
            }

            return result;
        }

        public static List<int> ObtainFactors(int number, bool forceZero)
        {
            List<int> factors = new List<int>();
            number = Math.Abs(number);
            if (number != 0)
            {
                factors.Add(1);
                factors.Add(number);
                for (int i = 2; i < number; i++)
                {
                    if (number % i == 0)
                    {
                        factors.Add(i);
                    }
                }
            }

            if (number == 0 || forceZero)
            {
                factors.Add(0);
            }

            return factors;
        }
    }

    public class Drive
    {
        public int DriveNumber { get; set; }
        public int SlopeOfTheWind { get; set; }
        public string Direction { get; set; }
        public List<int> RoadFunction { get; set; }
        public List<int> DerivedRoadFunctionWithWindAdjustment()
        {
            var derivedRoadFunction = SundayDrive.CalculateDerivative(RoadFunction);
            return SundayDrive.AdjustDerivativeBasedOnWindDirection(derivedRoadFunction, SlopeOfTheWind);
        }

        public List<int> FactorsOfFirst()
        {
            if (DerivedRoadFunctionWithWindAdjustment().Count == 0)
            {
                return new List<int>();
            }

            return SundayDrive.ObtainFactors(DerivedRoadFunctionWithWindAdjustment().FirstOrDefault(x => x != 0),
                (DerivedRoadFunctionWithWindAdjustment().First() == 0));
        }

        public List<int> FactorsOfLast()
        {
            if (DerivedRoadFunctionWithWindAdjustment().Count <= 1)
            {
                return new List<int>();
            }

            return SundayDrive.ObtainFactors(DerivedRoadFunctionWithWindAdjustment().LastOrDefault(x => x != 0),
               false);
        }

        public string CalculatePercentage()
        {
            List<double> rootsWithinScope = new List<double>(Roots.Where(x => StartCoordinate <= x && x <= StopCoordinate));

            // add start and stop to list
            rootsWithinScope.Add(StartCoordinate);
            rootsWithinScope.Add(StopCoordinate);
            rootsWithinScope = rootsWithinScope.Distinct().ToList();
            rootsWithinScope.Sort();

            double sumOfDryRegions = 0;
            // between each pair, determine wet or dry.. if dry.. sum up
            // list should at least contain start and stop
            double dryPercentage = 0;
            if (Math.Abs(StartCoordinate - StopCoordinate) < double.Epsilon)
            {
                double slopeAtStartStopValue = SundayDrive.CalculateFunctionReturnValue(DerivedRoadFunctionWithWindAdjustment(), StartCoordinate);
                if ((slopeAtStartStopValue <= 0 + 1E-9 && Direction.ToUpper() == "F") ||
                            (slopeAtStartStopValue >= 0 - 1E-9 && Direction.ToUpper() == "B"))
                {
                    dryPercentage = 100;
                }
            }
            else
            {
                for (int i = 0; i < rootsWithinScope.Count; i++)
                {
                    double currentRoot = rootsWithinScope[i];
                    if (i < rootsWithinScope.Count - 1)
                    {
                        // then we have a next root
                        double nextRoot = rootsWithinScope[i + 1];
                        double halfWayValue = (currentRoot + nextRoot) / 2;
                        double slopeAtHalfwayValue = SundayDrive.CalculateFunctionReturnValue(DerivedRoadFunctionWithWindAdjustment(), halfWayValue);

                        if ((slopeAtHalfwayValue <= 0 + 1E-9 && Direction.ToUpper() == "F") ||
                            (slopeAtHalfwayValue >= 0 - 1E-9 && Direction.ToUpper() == "B"))
                        {
                            sumOfDryRegions += nextRoot - currentRoot;
                        }
                    }
                }

                dryPercentage = (sumOfDryRegions / (StopCoordinate - StartCoordinate) * 100);
            }

            return string.Format("{0:f6}", dryPercentage);
        }

        public string FormattedResult()
        {
            return string.Format("Drive #{0}: Your windows can be down {1}% of the time", DriveNumber, CalculatePercentage());
        }

        public List<double> Roots { get; set; }
        //  (B < E, |B|, |E| ≤ 1000000)
        public double StartCoordinate { get; set; }
        public double StopCoordinate { get; set; }
    }
}
