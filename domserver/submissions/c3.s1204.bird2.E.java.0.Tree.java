import java.util.*;
import java.util.concurrent.ConcurrentSkipListSet;

class Main {
	public static void main (String [] args) {
		Scanner sc = new Scanner(System.in);
		StringBuilder out = new StringBuilder();
		Hashtable<String, Node> set = new Hashtable<String, Node>();
		while (sc.hasNext()) {
			String op = sc.next();
			if (op.equals("NEW")) {
				sc.next();
				String child = sc.next();
				String par1 = sc.next();
				String par2 = sc.next();
				if (!set.containsKey(par1)) {
					set.put(par1, new Node(par1));
				}
				
				if (!set.containsKey(par2)) {
					set.put(par2, new Node(par2));
				}
				
				if (!set.containsKey(child)) {
					set.put(child, new Node(child));
				}
				
				if (set.get(child).parents[0] == null) {
					set.get(child).setPar(set.get(par1), set.get(par2));
				}
				
				set.get(par1).add(set.get(child));
				set.get(par2).add(set.get(child));
			}
			else {
				ConcurrentSkipListSet<String> que = new ConcurrentSkipListSet<String>();
				if (op.equals("CHILD")) {
					Node n = set.get(sc.next());
					for (int i = 0; i < n.size(); i++) {
						que.add(n.get(i).name);
					}
					while (!que.isEmpty()) {
						out.append(que.pollFirst()+" ");
					}
					out.replace(out.length()-1, out.length(), "\n");
				}
				else if (op.equals("GRANDCHILD")) {
					Node n = set.get(sc.next());
					for (int i = 0; i < n.size(); i++) {
						for (int k = 0; k < n.get(i).size(); k++) {
							que.add(n.get(i).get(k).name);
						}
					}
					while (!que.isEmpty()) {
						out.append(que.pollFirst()+" ");
					}
					out.replace(out.length()-1, out.length(), "\n");
				}
				else if (op.equals("SIBLING")) {
					Node n = set.get(sc.next());
					for (int i = 0; i < 2; i++) {
						for (int k = 0; k < n.parents[i].size(); k++) {
							if (n.parents[i].get(k).name.equals(n.name))
								continue;
							que.add(n.parents[i].get(k).name);
						}
					}
					while (!que.isEmpty()) {
						out.append(que.pollFirst()+" ");
					}
					out.replace(out.length()-1, out.length(), "\n");
				}
				else if (op.equals("PARENT")) {
					Node n = set.get(sc.next());
					for (int i = 0; i < 2; i++) {
						if (n.parents[i] == null)
							continue;
						que.add(n.parents[i].name);
					}
					while (!que.isEmpty()) {
						out.append(que.pollFirst()+" ");
					}
					out.replace(out.length()-1, out.length(), "\n");
				}
				else if (op.equals("GRANDPARENT")) {
					Node n = set.get(sc.next());
					for (int i = 0; i < 2; i++) {
						for (int k = 0; k < 2; k++) {
							if (n.parents[i] == null || n.parents[i].parents[k] == null)
								continue;
							que.add(n.parents[i].parents[k].name);
						}
					}
					while (!que.isEmpty()) {
						out.append(que.pollFirst()+" ");
					}
					out.replace(out.length()-1, out.length(), "\n");
				}
				
			}
		}
		System.out.print(out.toString().trim());
	}
}
class Node {
	Node [] parents = new Node [2];
	ArrayList<Node> children;
	String name;
	public Node(String a) {
		name = a;
		children = new ArrayList<Node>();
	}
	
	public Node (Node p1, Node p2, String a) {
		parents[0] = p1;
		parents[1] = p2;
		name = a;
		children = new ArrayList<Node>();
	}
	
	public void setPar(Node p1, Node p2) {
		parents[0] = p1;
		parents[1] = p2;
	}
	
	public void add(Node c) {
		children.add(c);
	}
	
	public int size() {
		return children.size();
	}
	
	public Node get(int i) {
		return children.get(i);
	}
	
}