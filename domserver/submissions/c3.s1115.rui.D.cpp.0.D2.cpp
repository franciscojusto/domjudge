#include <iostream>
#include <cstdio>
#include <cmath>
#include <string>

using namespace std;

int main () {
    int N; cin >> N;
    for (int cc=0;cc<N;cc++) {
    	double a,b,c,d; cin >>a>>b>>c>>d;
    	double diff = ((a-c)+(b-d))/2;
    	
    	if (diff > 0) {
    		printf("%.1f DEGREE(S) ABOVE NORMAL", abs(diff));
    	} else {
    		printf("%.1f DEGREE(S) BELOW NORMAL", abs(diff));
    	}
    	if (cc<N-1) printf("\n");
    }
}
