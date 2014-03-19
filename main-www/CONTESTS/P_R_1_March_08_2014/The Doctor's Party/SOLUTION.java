import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Scanner;
import java.util.Set;
import java.util.TreeSet;

class DoctorsParty {

	public static void main(String[] args) {
		Scanner in = new Scanner(System.in);
		int parties = in.nextInt();
		
		for(int i = 0; i < parties; i++){
			int guests = in.nextInt();
						
			// we are going to want to have all the numbers sorted
			// to make it easier to map them down to smaller numbers later
			Set<Integer> numbers = new TreeSet<Integer>();
			// we'll store the intervals here..
			List<Interval> intervals = new ArrayList<Interval>();
			for(int j = 0; j < guests; j++){
				int arrival = in.nextInt();
				int departure = arrival + in.nextInt();
				
				numbers.add(arrival);
				numbers.add(departure);
				intervals.add(new Interval(arrival, departure));
			}
			
			// we are going to map each number we got to smaller numbers 
			HashMap<Integer, Integer> mapping = new HashMap<Integer, Integer>();			
			// start at 1 
			int currentIdx = 1;
			for(int n : numbers) {				
				mapping.put(n, currentIdx++);
			}			
						
			 // this line is because we could potentially get 0 guests
			int size = Math.max(1, numbers.size());
			IntervalTree intervalTree = new IntervalTree(size);
			for (Interval interval : intervals) {
				// update the intervals in our tree, each interval has value 1 since it's 1 guest
				intervalTree.update(mapping.get(interval.getStart()), mapping.get(interval.getEnd()), 1);
			}
			
			// the maximum overlapping guests is then computed over the entire interval
			int maxOverlappingGuests = intervalTree.getMax(1, size);
			
			System.out.println(maxOverlappingGuests);
		}
		
		in.close();
	}
}

class Interval {
	int start;
	int end;
	
	public Interval(int start, int end){
		this.start = start;
		this.end = end;
	}
	
	public int getStart(){
		return start;
	}
	
	public int getEnd() {
		return end;
	}
}

class IntervalTree {
	
	// Each interval tree node stores six things: the range it covers (start/end) its own value, its children's values, and its children
	private class IntervalTreeNode
	{
		// The range this interval tree node covers
		private int start, end;
		
		// This node's value, and its children's value
		public int value, children;
		
		// This node's children
		private IntervalTreeNode left, right;
		
		// Build the node by giving it a range to cover
		public IntervalTreeNode(int s, int e)
		{
			start = s;
			end = e;
			value = children = 0; // start everything at 0 (unless you are doing a min-tree, where you'd start with MAX_VALUE)
			
			// If we have children, make them
			if(start != end)
			{
				int mid = (start + end) / 2;
				left = new IntervalTreeNode(start, mid);
				right = new IntervalTreeNode(mid + 1, end);
			}
			// No children, don't bother
			else
			{
				left = right = null;
			}
		}
		
		// Update works like this: if the range encompasses us, then update our own value.
		// Otherwise, if the range is completely out of us (ends before we start or vice versa),
		// then there is nothing to do. Otherwise that means we need to update our children,
		// and we don't need to change parameters because we are doing the encompassing case.
		// Once the children have been updated, update your children value to be whatever the
		// tree needs it to be (the max or sum in this case)
		public void update(int startRange, int endRange, int update)
		{
			// Range encompasses us, modify our own value
			if(startRange <= start && endRange >= end)
			{
				value += update;
			}
			// Range does not intersect us, do nothing
			else if(startRange > end || endRange < start)
			{
				return;
			}
			else
			{
				// Update our children
				left.update(startRange, endRange, update);
				right.update(startRange, endRange, update);
				
				// Max-tree (doctor's party kind)
				children = Math.max(left.value + left.children, right.value + right.children);
			}
		}
		
		// Returns the maximum value within a range
		public int getMax(int startRange, int endRange)
		{
			// If the range encompasses us, return ourselves(number of guests that are within this interval)
			// plus the max of our children
			if(startRange <= start && endRange >= end)
			{
				return value + children;
			}
			// Range does not intersect, return 0
			else if(startRange > end || endRange < start)
			{
				return 0;
			}
			// Return whichever child is greater, since they are independent ranges
			else
			{
				return value + Math.max(left.getMax(startRange, endRange), right.getMax(startRange, endRange));
			}
		}
	}
	
	private IntervalTreeNode root;
	
	// Initialize an interval tree that covers the range of 1-size
	public IntervalTree(int size)
	{
		root = new IntervalTreeNode(1, size);
	}
	
	// Update the tree for the given range by adding the given value
	public void update(int start, int end, int value)
	{
		root.update(start, end, value);
	}
	
	// Grab the maximum value of the tree within the given range
	public int getMax(int start, int end)
	{
		return root.getMax(start, end);
	}
}
