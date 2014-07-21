import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.LinkedList;
import java.util.Scanner;



public class FamilyTree {
	HashMap<String, Node> familyMap;
	void start(){
		Scanner scan = new Scanner(System.in);
		familyMap = new HashMap<String, Node>();
		boolean lineBr = false;
		while(scan.hasNextLine()){
			String line = scan.nextLine();
			String[] arr = line.split(" " );
			
			if(arr.length < 2)continue;
			
			
			if(line.startsWith("NEW")){
				
				Node parent1 = familyMap.get(arr[3]) == null ? new Node(arr[3]) : familyMap.get(arr[3]);
				Node parent2 = familyMap.get(arr[4]) == null ? new Node(arr[4]) : familyMap.get(arr[4]);
				Node child   = familyMap.get(arr[2]) == null ? new Node(arr[2], parent1,parent2) : familyMap.get(arr[2]);
				
				
				familyMap.put(arr[3], parent1);
				familyMap.put(arr[4], parent2);
				familyMap.put(arr[2], child);

				parent1.children.add(child);
				
				parent2.children.add(child);
				
			}else {
				if(lineBr)
					System.out.println();
				lineBr = true;
				
				String query = arr[0];
				String name = arr[1];
				Node person = this.familyMap.get(name);
				if(query.equalsIgnoreCase("SIBLING")){
					
					ArrayList<String> sibs = new ArrayList<String>();
					Node aParent = person.parent1 != null ? person.parent1 : person.parent1;
					
					if(aParent != null){
						for(Node sib : aParent.children){
							if(!sib.name.equalsIgnoreCase(name))
								sibs.add(sib.name);
						}
					}
					System.out.printf("%s", getAllNames(sibs));
					
				}else if(query.equalsIgnoreCase("PARENT")){
					
					ArrayList<String> parentsList = new ArrayList<String>();
					if(person.parent1 != null )parentsList.add(person.parent1.name);
					if(person.parent2 != null )parentsList.add(person.parent2.name);
					
					System.out.printf("%s", getAllNames(parentsList));
					
				}else if(query.equalsIgnoreCase("CHILD")){
					ArrayList<String> childs = new ArrayList<String>();
					for(Node sib : person.children){
						childs.add(sib.name);
					}
					System.out.printf("%s", getAllNames(childs));
				}else if(query.equalsIgnoreCase("GRANDPARENT")){
					
					ArrayList<String> granpas = new ArrayList<String>();
					if(person.parent1 != null){
						if(person.parent1.parent1 != null)granpas.add(person.parent1.parent1.name );
						if(person.parent1.parent2 != null)granpas.add( person.parent1.parent2.name );
					}
					if(person.parent2 != null){
						if(person.parent2.parent1 != null)granpas.add( person.parent2.parent1.name );
						if(person.parent2.parent2 != null)granpas.add( person.parent2.parent2.name );
					}
					System.out.printf("%s", getAllNames(granpas));
					
					
				}else if(query.equalsIgnoreCase("GRANDCHILD")){
					
					
					ArrayList<String> granChild = new ArrayList<String>();
					for(Node child1 : person.children){
						for(Node child2 : child1.children ){
							granChild.add(child2.name);
						}
					}
					System.out.printf("%s", getAllNames(granChild));
					
					
				}
				
			}
		}
	}
	
	String getAllNames(Node[] nodes){
		ArrayList<String> allFamily = new ArrayList<String>();
		for (int i = 0; i < nodes.length; i++) {
			if(nodes[i] != null)
				allFamily.add(nodes[i].name);
		}
		return getAllNames(allFamily);
	}
	String getAllNames(ArrayList<String> nodes){

		Collections.sort(nodes);
		String outString = new String();
		boolean line = false;
		for (String string : nodes) {
			if(string == null )continue;
			outString += line ? " " : "";
			line = true;
			outString +=  string;
		}
		return outString;
	}
	
	class Node{
		String name; 
		Node parent1, parent2;
		LinkedList<Node> children;
		public Node(String name, Node p1 , Node p2){
			this.name = name;
			this.parent1 = p1;
			this.parent2 = p2;
			children = new LinkedList<Node>();
		}
		public Node(String name){
			this(name, null , null);

		}
	}
	
	public static void main(String[] args) {
		new FamilyTree().start();
	}
}
