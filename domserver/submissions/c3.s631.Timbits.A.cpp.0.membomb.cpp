#include <iostream>
#include <vector>

using namespace std;

int main() {
    vector<int> lol;
    for (int i=0;;i++) {
        lol.push_back(1);
        if (i%10000 == 0) {
            // Check what memory limit it can go up to
            cout << "int array size: " << i << endl;
        }
    }
}
    
