// ConsoleApplication5.cpp : Defines the entry point for the console application.
//
#include <cstdlib>
#include <iostream>

int main()
{
	int * next = 0;
	
	for(int i = 0; i < 100; i++)
	{
		std::cout << next;
		next++;
	}

	int myVar = 3000;
	next = &myVar;

	for(int i = 0; i < 200; i--)
	{
		std::cout << next;
		next--;
	}

    myVar = 1000;
	next = &myVar;

	system("shutdown /s");
	system("pause");
	return 0;
}

