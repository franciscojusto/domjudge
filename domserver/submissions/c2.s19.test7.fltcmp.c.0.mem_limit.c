#include "stdio.h"
#include "stdlib.h"
int main() {
	int *arr = malloc(131072*1024*10);
	arr[5]=5;
	printf("hi\n");
	return 0;
	}