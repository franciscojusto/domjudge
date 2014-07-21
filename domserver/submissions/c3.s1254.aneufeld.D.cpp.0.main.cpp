/*
 * File:   main.cpp
 * Author: AaronN
 *
 * Created on January 22, 2014, 2:34 PM
 */

#include <cstdlib>
#include <iostream>
#include <cmath>
#include <cstdio>

using namespace std;

/*
 *
 */
int main(int argc, char** argv)
{
    const string above = " DEGREE(S) ABOVE NORMAL";
    const string below = " DEGREE(S) BELOW NORMAL";

    int n;

    cin >> n;

    float h, l, nh, nl;
    float d;
    char s[20];
    for (int i = 0; i < n; i++)
    {
        cin >> h >> l >> nh >> nl;
        d = round((((h - nh) + (l - nl)) / 2.0) * 10.0) / 10.0;
        //d = (((h - nh) + (l - nl)) / 2);
        sprintf(s, "%.1f", abs(d));
        cout << s << (d > 0 ? above : below);

        if (i < n - 2) cout << endl;
    }
    return 0;
}

