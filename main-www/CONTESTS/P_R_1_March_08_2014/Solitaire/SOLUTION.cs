using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.Linq;

namespace Solitaire
{
    using System.Text;

    public class Program
    {
        private static long[][] _map;
        private static long[][] _memo;
        private static int _maxMask;

        static void Main(string[] args)
        {
            IEnumerable<Game> games = new GameSetup().ParseGames();

            foreach (Game game in games)
            {
                Console.WriteLine(GetGameResult(game.Cards));      
            }
        }

        public static string GetGameResult(long[] cards)
        {
            // Initialize all arrays
            _map = new long[cards.Length][];
            _memo = new long[cards.Length][];
            _maxMask = (1 << cards.Length) - 1;

            // Fill the "map" with edge weights

            for (int index = 0; index < cards.Length; index++)
            {
                _map[index] = new long[cards.Length];
                for (int inner = 0; inner < cards.Length; inner++)
                {
                    _map[index][inner] = ComputeWeight(cards[index], cards[inner]);
                }

                // Fill memo with an unattainable value (-1)
                _memo[index] = new long[1 << cards.Length];
                for (int j = 0; j < _memo[index].Length; j++)
                {
                    _memo[index][j] = -1;
                }
            }

           // Console.WriteLine("Finished initalizing arrays after " + watch.Elapsed.TotalSeconds + " seconds");

            long minimum = long.MaxValue;

            // Get minimum total points by trying every starting card
            for (int i = 0; i < cards.Length; i++)
            {
                minimum = Math.Min(minimum, go(i, 1 << i));
            }

            int curMask = 0; // this is the current mask we are on
            int prevIndex = -1;
            StringBuilder sb = new StringBuilder();

            // Revisit the memo
            // Or, go through the memo array by tracing your steps you left behind
            // The smallest value in each place will be your next target

            // Find best result for each card
            for (int card = 0; card < cards.Length; card++)
            {
                // Keep best index and best mask, for later
                int minIndex = 0;
                int minMask = -1; // 1 << 0
                long minValue = 0;

                // Run through each of the possible cards, and try it
                for (int i = 0; i < cards.Length; i++)
                {
                    // The next mask is the mask we would be going to if we took this card next
                    int nextMask = curMask | (1 << i);
                    long nextValue = _memo[i][nextMask] + (prevIndex == -1 ? 0 : _map[prevIndex][i]);

                    // If we have not used this card, and it is the best so far
                    if (_memo[i][nextMask] != -1 && nextMask != curMask && (nextValue < minValue || minMask == -1))
                    {
                        // Then replace it
                        minIndex = i;
                        minMask = nextMask;
                        minValue = nextValue;
                    }
                }

                // We found the best card, so append it to the StringBuilder
                sb.Append(cards[minIndex]).Append(" ");

                // Update our mask with the new version
                curMask = minMask;
                prevIndex = minIndex;
            }

            // Append the total score
            sb.AppendFormat(": {0}", minimum);

            // Return the string
            return sb.ToString();
        }

        // Index is the card we came from, mask is an int representing which cards we have already used
        // For example, if mask = 9, then 9 in binary is 1001
        // That means we took the first and last cards, so we can only go to the second or third next
        private static long go(int index, int mask)
        {
            // If we have already visited it
            if (_memo[index][mask] != -1)
            {
                // Then we already know the answer
                return _memo[index][mask];
            }

            // If we have visited every card, then we have nowhere else to go
            if (mask == _maxMask)
            {
                // Return 0, but store it for later
                return _memo[index][mask] = 0;
            }

            long minimum = long.MaxValue;

            // Try going to every card you haven't been to
            for (int i = 0; i < _map.Length; i++)
            {
                // If the mask has the bit at i off
                if ((mask & (1 << i)) == 0)
                {
                    // Minimum becomes min of current minimum
                    // and the result of taking this card + the edge weight
                    // from our current card to the next card
                    minimum = Math.Min(minimum, go(i, mask | (1 << i)) + _map[index][i]);
                }
            }

            // Our minimum is found, store it and return it
            return _memo[index][mask] = minimum;
        }

        private static long ComputeWeight(long card1, long card2)
        {
            return Math.Abs(card1 - card2) * gcd(card1, card2);
        }

        private static long gcd(long a, long b)
        {
            return b == 0 ? a : gcd(b, a % b);
        }
    }

    public class GameSetup
    {
        public IEnumerable<Game> ParseGames()
        {
            IList<Game> games = new List<Game>();
            string ca = Console.ReadLine();
            int cases = int.Parse(ca);
            for (int i = 0; i < cases; i++)
            {
                int cards = int.Parse(Console.ReadLine());
                long[] values = new long[cards];
                for (int c = 0; c < cards; c++)
                {
                    values[c] = int.Parse(Console.ReadLine());
                }

                games.Add(new Game (values));
            }

            return games;
        }
    }

    public class Game
    {
        public Game(IEnumerable<long> cards)
        {
            Cards = cards.OrderBy(x => x).ToArray();
        }

        public long[] Cards
        {
            get; set;
        }
    }
}