using System;


namespace memTest
{
    public class EventRaiser
    {
        private string stringValue;

        public string StringValue
        {
            get { return this.stringValue; }
            set
            {
                if (this.stringValue != value)
                {
                    this.stringValue = value;
                    if (this.StringValueChanged != null)
                    {
                        this.StringValueChanged(this, new EventArgs());
                    }
                }
            }
        }

        public event EventHandler StringValueChanged;
    }

    public class MemoryLeak
    {
        private byte[] allocatedMemory;
        private EventRaiser raiser;

        public MemoryLeak(EventRaiser raiser)
        {
            this.raiser = raiser;
            //allocate some memory to mimic a real life object
            this.allocatedMemory = new byte[1000000];
            raiser.StringValueChanged += new EventHandler(raiser_StringValueChanged);
        }

        private void raiser_StringValueChanged(object sender, EventArgs e)
        {

        }
    }

    public class Program
    {
        private static EventRaiser raiser;
        public static void Main(string[] args)
        {
            raiser = new EventRaiser();
            for (int i = 0; i <= 1000; i++)
            {
                CreateLeak();
            }

            Console.WriteLine("Press any key to continue...");
            Console.ReadKey(true);
        }

        private static void CreateLeak()
        {
            MemoryLeak memoryLeak = new MemoryLeak(raiser);
            memoryLeak = null;
            GC.Collect();
            long memory = GC.GetTotalMemory(true);
            Console.WriteLine("Memory being used: {0:0,0}", memory);
        }
    }
}
