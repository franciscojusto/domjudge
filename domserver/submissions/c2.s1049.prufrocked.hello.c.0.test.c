#include <unistd.h>
#include <malloc.h>
 
 int main(void)
 {
	 while(1)
		 char *str = (char *)malloc(99999999 * sizeof(char));
 }