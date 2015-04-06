using System;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;

namespace VolcanoMadnessProduction
{
    public class Program
    {
        static void Main()
        {
            // Read in the amount of eruptions.
            var eruptionLine = Console.ReadLine();

            var eruptions = Convert.ToInt32(eruptionLine);

            for (var i = 0; i < eruptions; i++)
            {
                // Validate the track pieces line.
                var nextTrackPieces = Console.ReadLine();

                if (nextTrackPieces == null) continue;

                var trackPieceInputs = nextTrackPieces.Select(c => int.Parse(c.ToString(CultureInfo.InvariantCulture))).ToList();

                // Create a solver.
                var solver = new VolcanoMadness.Solver();
                var result = solver.Solve(trackPieceInputs);
                Console.WriteLine(result ? "The town is safe this time." : "Need more track pieces! Quickly!");
            }
        }

        public class VolcanoMadness
        {
            public class TrackPieceList : List<TrackPiece>
            {
                public int Number { get; set; }
            }

            /// <summary>
            /// Factory used to create Track Pieces
            /// </summary>
            public class TrackPieceFactory
            {
                /// <summary>
                /// Dictionary that defines what a direction can connect to
                /// </summary>
                public static Dictionary<Direction, Direction> CompatibleDirections = new Dictionary<Direction, Direction>
                {
                    { Direction.Right, Direction.Left },
                    { Direction.Left, Direction.Right },
                    { Direction.Up, Direction.Down },
                    { Direction.Down, Direction.Up },
                };

                /// <summary>
                /// Responsible for returning a list of possible track piece orientations, given an integer
                /// </summary>
                /// <param name="value">The value of the respective track piece</param>
                /// <returns>Returns list of track piece orientations</returns>
                public TrackPieceList CreateTrackPiece(int value)
                {
                    switch (value)
                    {
                        case 1:
                            var list = new TrackPieceList
                        {
                            new TrackPiece
                            {
                                Number = 1,
                                Orientation = Orientation.TrackPiece1A,
                                Directions = new List<Direction> {Direction.Left, Direction.Right},
                            },
                            new TrackPiece
                            {
                                Number = 1,
                                Orientation = Orientation.TrackPiece1B,
                                Directions = new List<Direction> { Direction.Up, Direction.Down },
                            }
                        };
                            list.Number = 1;
                            return list;
                        case 2:
                            list = new TrackPieceList
                        {
                            new TrackPiece
                            {
                                Number = 2,
                                Orientation = Orientation.TrackPiece2A,
                                Directions = new List<Direction> { Direction.Up, Direction.Right },
                            },
                            new TrackPiece
                            {
                                Number = 2,
                                Orientation = Orientation.TrackPiece2B,
                                Directions = new List<Direction> { Direction.Right, Direction.Down },
                            },
                            new TrackPiece
                            {
                                Number = 2,
                                Orientation = Orientation.TrackPiece2C,
                                Directions = new List<Direction> { Direction.Down, Direction.Left },
                            },
                             new TrackPiece
                             {
                                 Number = 2,
                                Orientation = Orientation.TrackPiece2D,
                                Directions = new List<Direction> { Direction.Left, Direction.Up },
                            }
                        };
                            list.Number = 2;
                            return list;
                        case 3:
                            list = new TrackPieceList
                        {
                            new TrackPiece
                            {
                                Number = 3,
                                Orientation = Orientation.TrackPiece3A,
                                Directions = new List<Direction> {Direction.Up, Direction.Right, Direction.Left},
                            },
                            new TrackPiece
                            {
                                Number = 3,
                                Orientation = Orientation.TrackPiece3B,
                                Directions = new List<Direction> {Direction.Up, Direction.Right, Direction.Down},
                            },
                            new TrackPiece
                            {
                                Number = 3,
                                Orientation = Orientation.TrackPiece3C,
                                Directions = new List<Direction> {Direction.Down, Direction.Right, Direction.Left},
                            },
                            new TrackPiece
                            {
                                Number = 3,
                                Orientation = Orientation.TrackPiece3D,
                                Directions = new List<Direction> {Direction.Up, Direction.Down, Direction.Left},
                            }
                        };
                            list.Number = 3;
                            return list;

                        case 4:
                            list = new TrackPieceList
                        {
                            new TrackPiece
                            {
                                Number = 4,
                                Orientation = Orientation.TrackPiece4,
                                Directions = new List<Direction> {Direction.Up, Direction.Down, Direction.Left, Direction.Right},
                            }
                        };
                            list.Number = 4;
                            return list;
                    }

                    return null;
                }
            }

            /// <summary>
            /// Track Piece interface
            /// </summary>
            public interface ITrackPiece
            {
                Orientation Orientation { get; set; }
                List<Direction> Directions { get; set; }
            }

            /// <summary>
            /// The directions a Track Piece can go
            /// </summary>
            public enum Direction
            {
                Up,
                Right,
                Down,
                Left
            }

            /// <summary>
            /// Class responsible for storing information regarding location on the board
            /// </summary>
            public class Coordinate
            {
                public int X { get; set; }
                public int Y { get; set; }

                public Coordinate(int x, int y)
                {
                    X = x;
                    Y = y;
                }

                /// <summary>
                /// Given a direction, returns the next coordinate in that direction
                /// </summary>
                /// <param name="direction">A direction.</param>
                /// <returns>The coordinate that was one unit in the direction</returns>
                public Coordinate Translate(Direction direction)
                {
                    switch (direction)
                    {
                        case Direction.Up:
                            return new Coordinate(X, Y - 1);

                        case Direction.Down:
                            return new Coordinate(X, Y + 1);

                        case Direction.Left:
                            return new Coordinate(X - 1, Y);

                        case Direction.Right:
                            return new Coordinate(X + 1, Y);
                    }

                    return null;
                }
            }

            /// <summary>
            /// Track Piece class
            /// </summary>
            public class TrackPiece : ITrackPiece
            {
                /// <summary>
                /// Track Piece Identifier
                /// </summary>
                public int Number { get; set; }

                /// <summary>
                /// The particular orientation this trackpiece has
                /// </summary>
                public Orientation Orientation { get; set; }

                /// <summary>
                /// The directions that this track piece faces
                /// </summary>
                public List<Direction> Directions { get; set; }
            }

            /// <summary>
            /// An enum that shows all the possible orientations of all track pieces
            /// </summary>
            public enum Orientation
            {
                TrackPiece1A,
                TrackPiece1B,
                TrackPiece2A,
                TrackPiece2B,
                TrackPiece2C,
                TrackPiece2D,
                TrackPiece3A,
                TrackPiece3B,
                TrackPiece3C,
                TrackPiece3D,
                TrackPiece4
            }

            /// <summary>
            /// The Board Class
            /// </summary>
            public class Board
            {
                /// <summary>
                /// Array that stores the state of the board
                /// </summary>
                public readonly TrackPiece[,] _board;

                /// <summary>
                /// The size of this board
                /// </summary>
                private readonly int size;

                /// <summary>
                /// The amount of pieces currently placed on this board
                /// </summary>
                public int PieceCount { get; private set; }

                /// <summary>
                /// Stores the available coordinates in which a piece can be placed on the board
                /// </summary>
                public List<Coordinate> AvailableCoordinates { get; private set; }

                /// <summary>
                /// Create a new board of the given size.
                /// </summary>
                /// <param name="size">Size of board</param>
                public Board(int size)
                {
                    AvailableCoordinates = new List<Coordinate>
                        {
                            new Coordinate(0,0)
                        };

                    this.size = size;
                    _board = new TrackPiece[size, size];
                }

                /// <summary>
                /// Clone the given board.
                /// </summary>
                /// <param name="other">Board</param>
                public Board(Board other)
                {
                    AvailableCoordinates = other.AvailableCoordinates;
                    PieceCount = other.PieceCount;
                    size = other.size;
                    _board = new TrackPiece[size, size];
                    Array.Copy(other._board, _board, size * size);
                }

                /// <summary>
                /// Determine if a coordinate is valid so that a piece can be potentially placed.
                /// </summary>
                /// <param name="coordinate">A coordinate</param>
                /// <returns>A bool</returns>
                private bool CoordinateIsAvailable(Coordinate coordinate)
                {
                    return CoordinateWithinBounds(coordinate) &&
                        _board[coordinate.X, coordinate.Y] == null;
                }

                /// <summary>
                /// Determine if a coordinate is within the bounds of the board.
                /// </summary>
                /// <param name="coordinate">A coordinate.</param>
                /// <returns>A bool</returns>
                private bool CoordinateWithinBounds(Coordinate coordinate)
                {
                    return coordinate.X >= 0 && coordinate.Y >= 0 &&
                           coordinate.X < size && coordinate.Y < size;
                }

                /// <summary>
                /// Attempt to add a piece to the board, return if attempt was successful
                ///  </summary>
                /// <param name="coordinate">A coordinate of where the piece will be placed</param>
                /// <param name="piece">The Track piece to be placed</param>
                /// <returns>A bool showing whether the piece was successfully added to the board</returns>
                public bool AddPiece(Coordinate coordinate, TrackPiece piece)
                {
                    // If the coordinate is within bounds and no tile is placed
                    if (CoordinateIsAvailable(coordinate))
                    {
                        var availableCoordinates = piece.Directions.Select(coordinate.Translate);

                        // This coordinate is no longer available.
                        var removeIndex = AvailableCoordinates.FindIndex(c => c.X == coordinate.X && c.Y == coordinate.Y);
                        if (removeIndex >= 0 && removeIndex < AvailableCoordinates.Count)
                        {
                            AvailableCoordinates.RemoveAt(removeIndex);
                        }

                        _board[coordinate.X, coordinate.Y] = piece;

                        // Find coordinates that become available due to this new addition.
                        AvailableCoordinates.AddRange(
                            availableCoordinates.Where(coord =>
                                !CoordinateAlreadyAvailable(coord) && CoordinateIsAvailable(coord)));

                        PieceCount++;

                        return true;
                    }

                    return false;
                }

                /// <summary>
                /// Return a list of directions that the given coordinate can connect to
                /// </summary>
                /// <param name="coordinate">A coordinate</param>
                /// <returns> List of directions </returns>
                public List<Direction> PossiblePiecesToPlace(Coordinate coordinate)
                {
                    var pieces = new List<Direction>();
                    var isOrigin = IsOriginCoord(coordinate);

                    if (CanConnect(coordinate.Translate(Direction.Left), Direction.Right, isOrigin))
                    {
                        pieces.Add(Direction.Left);
                    }

                    if (CanConnect(coordinate.Translate(Direction.Right), Direction.Left, isOrigin))
                    {
                        pieces.Add(Direction.Right);
                    }

                    if (CanConnect(coordinate.Translate(Direction.Up), Direction.Down, isOrigin))
                    {
                        pieces.Add(Direction.Up);
                    }

                    if (CanConnect(coordinate.Translate(Direction.Down), Direction.Up, isOrigin))
                    {
                        pieces.Add(Direction.Down);
                    }

                    return pieces;
                }

                /// <summary>
                /// Determine if the coordinate can connect to a piece in a given direction
                /// </summary>
                /// <param name="coordinate">The given coordinate.</param>
                /// <param name="direction">The direction the coordinate is attempting to connect with.</param>
                /// <param name="isOrigin">Are we at the origin point of 0,0?</param>
                /// <returns>Bool</returns>
                private bool CanConnect(Coordinate coordinate, Direction direction, bool isOrigin)
                {
                    if (!CoordinateWithinBounds(coordinate)) return false;

                    if (isOrigin)
                    {
                        return true;
                    }

                    var trackPiece = GetTrackPieceAtCoord(coordinate);

                    return trackPiece != null && trackPiece.Directions.Contains(direction);
                }

                /// <summary>
                /// Determine if the coordinate is the origin coordinate of 0,0
                /// </summary>
                /// <param name="coordinate">The coordinate</param>
                /// <returns>A bool</returns>
                private bool IsOriginCoord(Coordinate coordinate)
                {
                    return coordinate.X == 0 && coordinate.Y == 0;
                }

                /// <summary>
                /// Return the track piece at a specified coordinate
                /// </summary>
                /// <param name="coordinate">The given coordinate</param>
                /// <returns> The trackpiece found at the coordinate</returns>
                private TrackPiece GetTrackPieceAtCoord(Coordinate coordinate)
                {
                    return _board[coordinate.X, coordinate.Y];
                }

                /// <summary>
                /// Determine if a coordinate is already in the list of available coordinates
                /// </summary>
                /// <param name="coordinate">A coordinate</param>
                /// <returns>A bool.</returns>
                private bool CoordinateAlreadyAvailable(Coordinate coordinate)
                {
                    return AvailableCoordinates.Any(c => c.X == coordinate.X && c.Y == coordinate.Y);
                }
            }

            /// <summary>
            /// This class will be responsible for determining if an input of trackpieces will save the town.
            /// </summary>
            public class Solver
            {
                /// <summary>
                /// Static board size used for the exercise.
                /// </summary>
                private const int BoardSize = 10;

                /// <summary>
                /// The minimum path size given a board size.
                /// </summary>
                private readonly double minimumPathSize = Math.Ceiling((BoardSize * BoardSize) * 0.25);
                
                /// <summary>
                /// Stores whether the town survives after being given an input and running through the algorithm
                /// </summary>
                private bool theTownSurvives;

                /// <summary>
                /// The best board the algorithm could create given the input
                /// </summary>
                private Board furthestBoard;

                /// <summary>
                /// Determine if the town survives given the input
                /// </summary>
                /// <param name="input">A list of integers that represent the track pieces</param>
                /// <returns>Does the town survive?</returns>
                public bool Solve(List<int> input)
                {
                    theTownSurvives = false;

                    furthestBoard = new Board(BoardSize);
                    if (input.Count() >= minimumPathSize)
                    {
                        var factory = new TrackPieceFactory();

                        var trackPieces = input
                            .Select(factory.CreateTrackPiece).OrderByDescending(x => x.Number);

                        BuildViablePath(furthestBoard, trackPieces.ToList());
                    }

                    return theTownSurvives;
                }

                /// <summary>
                /// Builds a viable path of track pieces along a board. 
                /// </summary>
                /// <param name="board">A board.</param>
                /// <param name="trackPieces">A list of available track pieces.</param>
                public void BuildViablePath(Board board, List<TrackPieceList> trackPieces)
                {
                    // Iterate through all available spots on the board. 
                    // An available spot means we could potentially place a track piece on it. 
                    // This implies an adjacent track piece is compatible with the new piece.
                    foreach (var availableCoordinate in board.AvailableCoordinates.ToArray())
                    {
                        if (theTownSurvives)
                        {
                            return;
                        }

                        // Get the possible pieces that can be placed at the available coordinate.
                        var coordConnections = board.PossiblePiecesToPlace(availableCoordinate);

                        // From the current available track pieces we can place, find all viable pieces for this available coordinate.
                        var viableTrackPieces = FindNextViableTrackPieces(coordConnections, trackPieces);

                        if (!viableTrackPieces.Any())
                        {
                            continue;
                        }

                        foreach (var trackPiece in viableTrackPieces)
                        {
                            var clone = new Board(board);

                            // Attempt to add the track piece to the board.
                            if (clone.AddPiece(availableCoordinate, trackPiece))
                            {
                                // Update the list of available track pieces.
                                var remainingTrackPieces = new List<TrackPieceList>();
                                remainingTrackPieces.AddRange(trackPieces);

                                TrackPieceList test = trackPieces.First(list => list.Number == trackPiece.Number);
                                remainingTrackPieces.Remove(test);

                                // Update the board with the longest path.
                                if (clone.PieceCount > furthestBoard.PieceCount)
                                {
                                    furthestBoard = clone;
                                }

                                // Does the path length of the board reaches the minimum required path to save the town?
                                if (clone.PieceCount >= minimumPathSize)
                                {
                                    theTownSurvives = true;
                                    return;
                                }

                                // Recursively build the path further.
                                BuildViablePath(clone, remainingTrackPieces);
                            }
                        }

                    }
                }

                /// <summary>
                /// Finds viable track pieces from the list of track pieces given that match the given direction.
                /// </summary>
                /// <param name="directions">A list of directions.</param>
                /// <param name="trackPieces">A list of available track pieces.</param>
                /// <returns>A subset of the input list that matches the input directions.</returns>
                private static IEnumerable<TrackPiece> FindNextViableTrackPieces(IEnumerable<Direction> directions,
                    IEnumerable<TrackPieceList> trackPieces)
                {
                    return trackPieces.Distinct(new TrackPieceListCompare())
                        .SelectMany(i => i)
                        .Where(tp => tp.Directions.Intersect(directions).Any());
                }
            }

            /// <summary>
            /// Provides the ability to compare a list of track piece objects.
            /// </summary>
            public class TrackPieceListCompare : IEqualityComparer<TrackPieceList>
            {
                public bool Equals(TrackPieceList x, TrackPieceList y)
                {
                    return x.Number == y.Number;
                }
                public int GetHashCode(TrackPieceList piece)
                {
                    return piece.Number.GetHashCode();
                }
            }
        }
    }
}
