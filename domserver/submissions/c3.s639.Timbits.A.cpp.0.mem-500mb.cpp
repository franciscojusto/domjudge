#include <iostream>
#include <vector>

using namespace std;

int main() {
    int *test = new int[500 * 1024 * 1024 / sizeof(int)];
    for (int i=1;i<100;i++)
        test[i]=i*i;
}
    
