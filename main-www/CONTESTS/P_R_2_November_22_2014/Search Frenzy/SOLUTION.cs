using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Text;

namespace SearchingFrenzy
{
    public class Program
    {
        private static IDictionary<string, HashSet<string>> _wordWithSearchKeys;
        private static IDictionary<string, string> _searchKeyWithWord;

        private static void Main(string[] args)
        {
            #region Initialize lists

            _wordWithSearchKeys = new Dictionary<string, HashSet<string>>();
            _searchKeyWithWord = new Dictionary<string, string>();

            #endregion

            //var stopWatch = new Stopwatch();
            //stopWatch.Start();

            var numberOfItemsInRepo = Int32.Parse(Console.ReadLine());
            for (var count = 0; count < numberOfItemsInRepo; count++)
            {
                var word = Console.ReadLine();
                FindAllSearchKeysForWord(word);
            }

            var output = "";
            var numberOfSearchStrings = Int32.Parse(Console.ReadLine());
            for (var count = 0; count < numberOfSearchStrings; count++)
            {
                var word = Console.ReadLine();
                if (count != 0) output += Environment.NewLine;
                output += _wordWithSearchKeys.ContainsKey(word) && _wordWithSearchKeys[word].Any()
                    ? GetShortestSearchKey(_wordWithSearchKeys[word])
                    : "<ERROR>";
            }

            //stopWatch.Stop();

            Console.WriteLine(output);

            Console.ReadLine();
        }

        private static void FindAllSearchKeysForWord(string word)
        {
            var stopWatch = new Stopwatch();
            stopWatch.Start();

            if (_wordWithSearchKeys.ContainsKey(word))
            {
                _wordWithSearchKeys[word] = new HashSet<string>();
                return;
            }

            var alreadySearched = new HashSet<string>();
            var searchKeys = new List<string>();
            for (var count = 0; count < word.Length; count++)
            {
                for (var length = count + 1; length <= word.Length; length++)
                {
                    var substring = word.Substring(count, length - count);
                    if (alreadySearched.Contains(substring)) continue;

                    alreadySearched.Add(substring);
                    if (MarkSearchKeyAsUsed(substring, word))
                    {
                        searchKeys.Add(substring);
                    }
                }
            }

            stopWatch.Stop();
            //            Console.WriteLine("FindAllSearchKeysForWord: {0}", stopWatch.ElapsedMilliseconds);
        }

        private static bool MarkSearchKeyAsUsed(string searchKey, string word)
        {
            if (!_searchKeyWithWord.ContainsKey(searchKey))
            {
                _searchKeyWithWord[searchKey] = word;
                if (_wordWithSearchKeys.ContainsKey(word))
                    _wordWithSearchKeys[word].Add(searchKey);
                else
                    _wordWithSearchKeys[word] = new HashSet<string> { searchKey };

                return true;
            }

            if (_searchKeyWithWord[searchKey] == word) 
                return false;

            var wordSearchKeys = _wordWithSearchKeys[_searchKeyWithWord[searchKey]];
            if (wordSearchKeys.Contains(searchKey))
                wordSearchKeys.Remove(searchKey);
            return false;
        }

        private static string GetShortestSearchKey(HashSet<string> searchKeys)
        {
            var stopWatch = new Stopwatch();
            stopWatch.Start();

            if (!searchKeys.Any()) return null;

            var smallestLength = searchKeys.Min(s => s.Length);
            var key = searchKeys.Where(s => s.Length == smallestLength).OrderBy(s => s).FirstOrDefault();


            stopWatch.Stop();
            //            Console.WriteLine("GetShortestSearchKey: {0}", stopWatch.ElapsedMilliseconds);

            return key;
        }

        private static void GenerateTestFile()
        {
            StringBuilder sb = new StringBuilder();

            sb.AppendLine("100");
            for (int i = 0; i < 100; i++)
            {
                sb.AppendLine(RandomString(100).ToLower());
            }

            File.WriteAllText("random.txt", sb.ToString());
        }

        private static Random random = new Random((int)DateTime.Now.Ticks);//thanks to McAden

        private static string RandomString(int size)
        {
            StringBuilder builder = new StringBuilder();
            char ch;
            for (int i = 0; i < size; i++)
            {
                ch = Convert.ToChar(Convert.ToInt32(Math.Floor(26 * random.NextDouble() + 65)));
                builder.Append(ch);
            }

            return builder.ToString();
        }

    }
}
