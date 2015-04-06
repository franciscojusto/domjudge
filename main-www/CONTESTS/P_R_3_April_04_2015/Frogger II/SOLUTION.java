import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.PrintStream;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.PriorityQueue;
import java.util.Scanner;

/*
 * This problem immediately says find the shortest path between two points. Using
 * Dijkstra's algorithm is a clear choice for solving this problem, but the only
 * trouble is calculating the edges between the clouds. Assuming we know when any
 * two clouds are intersecting, it then becomes a move-and-wait type of shortest
 * path. So then the main part is figuring out when two clouds are going to
 * intersect. One major optimization you can make is noticing that clouds will
 * always intersect on the same interval no matter where Frogger is, so pre-
 * calculating those intersections will save loads of time.
 * 
 * Calculating the intersections has some mathematical formula, but it is
 * difficult to work out, so a simple binary search could do the job as well. All
 * you need to do is find the interval along the x-axis two clouds intersect and
 * the interval for when they intersect on the y-axis and merge the two to find
 * when they intersect.
 * 
 * So all that is left is the binary search algorithm itself. Not all clouds move
 * in the same direction, so we need to know when given a certain time, if that
 * time is too low or too high for our consideration. I do two binary searches
 * here, one for the beginning of the intersection interval and one for the end.
 * Always using a moving target as our reference is important, and knowing where
 * one cloud is in relation to another is the only other part. If neither of the
 * clouds are moving, it is extremely simple. Otherwise, the binary search can
 * proceed with the table below (for the beginning of the interval):
 * Relative|Moving|Keep      |
 * --------+------+----------+
 * Left    |Left  |Lower Half|  Too far, throw away later half
 * Left    |Right |Upper Half|  Haven't intersected yet, throw away lower half
 * Overlap |Left  |Lower Half|  Intersecting, but we need the first point, so throw away later half
 * Overlap |Right |Lower Half|  "																  "
 * Right   |Left  |Upper Half|  Haven't intersected yet, throw away lower half
 * Right   |Right |Lower Half|  Too far, throw away later half
 * 
 * Similarly we do the same for the end of the interval, only keeping the upper
 * half during intersections. More detail on this can be found in the implementation.
 */
public class Frogger {
	private static ArrayList<Intersection>[] intersections;
	private static Cloud[] clouds;
	
	private static final double[] MAX_INTERVAL = new double[]{0, 1000};
	
	public static void main(String[] args) throws IOException {
		Scanner br = new Scanner(System.in);
		
		int cases = br.nextInt();

		for(int c = 0; c < cases; c++){
			int numClouds = br.nextInt() + 2;
			clouds = new Cloud[numClouds];
			for(int i = 0; i < clouds.length; i++){
				Vector topLeft = new Vector(br.nextInt(), br.nextInt()),
						topRight = new Vector(br.nextInt(), br.nextInt()),
						bottomRight = new Vector(br.nextInt(), br.nextInt()),
						bottomLeft = new Vector(br.nextInt(), br.nextInt());

				int angle = br.nextInt();
				Vector direction;
				if(angle == 0){
					direction = new Vector(0, 0);
				} else {
					double radians = Math.toRadians(angle);
					
					// Unit vector means a point moves this far (in x and y directions)
					// after a single unit of time
					direction = new Vector(Math.cos(radians), Math.sin(radians));
				}

				clouds[i] = new Cloud(i, topLeft, topRight, bottomRight, bottomLeft, direction);
			}
			
			intersections = new ArrayList[numClouds];

			// Pre-calcuate all intersections before Dijkstra's
			calculateIntersections();
			
			// Get result from Dijkstra's
			double time = calculateTime();
			if(time < 0){
				System.out.println(-1);
			} else {
				System.out.printf("%.4f%n", time);
			}
		}
	}

	public static void calculateIntersections(){
		for(int i = 0; i < clouds.length; i++){
			intersections[i] = new ArrayList<Intersection>();
		}

		for(int i = 0; i < clouds.length; i++){
			for(int j = i + 1; j < clouds.length; j++){
				Intersection inter = clouds[i].getIntersection(clouds[j]);
				if(inter != null){
					// If there is an intersection, then it is bi-directional
					intersections[i].add(inter);
					intersections[j].add(new Intersection(i, inter.timeStart, inter.timeEnd));
				}
			}
		}
	}

	public static double calculateTime(){
		double[] fastest = new double[clouds.length];
		Arrays.fill(fastest, Double.MAX_VALUE);
		int start = clouds.length - 2;
		
		fastest[start] = 0;
		PriorityQueue<Distance> queue = new PriorityQueue<Distance>();
		queue.add(new Distance(start, 0));

		while(!queue.isEmpty()){
			Distance top = queue.poll();

			// If we found a faster way here, don't process this cloud
			if(top.time > fastest[top.index]){
				continue;
			}

			// If we are at the end, we have the fastest possible way to this cloud
			if(top.index == clouds.length - 1){
				return top.time;
			}

			for(Intersection inter : intersections[top.index]){

				// If the intersection has already passed, you can't go to that cloud
				if(inter.timeEnd < top.time){
					continue;
				}

				// Otherwise, you can either get there right now, or as soon as the intersection gets to you
				double nextTime = Math.max(top.time, inter.timeStart);

				// We can get to this cloud faster
				if(fastest[inter.to] > nextTime){
					fastest[inter.to] = nextTime;
					queue.add(new Distance(inter.to, nextTime));
				}
			}
		}

		return -1;
	}

	public static class Cloud{
		public final Vector topLeft, topRight, bottomLeft, bottomRight, direction;
		
		// Minimum/maximum ranges for the point values
		public final double[] xRange, yRange;

		public final int index, horizontalDirection, verticalDirection;

		public Cloud(int index, Vector topLeft, Vector topRight, Vector bottomRight, Vector bottomLeft, Vector dir){
			this.index = index;
			this.topLeft = topLeft;
			this.topRight = topRight;
			this.bottomRight = bottomRight;
			this.bottomLeft = bottomLeft;
			
			xRange = new double[]{ topLeft.x, topRight.x };
			yRange = new double[]{ bottomLeft.y, topLeft.y };
			
			direction = dir;
			
			horizontalDirection = getHorizontalDirection();
			verticalDirection = getVerticalDirection();
		}

		public Intersection getIntersection(Cloud other){
			double[] hInterval = getHorizontalTimeInterval(other);
			double[] vInterval = getVerticalTimeInterval(other);
			
			// If one time frame doesn't have any intersections, then they don't intersect
			// Imagine two clouds, one moving down while the other moves up, but they
			// have a big gap in the middle.
			if(hInterval == null || vInterval == null){
				return null;
			}
			
			// Merge the two intervals
			double[] total = new double[]{ Math.max(hInterval[0], vInterval[0]), Math.min(hInterval[1], vInterval[1]) };
			
			// If this interval starts after it ends, then they do not intersect
			if(total[0] > total[1]){
				return null;
			}
			
			return new Intersection(other.index, total[0], total[1]);
		}
		
		// The main problem with a binary searching approach is that we need to know if we are
		// getting closer to intersecting or not. These methods allow us to quickly determine
		// if a rectangle is getting closer by comparing its current relative position with
		// its current direction:
		// tooFar = Double.compare(other.x, current.x) == horizontalDirection
		//          ^says where we should be going        ^says where we are going
		// We are too far if we are going in the wrong direction, which means we throw away
		// the greater half of our binary search, and we keep searching. Otherwise, we throw
		// away the lesser half.
		/*====================================================================================*/
		
		// Returns -1 if going left, 1 if going right, or 0 if not moving in the horizontal direction (up/down/stationary)
		private int getHorizontalDirection() {
			if(Math.abs(direction.x) < 1e-10) return 0;
			
			return Double.compare(direction.x, 0);
		}

		// Returns -1 if going down, 1 if going up, or 0 if not moving in the vertical direction (left/right/stationary)
		private int getVerticalDirection() {
			if(Math.abs(direction.y) < 1e-10) return 0;
			
			return Double.compare(direction.y, 0);
		}
		
		// Does the actual binary search for the horizontal time interval that the clouds intersect
		public double[] getHorizontalTimeInterval(Cloud other){
			// If neither cloud moves in the horizontal direction, short circuit and do basic testing
			if(horizontalDirection == 0 && other.horizontalDirection == 0){
				return Cloud.getRelativeHorizontalDirection(this, other, 0) == 0 ?
						MAX_INTERVAL :
						null;
			}
			
			// Use a moving cloud to determine which direction the intersection happens
			Cloud moving;
			if(horizontalDirection == 0){
				moving = other;
				other = this;
			} else {
				moving = this;
			}
			
			double low = -1, high = 1005; // Ranges that Frogger can travel, with some tolerance
			int movingDir = moving.horizontalDirection;
			
			// Find first point in time the clouds intersect:
			double first = 2000;
			boolean intersects = false;
			boolean tossHigh = false, tossLow = false;
			
			//Do quick check to see if they ever intersect
			int relativeStart = Cloud.getRelativeHorizontalDirection(moving, other, 0);
			int relativeEnd = Cloud.getRelativeHorizontalDirection(moving, other, 1000);
			
			if(relativeStart != 0 && relativeStart == relativeEnd){
			    return null;
			}
			
			if(relativeStart == 0 && relativeEnd == 0){
				return MAX_INTERVAL;
			}
			
			// Only need 4 decimals, so get it accurate to one more, but if we go higher than 1000, we can stop there too
			while(high - low > 1e-5 && low <= 1000 + 1e-9 && high >= -(1e-9)) {
				double mid = (high + low) / 2;
				int relative = Cloud.getRelativeHorizontalDirection(moving, other, mid);
				
				// This means we have not intersected yet, so throw away lower half
				if(relative != movingDir && relative != 0 && (moving.direction.x * other.direction.x < 0 || Math.abs(moving.direction.x) > Math.abs(other.direction.x))){
					low = mid;
					tossLow = true;
				} else {
					high = mid;
					tossHigh = true;
				}
				
				first = mid;
				
				// Keep track if it intersects at all
				// It intersects if we get an intersection (relative == 0) OR
				// We have gone both too far AND not far enough
				intersects = intersects || relative == 0 || (tossHigh && tossLow);
			}
			
			// If it doesn't intersect during our time range, then it doesn't matter
			if(!intersects){
				return null;
			}
			
			// Otherwise find last point that it intersects
			low = first;
			high = 1005;
			double last = first;
			
			 // Only need 4 decimals, so get it accurate to one more
			while(high - low > 1e-5) {
				double mid = (high + low) / 2;
				int relative = Cloud.getRelativeHorizontalDirection(moving, other, mid);
				
				// This means we have already intersected, so throw away greater half
				if(relative == movingDir){
					high = mid;
				} else {
					low = mid;
				}
				
				last = mid;
			}
			
			return new double[]{convert(first), convert(last)};
		}
		
		// Does the actual binary search for the vertical time interval that the clouds intersect
		public double[] getVerticalTimeInterval(Cloud other){
			// If neither cloud moves in the vertical direction, short circuit and do basic testing
			if(verticalDirection == 0 && other.verticalDirection == 0){
				return getRelativeVerticalDirection(this, other, 0) == 0 ?
						MAX_INTERVAL :
						null;
			}
			
			// Use a moving cloud to determine which direction the intersection happens
			Cloud moving;
			if(verticalDirection == 0){
				moving = other;
				other = this;
			} else {
				moving = this;
			}
			
			double low = -1, high = 1005; // Ranges that Frogger can travel, with some tolerance
			int movingDir = moving.verticalDirection;
			
			// Find first point in time the clouds intersect:
			double first = 2000;
			boolean intersects = false;
			boolean tossHigh = false, tossLow = false;
			
			//Do quick check to see if they ever intersect
			int relativeStart = Cloud.getRelativeVerticalDirection(moving, other, 0);
			int relativeEnd = Cloud.getRelativeVerticalDirection(moving, other, 1000);
			
			if(relativeStart != 0 && relativeStart == relativeEnd){
			    return null;
			}
			
			if(relativeStart == 0 && relativeEnd == 0){
				return MAX_INTERVAL;
			}
			
			// Only need 4 decimals, so get it accurate to one more, but if we go higher than 1000, we can stop there too
			while(high - low > 1e-5 && low <= 1000 + 1e-9 && high >= -(1e-9)) {
				double mid = (high + low) / 2;
				int relative = Cloud.getRelativeVerticalDirection(moving, other, mid);
				
				// This means we have not intersected yet, so throw away lower half
				if(relative != movingDir && relative != 0 && (moving.direction.y * other.direction.y < 0 || Math.abs(moving.direction.y) > Math.abs(other.direction.y))){
					low = mid;
					tossLow = true;
				} else {
					high = mid;
					tossHigh = true;
				}
				
				first = mid;
				
				// Keep track if it intersects at all
				intersects = intersects || relative == 0 || (tossLow && tossHigh);
			}
			
			// If it doesn't intersect during our time range, then it doesn't matter
			if(!intersects){
				return null;
			}
			
			// Otherwise find last point that it intersects
			low = first;
			high = 1000;
			double last = first;
			// Only need 4 decimals, so get it accurate to one more
			while(high - low > 1e-5) {
				double mid = (high + low) / 2;
				int relative = Cloud.getRelativeVerticalDirection(moving, other, mid);
				
				// This means we have already intersected, so throw away greater half
				if(relative == movingDir){
					high = mid;
				} else {
					low = mid;
				}
				
				last = mid;
			}
			
			return new double[]{convert(first), convert(last)};
		}
		
		// Does the actual binary search for the horizontal time interval that the clouds intersect
		public static int getRelativeVerticalDirection(Cloud moving, Cloud other, double time){
			double myDist = moving.direction.scalar(time).y;
			double otherDist = other.direction.scalar(time).y;
			
			double myMin = moving.yRange[0] + myDist;
			double myMax = moving.yRange[1] + myDist;
			
			double otherMin = other.yRange[0] + otherDist;
			double otherMax = other.yRange[1] + otherDist;
			
			// We are currently completely left of the other cloud
			if(myMax < otherMin - 1e-9){
				return -1;
			} else if(myMin > otherMax + 1e-9){ // We are completely right of the other cloud
				return 1;
			} else {
				return 0; // We are intersecting
			}
		}
		
		public static int getRelativeHorizontalDirection(Cloud moving, Cloud other, double time){
			double myDist = moving.direction.scalar(time).x;
			double otherDist = other.direction.scalar(time).x;
			
			double myMin = moving.xRange[0] + myDist;
			double myMax = moving.xRange[1] + myDist;
			
			double otherMin = other.xRange[0] + otherDist;
			double otherMax = other.xRange[1] + otherDist;
			
			// We are currently completely left of the other cloud
			if(myMax < otherMin){
				return -1;
			} else if(myMin > otherMax){ // We are completely right of the other cloud
				return 1;
			} else {
				return 0; // We are intersecting
			}
		}
		
		/*====================================================================================*/
	}

	// Holds points and gives us vector math/geometry
	public static class Vector{
		public final double x, y;

		public Vector(double x, double y){
			this.x = x;
			this.y = y;
		}

		public Vector scalar(double r){
			return new Vector(x * r, y * r);
		}
		
		public String toString(){
			return String.format("<%.4f,%.4f>", x, y);
		}
	}

	// Holds the time interval a cloud intersects the cloud at index "to"
	public static class Intersection{
		public final int to;
		public final double timeStart, timeEnd;

		public Intersection(int to, double ts, double te){
			this.to = to;
			timeStart = ts;
			timeEnd = te;
		}
	}

	public static class Distance implements Comparable<Distance>{
		public final int index;   // Last visited cloud
		public final double time; // Time it took to reach cloud

		public Distance(int index, double time){
			this.index = index;
			this.time = time;
		}

		public int compareTo(Distance other){
			return (int)Math.signum(time - other.time);
		}
	}
	
	// Converts a double to be exactly 6 digits long (after the decimal)
	private static double convert(double x){
		return ((int)(x * 100000)) / 100000.0;
	}
}
