#include <iostream>
#include <algorithm>
#include <fstream>
#include <vector>
#include <time.h>

using namespace std;

int lds(vector<int> numbers)
{
	vector<int>lds;
	for (int i=0; i<numbers.size(); i++)	
	{
		int count = lds.size();
		if (count == 0 || lds[count-1] > numbers[i])
		{
			lds.push_back(numbers[i]);
		}
		else {
			vector<int>::iterator itr = std::upper_bound(lds.begin(), lds.end(), numbers[i], [](int a, int b) { return a >= b; });
			if (itr != lds.end()) {
				*itr = numbers[i];
			}
		}
	}

	return lds.size();
}

int main() {	
	int cases;
	ifstream in("play.in");
	in>>cases;

	for (int i=0; i < cases; i++)
	{
		int students;		
		in>>students;
		
		vector<int> numbers;		
		for (int s=0; s<students; s++)
		{
			int n;
			in>>n;
			numbers.push_back(n);
		}
		
		cout <<lds(numbers)<<endl;
	}
	
	return 0;	
}
