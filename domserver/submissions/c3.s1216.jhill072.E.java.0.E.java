import java.io.File;
import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.Scanner;
import java.util.TreeSet;


public class E {
	public static void main(String[] args)throws Exception{
//		Scanner br = new Scanner(System.in);
		Scanner br = new Scanner(new File("tree.in"));
		HashMap<String, Person> people = new HashMap<String, Person>();
		boolean first = true;
		while(br.hasNext()){
			String op = br.next();
			String toPrint = first ? "" : "\n";
			if(op.equals("NEW")){
				br.next();
				Person child = new Person(br.next());
				String parent1 = br.next(), parent2 = br.next();
				Person p1, p2;
				if(!people.containsKey(parent1)){
					people.put(parent1, new Person(parent1));
				}
				if(!people.containsKey(parent2)){
					people.put(parent2, new Person(parent2));
				}
				
				p1 = people.get(parent1);
				p2 = people.get(parent2);
				
				child.parent1 = p1;
				child.parent2 = p2;
				
				p1.children.add(child);
				p2.children.add(child);
				
				people.put(child.name, child);
				people.put(parent1, p1);
				people.put(parent2, p2);
				toPrint = "";
			} else if(op.equals("CHILD")){
				Person p = people.get(br.next());
				TreeSet<Person> result = new TreeSet<Person>();
				result.addAll(p.children);
				String s = result.toString().replaceAll(",", "");
				toPrint += s.substring(1, s.length() - 1);
			} else if(op.equals("PARENT")){
				Person p = people.get(br.next());
				TreeSet<Person> parents = new TreeSet<Person>();
				if(p.parent1 != null){
				parents.add(p.parent1);
				parents.add(p.parent2);
				}
				String s = parents.toString().replaceAll(",", "");
				toPrint += s.substring(1, s.length() - 1);
			} else if(op.equals("GRANDPARENT")){
				Person p = people.get(br.next());
				TreeSet<Person> parents = new TreeSet<Person>();
				if(p.parent1.parent1 != null){
				parents.add(p.parent1.parent1);
				parents.add(p.parent1.parent2);
				}
				if(p.parent2.parent1!=null){
				parents.add(p.parent2.parent1);
				parents.add(p.parent2.parent2);
				}
				String s = parents.toString().replaceAll(",", "");
				toPrint += s.substring(1, s.length() - 1);
			} else if(op.equals("GRANDCHILD")){
				Person p = people.get(br.next());
				TreeSet<Person> children = new TreeSet<Person>();
				for(Person c : p.children){
					children.addAll(c.children);
				}

				String s = children.toString().replaceAll(",", "");
				toPrint += s.substring(1, s.length() - 1);
			} else{
				Person p = people.get(br.next());
				TreeSet<Person> children = new TreeSet<Person>();
				children.addAll(p.parent1.children);
				children.addAll(p.parent2.children);
				
				children.remove(p);

				String s = children.toString().replaceAll(",", "");
				toPrint += s.substring(1, s.length() - 1);
			}
			
			System.out.print(toPrint);
			first &= toPrint.isEmpty();
		}
	}
	
	public static class Person implements Comparable<Person>{
		String name;
		ArrayList<Person> children;
		Person parent1, parent2;
		public Person(String n){
			name = n;
			children = new ArrayList<Person>();
			parent1 = parent2 = null;
		}
		
		public int compareTo(Person o){
			return name.compareTo(o.name);
		}
		
		public String toString(){
			return name;
		}
	}
}
