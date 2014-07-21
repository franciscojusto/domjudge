#include <iostream>
#include <sstream>
#include <cstdio>
#include <vector>
#include <cmath>
#include <string>

using namespace std;

int main () {
    int N; cin >> N;
	cin.ignore();
    for (int cc=0;cc<N;cc++) {
    	stringstream ss;
    	string dummy;
    	getline(cin, dummy);
    	ss << dummy;
    	
    	vector<double> arr;
    	double a=0,sum=0;
    	while (ss>>a) {
    		cout << "test";
    		arr.push_back(a);
    		sum+=a;
    	}
    	double avg = sum/(double)arr.size();
    	cout << "average" << avg << endl;
    	double diff = 0;
    	for (int i=0;i<arr.size();i++){
    		diff += arr[i]-avg;
    	}
    	cout << "diff" << diff << endl;
    	//diff /= (double)arr.size();
    	if (diff > 0) {
    		printf("%.1f DEGREE(S) ABOVE NORMAL", abs(diff));
    	} else {
    		printf("%.1f DEGREE(S) BELOW NORMAL", abs(diff));
    	}
    	if (cc<N-1) printf("\n");
    }
}
