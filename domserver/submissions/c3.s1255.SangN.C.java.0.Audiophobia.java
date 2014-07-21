import java.util.ArrayList;
import java.util.HashMap;
import java.util.Scanner;

public class Audiophobia {
	private static Scanner input;

	public static void main(String[] arg) {
		input = new Scanner(System.in);
		int C = input.nextInt();
		int S = input.nextInt();
		int Q = input.nextInt();
		HashMap<Integer, ArrayList<Integer>> route = new HashMap<Integer, ArrayList<Integer>>();
		HashMap<String, Integer> dec = new HashMap<String, Integer>();
		boolean firstTime = true;
		int cases = 1;
		while (!(C == 0 && S == 0 && Q == 0)) {
			if (S == 0 && Q == 0) {
				C = input.nextInt();
				S = input.nextInt();
				Q = input.nextInt();
				route.clear();
				dec.clear();
				cases++;
				continue;
			}
			if (cases > 1) {
				System.out.println();
				System.out.println();
			}
			System.out.print("Case #" + cases);
			while (S != 0) {
				S--;
				int first = input.nextInt();
				int second = input.nextInt();
				int decs = input.nextInt();
				if (first < second) {
					dec.put(first + " " + second, decs);
				} else {
					dec.put(second + " " + first, decs);
				}
				if (route.containsKey(first)) {
					route.get(first).add(second);
				} else {
					ArrayList<Integer> temp = new ArrayList<Integer>();
					temp.add(second);
					route.put(first, temp);
				}
				if (route.containsKey(second)) {
					route.get(second).add(first);
				} else {
					ArrayList<Integer> temp = new ArrayList<Integer>();
					temp.add(first);
					route.put(second, temp);
				}
			}
			while (Q != 0) {
				Q--;
				int start = input.nextInt();
				int end = input.nextInt();
				if (!pathExists(route, start, end)) {
					if (!firstTime) {
						firstTime = true;
					} else {
						System.out.println();
					}
					System.out.print("no path");
				} else {
					int result = findPath(route, dec, start, end);
					if (!firstTime) {
						firstTime = true;
					} else {
						System.out.println();
					}
					if (result == -1) {
						System.out.print("no path");
					} else {
						System.out.print(result);
					}
				}
			}
		}
	}

	private static boolean pathExists(
			HashMap<Integer, ArrayList<Integer>> route, int start, int end) {
		boolean result = false;
		ArrayList<Integer> siblings = new ArrayList<Integer>();
		ArrayList<Integer> q = new ArrayList<Integer>();
		q.add(start);
		while (q.size() != 0) {
			int first = q.get(0);
			q.remove(0);
			ArrayList<Integer> a = route.get(first);
			siblings.add(first);
			for (Integer b : a) {
				if (!siblings.contains(b)) {
					q.add(b);
				}
			}
			if (first == end) {
				return true;
			}
		}
		return false;
	}

	private static int findPath(HashMap<Integer, ArrayList<Integer>> route,
			HashMap<String, Integer> dec, int start, int end) {
		int lowest = -1;
		ArrayList<Integer> visited = new ArrayList<Integer>();
		visited.add(start);
		for (Integer i : route.get(start)) {
			int low = findPathLowest(route, dec, i, end,
 mapTo(dec, start, i),
					(ArrayList<Integer>) visited.clone());
			if (low < lowest || lowest == -1) {
				lowest = low;
			}
		}
		return lowest;
	}

	private static int findPathLowest(
			HashMap<Integer, ArrayList<Integer>> route,
			HashMap<String, Integer> dec, int start, int end, int mapTo,
			ArrayList<Integer> visited) {
		int result = -1;
		if (start == end) {
			return mapTo;
		}
		visited.add(start);
		ArrayList<Integer> clone = (ArrayList<Integer>) visited.clone();
		boolean visiteds = false;
		for (Integer i : route.get(start)) {
			if (!visited.contains(i)) {
				visited.add(i);
				int low = findPathLowest(route, dec, i, end,
						mapTo(dec, i, start),
						(ArrayList<Integer>) clone.clone());
				if (result > low || result == -1) {
					result = low;
				}
				visiteds = true;
			}
		}
		if (!visiteds) {
			return -1;
		}
		return Math.max(mapTo, result);
	}

	private static int mapTo(HashMap<String, Integer> dec, int start,
			Integer i) {
		if (start < i) {
			return dec.get(start + " " + i);
		} else {
			return dec.get(i + " " + start);
		}
	}
}
