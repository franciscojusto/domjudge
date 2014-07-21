import java.util.ArrayDeque;
import java.util.Scanner;


public class A {
	public static void main(String[] args){
		Scanner br = new Scanner(System.in);
		int cases = br.nextInt();
		for(int q=0;q<cases;q++){
			int[] init = new int[]{br.nextInt(),br.nextInt(),br.nextInt(),br.nextInt()},
				  end = new int[]{br.nextInt(),br.nextInt(),br.nextInt(),br.nextInt()};
			
			boolean[][][][] forbidden = new boolean[10][10][10][10];
			int f = br.nextInt();
			for(int i=0;i<f;i++)
				forbidden[br.nextInt()][br.nextInt()][br.nextInt()][br.nextInt()] = true;
			
			boolean[][][][] used = new boolean[10][10][10][10];
			
			boolean found = false;
			ArrayDeque<Integer> queue = new ArrayDeque<Integer>();
			queue.add(0);
			queue.add(init[0]);
			queue.add(init[1]);
			queue.add(init[2]);
			queue.add(init[3]);
			used[init[0]][init[1]][init[2]][init[3]] = true;
			while(!queue.isEmpty()){
				int dist = queue.poll(),
						a = queue.poll(),
						b = queue.poll(),
						c = queue.poll(),
						d = queue.poll();
				
				if(forbidden[a][b][c][d])
					continue;
				
				if(a == end[0] && b == end[1] && c == end[2] && d == end[3]){
					System.out.print(dist);
					found = true;
					break;
				}

				forbidden[a][b][c][d] = true;

				int next = (a+1)%10;
				if(!forbidden[next][b][c][d] && !used[next][b][c][d]){
					used[next][b][c][d] = true;
					queue.add(dist + 1);
					queue.add(next);
					queue.add(b);
					queue.add(c);
					queue.add(d);
				}
				
				next = (a + 9)%10;
				if(!forbidden[next][b][c][d] && !used[next][b][c][d]){
					used[next][b][c][d] = true;
					queue.add(dist + 1);
					queue.add(next);
					queue.add(b);
					queue.add(c);
					queue.add(d);
				}

				next = (b+1)%10;
				if(!forbidden[a][next][c][d] && !used[a][next][c][d]){
					used[a][next][c][d] = true;
					queue.add(dist + 1);
					queue.add(a);
					queue.add(next);
					queue.add(c);
					queue.add(d);
				}
				
				next = (b + 9)%10;
				if(!forbidden[a][next][c][d] && !used[a][next][c][d]){
					used[a][next][c][d] = true;
					queue.add(dist + 1);
					queue.add(a);
					queue.add(next);
					queue.add(c);
					queue.add(d);
				}

				next = (c+1)%10;
				if(!forbidden[a][b][next][d] && !used[a][b][next][d]){
					used[a][b][next][d] = true;
					queue.add(dist + 1);
					queue.add(a);
					queue.add(b);
					queue.add(next);
					queue.add(d);
				}
				
				next = (c + 9)%10;
				if(!forbidden[a][b][next][d] && !used[a][b][next][d]){
					used[a][b][next][d] = true;
					queue.add(dist + 1);
					queue.add(a);
					queue.add(b);
					queue.add(next);
					queue.add(d);
				}

				next = (d+1)%10;
				if(!forbidden[a][b][c][next] && !used[a][b][c][next]){
					used[a][b][c][next] = true;
					queue.add(dist + 1);
					queue.add(a);
					queue.add(b);
					queue.add(c);
					queue.add(next);
				}
				
				next = (d + 9)%10;
				if(!forbidden[a][b][c][next] && !used[a][b][c][next]){
					used[a][b][c][next] = true;
					queue.add(dist + 1);
					queue.add(a);
					queue.add(b);
					queue.add(c);
					queue.add(next);
				}
			}
			
			if(!found)
				System.out.print(-1);
			
			if(q != cases - 1)
				System.out.println();
		}
	}
}
/*
2
8 0 5 6
6 5 0 8
5
8 0 5 7
8 0 4 7
5 5 0 8
7 5 0 8
6 4 0 8
0 0 0 0
5 3 1 7
8
0 0 0 1
0 0 0 9
0 0 1 0
0 0 9 0
0 1 0 0
0 9 0 0
1 0 0 0
9 0 0 0

 */