#include <iostream>
#include <string>
using namespace std;

int main() {
    int T; cin>>T;
    string text;
    for (int ss=0;ss<T;ss++) {
        int A,B,C; cin>>A>>B>>C;
        cin.ignore();
        getline(cin, text);
        
        for (int i=0;i<text.size();i+=A+C) {
            string before = text.substr(i,A);
            for (int j=0;j<before.size();j++)
                text[ i + (j+B) % before.size() ] = before[ j ];
        }
        cout << text << endl << endl;
    }
}
        
        
