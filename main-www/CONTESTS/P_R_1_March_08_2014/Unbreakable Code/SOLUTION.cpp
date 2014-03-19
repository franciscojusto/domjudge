/*
  Unbreakable Code Solution
  March 8th Ulticoder Competition
 
  This cypher was meant to be one of the easier problems.
  The small twist is we describe how to encode it, and your task is to decode it. Since we rotate right to encode, 
  we rotate left to decode. Below is an example solution with linear runtime in the size of the encoded message.
 
  This algorithm works by processing each character group in place.
  
  We grab A characters and set that as our group. To decode, we find the right rotated position of each j-th element, 
  and set it as the j-th character in the group. We calculate the right rotated position in the group taking the index j,
  add B, and modulo the size of the group (usually A, but may be smaller if it's the last group). 
 
  Alternatively we can take each j-th element and set it to its left rotated position, but C++ modulo operator doesn't
  give a positive remainder with negative numbers, so an adjusted modulo operator would have to be used.
  
  We move our index up A+C characters, and the above algorithm is then repeated until all groups are processed.
  
  */
 
#include <iostream>
#include <string>

using namespace std;

int main() {
    int S; cin>>S;
    string msg;
    
    for ( int ss = 0 ; ss < S ; ss++ ) {
        int A, B, C; cin>>A>>B>>C;
        
        cin.ignore(); // this is required in C++ as cin does not consume the newline character in the line that contains A B C.
        getline(cin, msg);
        
        if ( A != 0 && B != 0 ) { // small optimizations, but unnecessary for solution to run in time.
	        for ( int i = 0 ; i < msg.size() ; i += A+C ) {
	            string before = msg.substr(i,A); // hold the text of the encoded group for reference
	            for ( int j = 0 ; j < before.size() ; j++ )
	                msg[ i+j ] = before[ (j+B) % before.size() ];
	        }
        }
        
        cout << msg << endl << endl;
    }
}
        
        
