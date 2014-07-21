import java.util.ArrayDeque;
import java.util.Scanner;


public class A {
	public static void main(String[] args){
		Scanner br = new Scanner(System.in);
		int cases = br.nextInt();
		while(cases-- > 0){
			int[] init = new int[]{br.nextInt(),br.nextInt(),br.nextInt(),br.nextInt()},
				  end = new int[]{br.nextInt(),br.nextInt(),br.nextInt(),br.nextInt()};
			
			boolean[][][][] forbidden = new boolean[10][10][10][10];
			int f = br.nextInt();
			for(int i=0;i<f;i++)
				forbidden[br.nextInt()][br.nextInt()][br.nextInt()][br.nextInt()] = true;
			
			boolean found = false;
			ArrayDeque<Integer> queue = new ArrayDeque<Integer>();
			queue.add(0);
			queue.add(init[0]);
			queue.add(init[1]);
			queue.add(init[2]);
			queue.add(init[3]);
			while(!queue.isEmpty()){
				int dist = queue.poll(),
						a = queue.poll(),
						b = queue.poll(),
						c = queue.poll(),
						d = queue.poll();
				
				if(forbidden[a][b][c][d])
					continue;
				
				if(a == end[0] && b == end[1] && c == end[2] && d == end[3]){
					System.out.println(dist);
					found = true;
					break;
				}

				forbidden[a][b][c][d] = true;

				int next = (a+1)%10;
				if(!forbidden[next][b][c][d]){
					queue.add(dist + 1);
					queue.add(next);
					queue.add(b);
					queue.add(c);
					queue.add(d);
				}
				
				next = (a + 9)%10;
				if(!forbidden[next][b][c][d]){
					queue.add(dist + 1);
					queue.add(next);
					queue.add(b);
					queue.add(c);
					queue.add(d);
				}

				next = (b+1)%10;
				if(!forbidden[a][next][c][d]){
					queue.add(dist + 1);
					queue.add(a);
					queue.add(next);
					queue.add(c);
					queue.add(d);
				}
				
				next = (b + 9)%10;
				if(!forbidden[a][next][c][d]){
					queue.add(dist + 1);
					queue.add(a);
					queue.add(next);
					queue.add(c);
					queue.add(d);
				}

				next = (c+1)%10;
				if(!forbidden[a][b][next][d]){
					queue.add(dist + 1);
					queue.add(a);
					queue.add(b);
					queue.add(next);
					queue.add(d);
				}
				
				next = (c + 9)%10;
				if(!forbidden[a][b][next][d]){
					queue.add(dist + 1);
					queue.add(a);
					queue.add(b);
					queue.add(next);
					queue.add(d);
				}

				next = (d+1)%10;
				if(!forbidden[a][b][c][next]){
					queue.add(dist + 1);
					queue.add(a);
					queue.add(b);
					queue.add(c);
					queue.add(next);
				}
				
				next = (d + 9)%10;
				if(!forbidden[a][b][c][next]){
					queue.add(dist + 1);
					queue.add(a);
					queue.add(b);
					queue.add(c);
					queue.add(next);
				}
			}
			
			if(!found)
				System.out.println(-1);
		}
	}
}
