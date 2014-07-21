#include <iostream>
#include <cstdio>
#include <cmath>
#include <string>

using namespace std;
// 1 to 100
int C, S, Q;
bool path[102][102];
int sound[102][102];

int main () {
	freopen("C.in.txt","r",stdin);
	int cc=1;
	while (cin >> C>>S>>Q) {
		if (C == 0 && S == 0 && Q == 0) return 0;
		if (cc>1) cout << endl << endl;
		
		for(int i=0;i<102;i++)
			for (int j=0;j<102;j++) {
				path[i][j] = false;
				sound[i][j] = 1000000;
			}
				
		for (int i=0;i<S;i++) {
			int a,b,c; cin >>a>>b>>c;
			sound[a][b] = sound[b][a] = c;
			path[a][b] = path[b][a] = true;
		}
		for (int i=1;i<=C;i++) 
			for (int j=1;j<=C;j++) 
				for (int k=1;k<=C;k++) 
					if (i != j && j != k)
					if (path[i][k] && path[j][k]) {
						sound[j][i] = sound[i][j] = min(sound[j][i],min(sound[i][j], max(sound[i][k],sound[j][k])));
						path[i][j] = path[j][i] = true;
					}
		/*for (int i=1;i<=C;i++) {
			for (int j=1;j<=C;j++) {
				cout << "i,j" << i << "," << j << " " << path[i][j] << " " << sound[i][j]<< endl;
		}
		}*/
		cout << "Case #" << cc << endl;		
		for (int i=0;i<Q;i++) {
			int a,b; cin>>a>>b;
			if (path[a][b]) {
				cout << sound[a][b]; // << endl;
				if (i!=Q-1) cout << endl;
			}
			else
				cout << "no path" << endl;
		}
		cc++;
	}	
	
}
