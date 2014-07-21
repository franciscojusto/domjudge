import java.util.Scanner;
import java.util.ArrayList;

class Main {
	public static void main(String...args) {
		Scanner in = new Scanner(System.in);

		ArrayList<TreeNode> people = new ArrayList<TreeNode>();
		String line = in.nextLine();
		while (!line.equals("") && !(line == null)) {
			String[] lineArr = line.split(" ");
			if (lineArr[0].equals("NEW")) {
				// OPERATION
				boolean childExists = false;
				boolean par1Exists = false;
				boolean par2Exists = false;
				for (TreeNode person : people) {
					if (person.name.equals(lineArr[2])) childExists = true;
					else if (person.name.equals(lineArr[3])) par1Exists = true;
					else if (person.name.equals(lineArr[4])) par2Exists = true;
				}
				if (!childExists) people.add(new TreeNode(lineArr[2]));
				if (!par1Exists) people.add(new TreeNode(lineArr[3]));
				if (!par2Exists) people.add(new TreeNode(lineArr[4]));

				// ADD CHILD TO PARENT 1 AND VICE VERSA
				for (TreeNode parent : people) {
					if (parent.name.equals(lineArr[3])) {
						for (TreeNode child : people) {
							if (child.name.equals(lineArr[2])) {
								parent.children.add(child);
								child.parents.add(parent);
								break;
							}
						}
						break;
					}
				}
				// ADD CHILD TO PARENT 2 AND VICE VERSA
				for (TreeNode parent : people) {
					if (parent.name.equals(lineArr[4])) {
						for (TreeNode child : people) {
							if (child.name.equals(lineArr[2])) {
								parent.children.add(child);
								child.parents.add(parent);
								break;
							}
						}
						break;
					}
				}
			} else {
				// QUERY
				if (lineArr[0].equals("SIBLING")) {
					TreeNode parent = null;
					String result = "";
					for (TreeNode person : people) {
						if (person.name.equals(lineArr[1].trim())) {
							parent = person.parents.get(0);
							break;
						}
					}
					if (parent != null) {
						for (TreeNode child : parent.children) {
							if (!child.name.equals(lineArr[1]))
								result += child.name + " ";
						}
					}
					System.out.println(result.trim());
				}
				else if (lineArr[0].equals("PARENT")) {
					String result = "";
					for (TreeNode person : people) {
						if (person.name.equals(lineArr[1].trim())) {
							for (TreeNode parent : person.parents)
								result += parent.name + " ";
						}
					}
					System.out.println(result.trim());
				}
				else if (lineArr[0].equals("CHILD")) {
					String result = "";
					for (TreeNode person : people) {
						if (person.name.equals(lineArr[1].trim())) {
							for (TreeNode child : person.children)
								result += child.name + " ";
						}
					}
					System.out.println(result.trim());
				}
				else if (lineArr[0].equals("GRANDCHILD")) {
					String result = "";
					for (TreeNode person : people) {
						if (person.name.equals(lineArr[1].trim())) {
							for (TreeNode child : person.children)
								for (TreeNode grandchild : child.children) {
									result += grandchild.name + " ";
								}
						}
					}
					System.out.println(result.trim());
				}
				else if (lineArr[0].equals("GRANDPARENT")) {
					String result = "";
					for (TreeNode person : people) {
						if (person.name.equals(lineArr[1].trim())) {
							for (TreeNode parent : person.parents)
								for (TreeNode grandparent : parent.parents) {
									result += grandparent.name + " ";
								}
						}
					}
					System.out.println(result.trim());
				}
			}
			line = in.nextLine();
		}
	}
}

class TreeNode {
	String name;
	ArrayList<TreeNode> children = new ArrayList<TreeNode>();
	ArrayList<TreeNode> parents = new ArrayList<TreeNode>();

	public TreeNode(String name) {
		this.name = name;
	}

	public String toString() {
		String out = name;
		out += "\nChildren: ";
		for (TreeNode child : children) out += child.name + " ";
		out += "\nParents: ";
		for (TreeNode parent : parents) out += parent.name + " ";
		return out;
	}
}