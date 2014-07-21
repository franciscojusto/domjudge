#include <iostream>
#include <cstdio>
#include <cmath>
#include <string>

using namespace std;

int N;
int weight[5608], carry[5608];//, best[5608];
bool used[5608];

int search(int n, int carryleft) {
	//cout << "using " << n << endl;
	//if (best[n] != 1) return best[n];
	int bb=1;
	for (int i=0;i<N;i++) {
		//cout << weight[i] << " " << carryleft << " " << used[i] << endl;
		if (weight[i]<=carryleft && !used[i]) {
			used[i]=true;
			//cout << "carry " << i << endl;
			//best[n]=max(best[n],1+search(i,min(carryleft-weight[i], carry[i])));
			bb=max(bb,1+search(i,min(carryleft-weight[i], carry[i])));
			//cout << "out " << i << endl;
			//cout << bb << endl;
			//cout << best[n] << endl;
			used[i]=false;
		}
	}
	//return best[n];
	return bb;
}

int main () {
	//freopen("h.in.txt","r",stdin);
    int a,b,i=0;
	while (cin>>a>>b) {
		weight[i]=a;
		carry[i]=b-a;
		i++;
	}
	N=i;
	for (int i=0;i<N;i++) {
		//best[i]=1;
		used[i]=false;
	}
	int totbb=0;
	for (int i=0;i<N;i++){
		//cout << "--" << endl;
		used[i]=true;
		totbb = max(totbb,search(i,carry[i]));
		used[i]=false;
	}
	/*int good=0;
	for (int i=0;i<N;i++){
		good=max(good,best[i]);
	}
	cout << good;*/
	cout << totbb << endl;
}
