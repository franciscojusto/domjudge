/* Sample solution for the default "Demo Contest" problem "hello"
 * @EXPECTED_RESULTS@: CORRECT
 */

import java.io.*;

class Main
{
        public static void main(String[] args) throws IOException
        {
                String text = "Hello world";
				try {
				  File file = new File("example.txt");
				  BufferedWriter output = new BufferedWriter(new FileWriter(file));
				  output.write(text);
				  output.close();
				  
				  BufferedReader reader = new BufferedReader(new FileReader("example.txt"));
				  String sCurrentLine;
				  while ((sCurrentLine = reader.readLine()) != null) {
				  System.out.println(sCurrentLine);
			}
				  
				} catch ( IOException e ) {
				   e.printStackTrace();
				}
        }
}