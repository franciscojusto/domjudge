#include <iostream>
#include <fstream>
#include <vector>
#include <algorithm>
#include <time.h>

using namespace std;

int main() {
	int n;
	
	ofstream out("play.out");

	while (cin>>n)
	{
		vector<int> vals;

		for (int i=1; i<=n; i++)
		{
			
			vals.push_back(i);
		}

		random_shuffle(vals.begin(), vals.end());

		out<<vals.size()<<endl;

		for (int i=0; i<vals.size(); i++)
		{
			out<<vals[i];
			if (i != vals.size()-1) out<<" ";
		}		
		out<<endl;
	}

	return 0;
}