using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Text.RegularExpressions;

namespace ChessPlease
{
    using System.IO;

    public class Program
    {
        private const char Queen = 'Q';
        private const char Rook = 'R';
        private const char Bishop = 'B';
        private const char King = 'K';
        private const char Knight = 'N';
        private const char Pawn = 'P';
        private const char Opponent = 'X';
        private const char Blank = '.';

        #region Moves

        private static readonly sbyte[,] queenMoves =
        {
            { -1, 1 }, { 0, 1 }, { 1, 1 }, 
            { -1, 0 },           { 1, 0 },
            { -1, -1 }, { 0, -1 }, { 1, -1 }
        };

        private static readonly sbyte[,] rookMoves =
        {
                { 0, 1 }, 
            { -1, 0 },  { 1, 0 },
             { 0, -1 }
        };

        private static readonly sbyte[,] bishopMoves =
        {
            { -1, 1 },  { 1, 1 },
            { -1, -1 }, { 1, -1 }
        };

        private static readonly sbyte[,] kingMoves =
        {
            { -1, 1 }, { 0, 1 }, { 1, 1 }, 
            { -1, 0 },  { 1, 0 },
            { -1, -1 }, { 0, -1 }, { 1, -1 }
        };

        private static readonly sbyte[,] knightMoves =
        {
            { -1, 2 }, { 1, 2 }, 
            { -2, 1 }, { 2, 1 },
            {-2, -1}, {2, -1},
            { -1, -2 }, { 1, -2 }
        };

        private static readonly sbyte[,] pawnMoves =
        {
            { -1, 0 }
        };

        #endregion

        private static readonly sbyte[] maxMoves = { 8, 8, 8, 1, 1, 1 };

        private static readonly char[,] board = new char[8, 8];

        private static int numberOfGames;

        private static Piece[] pieces;

        private static byte totake;

        private static byte[,] totakePositon;

        private static int totalTaken;

        private static Queue<byte> state;

        private static byte[, , , , , ,] visited;

        private static byte[] initialpositions;

        private static bool[] pieceCanTake;

        private static bool[,,] extraPieceVisited;

        private static void Main(string[] args)
        {
            visited = new byte[8, 8, 8, 8, 8, 8, 256];

            // Get rid of any extra characters like \r or \n
            numberOfGames = int.Parse(new Regex("[^0-9]*").Replace(Console.ReadLine(), ""));

            for(int game = 0; game< numberOfGames; game++)
            {
                pieces = new Piece[3];
                byte numberOfPiece = 0;
                totake = 1;
                totalTaken = 0;
                totakePositon = new byte[8, 8];
                state = new Queue<byte>();
                initialpositions = new byte[] { 0, 0, 0, 0, 0, 0 };
                pieceCanTake = new bool[3];
                extraPieceVisited = new bool[3,8,8];

                // Used for determining if a pawn could take a piece
                bool[] inColumn = new bool[8];

                // Used for determining if a bishop could ever take a piece
                bool onWhite = false, onBlack = false;

                for (byte i = 0; i < 8; i++)
                {
                    char[] line = Console.ReadLine().ToCharArray();

                    for (byte j = 0; j < 8; j++)
                    {
                        char position = line[j];
                        if (position != Opponent && position != Blank)
                        {
                            pieces[numberOfPiece] = new Piece { PieceType = position, XPosition = i, YPosition = j };
                            initialpositions[numberOfPiece * 2] = i;
                            initialpositions[numberOfPiece * 2 + 1] = j;

                            if (position == Pawn)
                            {
                                int left = j - 1, right = j + 1;

                                pieceCanTake[numberOfPiece] = (left >= 0 && inColumn[left] ||
                                                               right < 8 && inColumn[right]);
                            }
                            else
                            {
                                pieceCanTake[numberOfPiece] = true;
                            }

                            numberOfPiece++;
                        }
                        if (position == Opponent)
                        {
                            totakePositon[i, j] = totake;
                            totalTaken = totalTaken | (1 << (totake - 1));
                            totake++;

                            onWhite = onWhite || (i + j) % 2 == 0;
                            onBlack = onBlack || (i + j) % 2 == 1;

                            inColumn[j] = true;
                        }
                    }
                }

                for (int i = 0; i < 3; i++)
                {
                    if (pieces[i] != null && pieces[i].PieceType == Bishop)
                    {
                        pieceCanTake[i] = ((pieces[i].XPosition + pieces[i].YPosition) % 2) == 0 ? onWhite : onBlack;
                    }
                }

                if (numberOfPiece == 0)
                {
                    Console.WriteLine(0);
                    continue;
                }

                Extensions.FillArray(visited, totalTaken);

                visited[
                    initialpositions[0], initialpositions[1],
                    initialpositions[2], initialpositions[3],
                    initialpositions[4], initialpositions[5],
                    0] = 0;

                for (int i = 0; i < 3; i++)
                {
                    if (!pieceCanTake[i])
                    {
                        extraPieceVisited[i, initialpositions[i*2], initialpositions[i*2 + 1]] = true;
                    }
                }

                EnqueueState(initialpositions[0], initialpositions[1],
                    initialpositions[2], initialpositions[3],
                    initialpositions[4], initialpositions[5],
                    0);

                bool running = true;
                while (running)
                {
                    byte[,] currentState = new byte[3, 2];
                    for (int i = 0; i < 3; i++)
                    {
                        currentState[i, 0] = state.Dequeue();
                        currentState[i, 1] = state.Dequeue();
                    }

                    int currentTaken = state.Dequeue();

                    for (int i = 0; i < pieces.Length && running; i++)
                    {
                        Piece p = pieces[i];

                        if (p != null)
                        {
                            sbyte[,] moves = { {} };
                            int index = -1;
                            switch (p.PieceType)
                            {
                                case Queen:
                                    moves = queenMoves;
                                    index = 0;
                                    break;
                                case Rook:
                                    moves = rookMoves;
                                    index = 1;
                                    break;
                                case Bishop:
                                    moves = bishopMoves;
                                    index = 2;
                                    break;
                                case King:
                                    moves = kingMoves;
                                    index = 3;
                                    break;
                                case Knight:
                                    moves = knightMoves;
                                    index = 4;
                                    break;
                                case Pawn:
                                    moves = pawnMoves;
                                    index = 5;
                                    break;
                            }

                            for (int j = 0; j < moves.GetLength(0) && running; j++)
                            {
                                int currentX = moves[j, 0] + currentState[i, 0];
                                int currentY = moves[j, 1] + currentState[i, 1];

                                for (int k = 1;
                                    moveOnBoard(currentX, currentY)
                                        && k <= maxMoves[index];
                                    k++, 
                                    currentX += moves[j,0],
                                    currentY += moves[j,1])
                                {
                                    if (!isMyPieceThere(currentX, currentY, currentState))
                                    {
                                        if (TakePiece(i, currentX, currentY, currentTaken, currentState, ref running))
                                        {
                                            break;
                                        }
                                    }
                                    else
                                    {
                                        //break out of k
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        private static void SetVisitedAndEnqueState(byte[,] nextState, byte[,] currentState, int taken, int currentTaken)
        {
            if (visited[nextState[0, 0], nextState[0, 1], nextState[1, 0], nextState[1, 1], nextState[2, 0], nextState[2, 1], taken] == 255)
            {
                visited[nextState[0, 0], nextState[0, 1], nextState[1, 0], nextState[1, 1], nextState[2, 0], nextState[2, 1], taken]
                    = (byte)(visited[currentState[0, 0], currentState[0, 1], currentState[1, 0], currentState[1, 1], currentState[2, 0],currentState[2, 1], currentTaken] + 1);

                EnqueueState(nextState[0, 0], nextState[0, 1], nextState[1, 0], nextState[1, 1], nextState[2, 0],nextState[2, 1], (byte) taken);
            }
        }

        private static bool TakePiece(int index, int currentX, int currentY, int currentTaken, byte[,] currentState, ref bool running)
        {
            Piece p = pieces[index];
            byte[,] nextState = new byte[3, 2] { { currentState[0, 0], currentState[0, 1] }, { currentState[1, 0], currentState[1, 1] }, { currentState[2, 0], currentState[2, 1] } };

            if (p.PieceType != Pawn)
            {
                if (totakePositon[currentX, currentY] != 0)
                {
                    int taken = currentTaken | (1 << (totakePositon[currentX, currentY] - 1));

                    if (taken == totalTaken)
                    {
                        Console.WriteLine(
                            visited[
                                currentState[0, 0], currentState[0, 1],
                                currentState[1, 0], currentState[1, 1],
                                currentState[2, 0], currentState[2, 1],
                                currentTaken] + 1);
                        running = false;
                        return true;
                    }
                    nextState[index, 0] = (byte) currentX;
                    nextState[index, 1] = (byte) currentY;

                    SetVisitedAndEnqueState(nextState, currentState, taken, currentTaken);
                }
                else
                {
                    nextState[index, 0] = (byte) currentX;
                    nextState[index, 1] = (byte) currentY;

                    // If a piece won't take anything and we have already been there, don't go there
                    if (pieceCanTake[index] || !extraPieceVisited[index, currentX, currentY])
                    {
                        SetVisitedAndEnqueState(nextState, currentState, currentTaken, currentTaken);
                        extraPieceVisited[index, currentX, currentY] = true;
                    }
                }
            }
            else
            {
                if (moveOnBoard(currentX, currentY - 1) && totakePositon[currentX, currentY - 1] != 0 && !isMyPieceThere(currentX, currentY - 1, currentState))
                {
                    int taken = currentTaken | (1 << (totakePositon[currentX, currentY - 1] - 1));

                    if (taken == totalTaken)
                    {
                        Console.WriteLine(
                            visited[
                                currentState[0, 0], currentState[0, 1], 
                                currentState[1, 0], currentState[1, 1],
                                currentState[2, 0], currentState[2, 1], 
                                currentTaken] + 1);
                        running = false;
                        return true;
                    }


                    nextState[index, 0] = (byte)currentX;
                    nextState[index, 1] = (byte)(currentY - 1);

                    SetVisitedAndEnqueState(nextState, currentState, taken, currentTaken);
                }

                if (moveOnBoard(currentX, currentY + 1) && totakePositon[currentX, currentY + 1] != 0 && !isMyPieceThere(currentX, currentY + 1, currentState))
                {
                    int taken = currentTaken | (1 << (totakePositon[currentX, currentY + 1] - 1));

                    if (taken == totalTaken)
                    {
                        Console.WriteLine(
                            visited[
                                currentState[0, 0], currentState[0, 1], 
                                currentState[1, 0], currentState[1, 1],
                                currentState[2, 0], currentState[2, 1], 
                                currentTaken] + 1);
                        running = false;
                        return true;
                    }

                    nextState[index, 0] = (byte)currentX;
                    nextState[index, 1] = (byte)(currentY + 1);

                    SetVisitedAndEnqueState(nextState, currentState, taken, currentTaken);
                }

                if (totakePositon[currentX, currentY] == 0 || ((1 << (totakePositon[currentX, currentY] - 1)) & currentTaken) > 0)
                {
                    nextState[index, 0] = (byte)currentX;
                    nextState[index, 1] = (byte)currentY;

                    // If a piece won't take anything and we have already been there, don't go there
                    if (pieceCanTake[index] || !extraPieceVisited[index, currentX, currentY])
                    {
                        SetVisitedAndEnqueState(nextState, currentState, currentTaken, currentTaken);
                        extraPieceVisited[index, currentX, currentY] = true;
                    }
                }
            }

            return false;
        }

        private class Piece
        {
            public char PieceType { get; set; }
            public byte XPosition { get; set; }
            public byte YPosition { get; set; }
        }

        private static bool moveOnBoard(int x, int y)
        {
            if (0 > x || x > 7)
            {
                return false;
            }

            if (0 > y || y > 7)
            {
                return false;
            }

            return true;
        }

        private static bool isMyPieceThere(int x, int y, byte[,] currentpieces)
        {
            for (int i = 0; i < pieces.Length; i++)
            {
                if (pieces[i] != null && currentpieces[i, 0] == x && currentpieces[i, 1] == y)
                {
                    return true;
                }
            }

            return false;
        }

        private static void EnqueueState(byte pos1x, byte pos1y, byte pos2x, byte pos2y, byte pos3x, byte pos3y, byte taken)
        {
            state.Enqueue(pos1x);
            state.Enqueue(pos1y);
            state.Enqueue(pos2x);
            state.Enqueue(pos2y);
            state.Enqueue(pos3x);
            state.Enqueue(pos3y);
            state.Enqueue(taken);
        }
    }

    public static class Extensions
    {
       public static void FillArray(byte[, , , , , ,] matrix, int bitMaskSize)
        {
            for (int i = 0; i < 8; i++)
            {
                for (int j = 0; j < 8; j++)
                {
                    for (int k = 0; k < 8; k++)
                    {
                        for (int l = 0; l < 8; l++)
                        {
                            for (int m = 0; m < 8; m++)
                            {
                                for (int n = 0; n < 8; n++)
                                {
                                    for (int o = 0; o < bitMaskSize; o++)
                                    {
                                        matrix[i, j, k, l, m, n, o] = 255;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
