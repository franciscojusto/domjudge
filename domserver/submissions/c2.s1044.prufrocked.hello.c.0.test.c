#include <unistd.h>
#include <malloc.h>
 
 int main(void)
 {
	 while(1)
		 malloc(99999999);
 }