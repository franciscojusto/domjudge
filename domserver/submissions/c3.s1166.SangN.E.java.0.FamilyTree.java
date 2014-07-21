import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.Scanner;


public class FamilyTree {
	private static Scanner input;

	public static void main(String[] arg) {
		input = new Scanner(System.in);

		HashMap<String, ArrayList<String>> sibling = new HashMap<String, ArrayList<String>>();
		HashMap<String, String> parent = new HashMap<String, String>();

		while (input.hasNextLine()) {
			String rule = input.nextLine();
			String[] split = rule.split("\\s");

			if (rule.contains("NEW CHILD ")) {
				String parentString = split[3] + " " + split[4];
				String childString = split[2];
				if (sibling.containsKey(parentString)) {
					sibling.get(parentString).add(childString);
				} else {
					ArrayList<String> siblingList = new ArrayList<String>();
					siblingList.add(childString);
					sibling.put(parentString, siblingList);
				}
				parent.put(childString, parentString);
			} else if (rule.contains("SIBLING ")) {
				System.out.println(findSibling(sibling, parent, split));
			} else if (rule.contains("GRANDCHILD ")) {
				System.out.println(findGrandChildren(sibling, split));
			} else if (rule.contains("GRANDPARENT ")) {
				System.out.println(findGrandParent(sibling, parent, split));
			} else if (rule.contains("PARENT ")) {
				System.out.println(findParent(sibling, parent, split));
			} else if (rule.contains("CHILD ")) {
				System.out.println(findChild(sibling, parent, split));
			}
		}
	}

	private static String findChild(HashMap<String, ArrayList<String>> sibling,
			HashMap<String, String> parent, String[] split) {
		String result = "";
		ArrayList<String> children = getChildOfParent(sibling, split[1]);
		result = arrayToString(result, children);
		return result;
	}

	private static String findGrandParent(
			HashMap<String, ArrayList<String>> sibling,
			HashMap<String, String> parent, String[] split) {
		String child = split[1];
		String result = "";
		ArrayList<String> grandParent = new ArrayList<String>();
		if (parent.containsKey(child)) {
			String[] parents = parent.get(child).split("\\s");
			for (String p : parents) {
				if (parent.containsKey(p)) {
					String[] grandParents = parent.get(p).split("\\s");
					for (String s : grandParents) {
						grandParent.add(s);
					}
				}
			}
		}
		result = arrayToString(result, grandParent);
		return result;
	}

	private static String findGrandChildren(
			HashMap<String, ArrayList<String>> sibling, String[] split) {
		ArrayList<String> child = new ArrayList<String>();
		ArrayList<String> grandChildren = new ArrayList<String>();
		String grandParent = split[1];

		child.addAll(getChildOfParent(sibling, grandParent));
		if (child != null && child.size() > 0) {
			for (String c: child) {
				grandChildren.addAll(getChildOfParent(sibling, c));
			}
		}
		String result = "";
		return arrayToString(result, grandChildren);
	}

	private static ArrayList<String> getChildOfParent(
			HashMap<String, ArrayList<String>> sibling, String parent) {
		ArrayList<String> children = new ArrayList<String>();
		for (String key : sibling.keySet()) {
			String[] parents = key.split("\\s");
			if (parents[1].equals(parent) || parents[0].equals(parent)) {
				children.addAll(sibling.get(key));
			}
		}
		return children;
	}

	private static String findParent(
			HashMap<String, ArrayList<String>> sibling,
			HashMap<String, String> parent, String[] split) {
		String childString = split[1];
		String result = "";
		if (parent.containsKey(childString)) {
			result = result + parent.get(childString);
		}
		return result;
	}

	private static String findSibling(
			HashMap<String, ArrayList<String>> sibling,
			HashMap<String, String> parent, String[] split) {
		String childString = split[1];
		String result = "";
		if (parent.containsKey(childString)) {
			ArrayList<String> copy = (ArrayList<String>) sibling.get(
					parent.get(childString)).clone();
			copy.remove(childString);
			result = arrayToString(result, copy);
		}
		return result;
	}

	private static String arrayToString(String result, ArrayList<String> copy) {
		Collections.sort(copy, String.CASE_INSENSITIVE_ORDER);
		for (int i = 0; i < copy.size() - 1; i++) {
			result = result + copy.get(i) + " ";
		}
		result = result + copy.get(copy.size() - 1);
		return result;
	}
}
