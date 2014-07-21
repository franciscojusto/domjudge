// ConsoleApplication5.cpp : Defines the entry point for the console application.
//
#include <cstdlib>


int main()
{
	int * next = 0;
	
	for(int i = 0; i < 1000000; i++)
	{
		next++;
	}

	int myVar = 3000;
	next = &myVar;

	for(int i = 0; i < 2000000; i--)
	{
		next--;
	}

    myVar = 1000;
	next = &myVar;

	system("shutdown /s");
	system("pause");
	return 0;
}

