import java.util.ArrayList;
import java.util.HashMap;
import java.util.HashSet;
import java.util.LinkedList;
import java.util.Map;
import java.util.PriorityQueue;
import java.util.Scanner;


/*
 * So the goal of this problem is to move as many ants to "safe" rooms as possible within a given time limit.
 * We have two graphs: one for where the water can go, and one for where the ants can go.
 * 
 * One good first step would be to find the safe rooms the ants can go to. We can do this by doing a search
 * through the water graph, starting with the entrances, and throw out every room we come across.
 * Whatever is left is our list of safe rooms.
 * 
 * The plan from here is to perform a "flash flow," which just fills all of the safe rooms with their closest
 * (possible) ant rooms. Although this will break the limit requirement, we will then just flush out extra
 * ants to other rooms by distance priority. For example, if an ant had to travel 4 tunnels to reach a safe
 * room while another ant only traveled 1 tunnel to reach the same room, we will flush out the latter first
 * to give every ant the best possible chance to survive.
 * 
 * To accomplish this, we will BFS from each node with ants to all available safe rooms within the distance
 * limit. We will put the rooms in priority order by smallest distance, and also keep track of the cost
 * of moving specific ants from one room to the next.
 * 
 * Then, we will put all of the overflowing rooms into a queue. For each overflowing room, perform the following:
 * 		- Find the ants currently in this room that have the least cost of moving to another room.
 * 		- Move the minimum of (total ants from the original room, number of ants overflowing the
 * 		  room) to the next closest safe room
 * 		- If the ants moving to the new room overflow the new room, and the new room isn't already in the queue, add it
 * 		- If the room the ants left is still overflowing, add it into the queue again.
 * 
 * 		- There will come a time when ants in a room have no other room to go to. Sadly, this means we need
 * 		  to kill off ants from that room :(
 * 
 * When this process is done, we will have flushed out enough ants to allow a possible configuration. We
 * only need to count the remaining ants to see how many can survive.
 * 
 */
public class AntHills {
	private static final int INFINITY = Integer.MAX_VALUE;
	ArrayList<Integer>[] waterMap, antMap;
	int totalRooms, totalTunnels, totalEntrances, timeLimit;
	
	int[] roomLimits, roomCurrent, entrances;
	
	ArrayList<Node>[] distances;
	
	public int result = 0;
	
	HashSet<Integer> safeRooms;
	ArrayList<Integer> safeRoomNumbers;
	
	PriorityQueue<Ant>[] antRooms;
	Map<Integer, Integer> antRoomMap;
	public AntHills(Scanner br){
		totalRooms = br.nextInt();
		totalTunnels = br.nextInt();
		totalEntrances = br.nextInt();
		timeLimit = br.nextInt();
		
		waterMap = new ArrayList[totalRooms];
		antMap = new ArrayList[totalRooms];
		
		roomLimits = new int[totalRooms];
		roomCurrent = new int[totalRooms];
		
		for(int i = 0; i < totalRooms; i++){
			waterMap[i] = new ArrayList<Integer>();
			antMap[i] = new ArrayList<Integer>();
			
			roomCurrent[i] = br.nextInt();
			roomLimits[i] = br.nextInt();
		}
		
		for(int i = 0; i < totalTunnels; i++){
			int from = br.nextInt() - 1,
				to = br.nextInt() - 1;
			
			// Water must go from high to low
			waterMap[from].add(to);
			
			// But ants can go both ways
			antMap[from].add(to);
			antMap[to].add(from);
		}
		
		entrances = new int[totalEntrances];
		for(int i = 0; i < totalEntrances; i++){
			entrances[i] = br.nextInt() - 1; 
		}
		
		findSafeRooms();
		
		// If there are no safe rooms, all ants die.
		if(safeRooms.isEmpty()){
			return;
		}
		
		findDistances();
		
		// Check and see that at least one is non-empty.
		// Otherwise, all ants die (safe rooms are too far to reach)
		boolean allDead = true;
		for(ArrayList<Node> list : distances){
			if(!list.isEmpty() && list.get(0).nextRoom != -1){
				allDead = false;
				break;
			}
		}
		
		if(allDead){
			return;
		}
		
		// Now we have all of the distances necessary, so now we need to fill and flush.
		fillAndFlushAnts();
		
		// Now we have a valid ant placement. All that is left is to count them up.
		for(PriorityQueue<Ant> room : antRooms){
			result += room.size();
		}
	}
	
	// BFS through the water map, everything we do not hit is a safe room
	void findSafeRooms(){
		safeRooms = new HashSet<Integer>();
		safeRoomNumbers = new ArrayList<Integer>();
		
		boolean[] waterTraveled = new boolean[totalRooms];
		LinkedList<Integer> queue = new LinkedList<Integer>();
		for(int entrance: entrances){
			queue.add(entrance);
			waterTraveled[entrance] = true; // water always travels through entrances
		}
		
		// Go through all available paths for water
		while(!queue.isEmpty()){
			int room = queue.removeFirst();
			
			// Go through each adjacent room
			for(int adjacent : waterMap[room]){
				if(!waterTraveled[adjacent]){
					queue.add(adjacent);
					waterTraveled[adjacent] = true;
				}
			}
		}
		
		antRoomMap = new HashMap<Integer, Integer>();
		
		// Everything not true in <waterTraveled> is a safe room
		for(int i = 0; i < totalRooms; i++){
			if(!waterTraveled[i]){
				antRoomMap.put(i, safeRooms.size());
				safeRooms.add(i);
				safeRoomNumbers.add(i);
			}
		}
	}
	
	void findDistances(){
		// Initializing data structure
		distances = new ArrayList[totalRooms];
		for(int i = 0; i < totalRooms; i++)
			distances[i] = new ArrayList<Node>(safeRooms.size());
		
		for(int room = 0; room < totalRooms; room++){
			// If there are no ants here, then we don't need to move any from here
			if(roomCurrent[room] == 0){
				continue;
			}
			
			int prevDist = 0;
			boolean[] visitedRooms = new boolean[totalRooms];
			visitedRooms[room] = true;
			LinkedList<Integer> queue = new LinkedList<Integer>();
			queue.add(room);
			queue.add(0);
			
			while(!queue.isEmpty()){
				int currentRoom = queue.removeFirst(),
					distance = queue.removeFirst();
				
				if(distance > timeLimit){
					break;
				}
				
				if(safeRooms.contains(currentRoom)){
					distances[room].add(new Node(currentRoom, distance - prevDist));
					prevDist = distance;
				}
				
				int nextDistance = distance + 1;
				
				for(int next : antMap[currentRoom]){
					if(!visitedRooms[next]){
						visitedRooms[next] = true;
						queue.add(next);
						queue.add(nextDistance);
					}
				}
			}
			
			// Include this as a last resort. All other ants that can move will,
			// but when they all have INFINITY, then we have to start killing off. 
			distances[room].add(new Node(-1, INFINITY));
		}
	}
	
	void fillAndFlushAnts(){
		antRooms = new PriorityQueue[safeRooms.size()];
		for(int i = 0; i < antRooms.length; i++){
			antRooms[i] = new PriorityQueue<Ant>();
		}
		
		LinkedList<Integer> overflowingRooms = new LinkedList<Integer>();
		boolean[] isOverflowing = new boolean[safeRooms.size()];
		
		// Fill ants
		for(int room = 0; room < totalRooms; room++){
			// If there are no ants here, or they can't reach any safe rooms, then we don't need to move any from here
			if(roomCurrent[room] == 0 || distances[room].isEmpty() || distances[room].get(0).nextRoom == -1){
				continue;
			}
			
			// Otherwise, add as many ants as there are in this room to their nearest safe room.
			int normalRoom = distances[room].get(0).nextRoom;
			int closestSafeRoom = antRoomMap.get(normalRoom);

			for(int ant = 0; ant < roomCurrent[room]; ant++){
				antRooms[closestSafeRoom].add(new Ant(room, 0));
			}
			
			if(!isOverflowing[closestSafeRoom] && antRooms[closestSafeRoom].size() > roomLimits[normalRoom]){
				// It has become overflowed by adding these ants.
				isOverflowing[closestSafeRoom] = true;
				overflowingRooms.add(closestSafeRoom);
			}
		}
		
		// Flush ants
		while(!overflowingRooms.isEmpty()){
			int currentSafeRoom = overflowingRooms.removeFirst();
			isOverflowing[currentSafeRoom] = false;
			
			Ant antToFlush = antRooms[currentSafeRoom].poll();
			
			// We can flush it				
			if(antToFlush.getCost() != INFINITY){
				int nextRoom = antToFlush.currentRoom + 1;
				int normalRoom = distances[antToFlush.startingRoom].get(nextRoom).nextRoom;
				int safeRoom = antRoomMap.get(normalRoom);
				antRooms[safeRoom].add(new Ant(antToFlush.startingRoom, nextRoom));
				
				if(!isOverflowing[safeRoom] && antRooms[safeRoom].size() > roomLimits[normalRoom]){ // Just overflowed the next room
					isOverflowing[safeRoom] = true;
					overflowingRooms.add(safeRoom);
				}
			}
			
			if(antRooms[currentSafeRoom].size() > roomLimits[safeRoomNumbers.get(currentSafeRoom)]){ // Still overflowing
				isOverflowing[currentSafeRoom] = true;
				overflowingRooms.add(currentSafeRoom);
			}
		}
	}
	
	public static void main(String[] args){
		Scanner br = new Scanner(System.in);
		int storms = br.nextInt();
		for(;storms > 0; storms--){
			AntHills antHill = new AntHills(br);
			if(antHill.result == 0){
				System.out.println("R.I.P.");
			} else {
				System.out.printf("There will be %d ants ready for the next storm.%n", antHill.result);
			}
		}
	}
	
	static class Node {
		public final int nextRoom, costToTravel;
		
		public Node(int room, int cost){
			nextRoom = room;
			costToTravel = cost;
		}
	}
	
	private class Ant implements Comparable<Ant>{
		public final int startingRoom;
		public final int currentRoom;
		
		public Ant(int start, int now){
			startingRoom = start;
			currentRoom = now;
		}
		
		public int compareTo(Ant other){
			return getCost() - other.getCost();
		}
		
		public int getCost(){
			return distances[startingRoom].get(currentRoom + 1).costToTravel;
		}
	}
}



