/*
 The essence of the problem is to find the longest decreasing subsequence. A brute force solution will not work
 because of the large amount of numbers possible. 
 One of the possible solutions is to loop through each number and always maintain the biggest number possible so far for
 a group of each size.

 Algorithm:
 Let groups = []					-- groups[i] will contain the biggest number in a group of length i
 For each number n
	if n < last value of groups
		append n to groups			-- New group length since we can form the group using everyone from the last group + the new student. 
	else							   The number of the new student becomes the smallest number of a group of the new size.
		find first position p where group[p] < n		-- Since the values will be sorted, a binary search can be performed
			replace groups[p] with n					-- This would be a more optimal solution since the group remains the same size but more numbers will be allowed
 return size of groups									

 The running time for the algorithm will be n log n.

 Example for arrangement after each loop pass: 2 4 3 5 1
 [2]
 [4]
 [4, 3]
 [5, 3]
 [5, 3, 1]
 */



#include <iostream>
#include <algorithm>
#include <vector>

using namespace std;

bool compare(int a, int b)
{
    return a >= b;
}

int findLongestSize(vector<int> numbers)
{
	vector<int> groups;

	// Loop through all the numbers
	for (int i=0; i < numbers.size(); i++)	
	{
		int count = groups.size();
		if (count == 0 || groups[count-1] > numbers[i])
		{
			// New group can be formed using last group + adding the student at position i
			groups.push_back(numbers[i]);
		}
		else {
			// Perform binary search 
			vector<int>::iterator positionIterator = std::upper_bound(groups.begin(), groups.end(), numbers[i], compare);
			if (positionIterator != groups.end()) {
				// Replace number in group
				*positionIterator = numbers[i];
			}
		}
	}

	// Return size of largest group
	return groups.size(); 
}

int main() {	
	int cases;
	cin>>cases;

	// Loop through all the cases
	for (int i=0; i < cases; i++)
	{
		int students;		
		cin>>students;
		
		vector<int> numbers;		
		
		for (int s = 0; s < students; s++)
		{
			// Read the number for each student
			int n;
			cin >> n;
			numbers.push_back(n);
		}
		
		// For each case print out the largest size
		cout << findLongestSize(numbers) << endl;
	}
		
	return 0;	
}
