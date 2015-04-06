import java.util.*;
import java.io.*;

class UltiProcessing
{
	static HashMap<Integer, ReferenceLocation> knownRegisters;
	static SpanInfo[][] spans;
	static int[] nextFreeColumn;

	static class SpanInfo {
		//Register number
		int register;

		//'S': Start
		//'E': End
		//'N': Normal
		char state;
	}

	static class ReferenceLocation {
		int operation;
		int column;
	}

	static void saveSpan(int register, int operation, char readOrWrite) {
		SpanInfo span = new SpanInfo();
		span.register = register;

		int column = nextFreeColumn[operation];

		ReferenceLocation reference = new ReferenceLocation();
		reference.operation = operation;
		reference.column = column;

		//Write case
		if(readOrWrite == 'W') {
			span.state = 'N';
			//Update last operation this register was referenced at
			knownRegisters.put(register, reference);
		}
		//Read case
		else {
			//Register doesn't exist yet
			if(!knownRegisters.containsKey(register)) {
				span.state = 'N';
			}
			//Register already exist
			else {
				span.state = 'E';

				ReferenceLocation lastReference = knownRegisters.get(register);
				char lastState = spans[lastReference.operation][lastReference.column].state;

				if(lastState == 'N') {
					spans[lastReference.operation][lastReference.column].state = 'S';
				}
				else if(lastState == 'E') {
					spans[lastReference.operation][lastReference.column] = null;
				}
			}

			//Update last operation this register was referenced at
			knownRegisters.put(register, reference);
		}

		spans[operation][column] = span;
		nextFreeColumn[operation]++;
	}

	static int extractInt(String register) {
		return Integer.parseInt(register.substring(1));
	}

	public static void main (String[] args)
	{
		Scanner sc = new Scanner(System.in);

		//Get number of operations
		int operations = sc.nextInt();

		if(operations == 0) {
			System.out.println(0);
		}
		else if(operations > 0) {
			sc.nextLine();

			//Initialize structures
			knownRegisters = new HashMap<Integer, ReferenceLocation>();
			spans = new SpanInfo[operations][3];
			nextFreeColumn = new int[operations];

			//Parse input and create span structure
			for(int op = 0; op < operations; op++) {
				String line = sc.nextLine();
				//System.out.println(line);
				String[] opSplit = line.split("\\s+");

				//ADD operation
				if(opSplit[0].equals("ADD")) {
					if(opSplit[1].charAt(0) == 'R') {
						saveSpan(extractInt(opSplit[1]), op, 'R');
					}
					if(opSplit[2].charAt(0) == 'R') {
						if(!opSplit[2].equals(opSplit[1]))
							saveSpan(extractInt(opSplit[2]), op, 'R');
					}
					if(opSplit[3].charAt(0) == 'R') {
						if(!opSplit[1].equals(opSplit[3]))
							saveSpan(extractInt(opSplit[3]), op, 'W');
					}

				}
				//STORE operation
				else {
					if(opSplit[1].charAt(0) == 'R') {
						saveSpan(extractInt(opSplit[1]), op, 'R');
					}
					if(opSplit[2].charAt(0) == 'R') {
						if(!opSplit[1].equals(opSplit[2]))
							saveSpan(extractInt(opSplit[2]), op, 'W');
					}
				}
			}

			// for(int op = 0; op < operations; op++) {
			// 	for(int col = 0; col < 3; col++) {
			// 		if(spans[op][col] != null) {
			// 			System.out.print(spans[op][col].state + " " + spans[op][col].register + ",");
			// 		}
			// 	}
			// 	System.out.println();
			// }

			//Caculate total number of alive registers per operation
			int maxTotal = -1;
			int sip = 0;//Spans in progress
			for(int op = 0; op < operations; op++) {
				int total = 0;
				int oldSip = sip;

				for(int col = 0; col < 3; col++) {
					if(spans[op][col] != null) {
						if(spans[op][col].state == 'N' ||  spans[op][col].state == 'S')
							total++;

						if(spans[op][col].state == 'S')
							sip++;
						else if(spans[op][col].state == 'E')
							sip--;

					}
				}

				total = total + oldSip;

				if(total > maxTotal)
					maxTotal = total;
			}

			System.out.println(maxTotal);
		}
	}
}
