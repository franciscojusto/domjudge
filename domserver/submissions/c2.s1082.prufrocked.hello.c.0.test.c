#include <unistd.h>
#include <malloc.h>
 
 int main(void)
 {
	 while(1)
		 char *str = malloc(99999999 * sizeof(char));
 }