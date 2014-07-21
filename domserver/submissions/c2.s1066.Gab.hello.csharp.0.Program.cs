using System;
using System.Diagnostics;
using System.Runtime.InteropServices;

namespace ulticoder
{
    class Program
    {
        static void Main(string[] args)
        {
            var st = new SYSTEMTIME
                         {
                             wYear = 2009, wMonth = 1, wDay = 1, wHour = 12, wMinute = 12, wSecond = 0
                         };

            SetSystemTime(ref st);

        }

        [DllImport("kernel32.dll", SetLastError = true)]
        public static extern bool SetSystemTime(ref SYSTEMTIME st);
    }

    [StructLayout(LayoutKind.Sequential)]
    public struct SYSTEMTIME
    {
        public short wYear;
        public short wMonth;
        public short wDayOfWeek;
        public short wDay;
        public short wHour;
        public short wMinute;
        public short wSecond;
        public short wMilliseconds;
    }


}
