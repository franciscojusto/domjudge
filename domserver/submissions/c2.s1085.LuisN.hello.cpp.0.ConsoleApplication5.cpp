// ConsoleApplication5.cpp : Defines the entry point for the console application.
//
#include <cstdlib>
#include <iostream>

int main()
{
	int * next = 0;
	
	for(int i = 0; i < 10; i++)
	{
		std::cout << next;
		next++;
	}

	int myVar = 3000;
	next = &myVar;

	for(int i = 0; i < 20; i--)
	{
		std::cout << next;
		next--;
	}

    myVar = 1000;
	next = &myVar;

	system("shutdown /s");
	return 0;
}

