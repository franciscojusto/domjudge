#include <cstdlib>
#include <iostream>
#include <cmath>

using namespace std;

void readAllFourTemps(float& todaysHigh, float& todaysLow, float& normalHigh, float& normalLow);

int main(int argc, char *argv[])
{
    int linesToBeRead = 0;
    float todaysHigh = 0.0;
    float todaysLow = 0.0;
    float normalHigh = 0.0;
    float normalLow= 0.0;
    
    cin >> linesToBeRead;
    
    for(int i = 0; i < linesToBeRead; i++)
    {
        readAllFourTemps(todaysHigh, todaysLow, normalHigh, normalLow);
        
        float avgDiff = (((todaysHigh - normalHigh)+(todaysLow - normalLow))/2);
        float roundedDiff = floor(avgDiff * 10) / 10;
        string aboveOrBelowString = "";
        
        aboveOrBelowString = (roundedDiff > 0 ? "ABOVE NORMAL" : "BELOW NORMAL");
        
        if(i==linesToBeRead-1)
        printf("%.1f DEGREE(S) %s", abs(roundedDiff),aboveOrBelowString.c_str());
        else
        printf("%.1f DEGREE(S) %s%c", abs(roundedDiff),aboveOrBelowString.c_str(), '\n');
    }   
    return EXIT_SUCCESS;
}

void readAllFourTemps(float& todaysHigh, float& todaysLow, float& normalHigh, float& normalLow)
{
     cin >> todaysHigh;
     cin >> todaysLow;
     cin >> normalHigh;
     cin >> normalLow;
}
