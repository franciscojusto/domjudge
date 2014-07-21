#include "stdio.h"
#include "stdlib.h"
int main() {
	int *arr = malloc(128*1024*1024*sizeof(int));
	for (long long i=0;i<128*1024*1024;i++) // 134217728
		arr[i]=1;
	arr[5]=6;
	printf("arr[5]=%d; arr[10]=%d\n",arr[5],arr[10]);
	return 0;
	}