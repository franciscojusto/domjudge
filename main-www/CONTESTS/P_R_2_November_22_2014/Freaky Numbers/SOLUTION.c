//Freaky Numbers UltiCoder C code

/*
(x^2 +y^2)-(x^2-y^2)=DSDS

 x = sqrt(DSDS / 2)
 And y = x + 1.
 
  */
#include <stdio.h>
#include <stdlib.h>
#include <stdint.h>
#include <math.h>
#define BUFSIZE 15

int main ()
{
	int64_t dsds, x;
	int i,cases;
	size_t len = 0;

	//Scans for the first item in the file
	//First item in the file is the number of test cases
	if (fscanf ( stdin, "%lld", &cases ) != EOF) 
	{		
		//Increments through each test case
		for (i = 0; i < cases ; ++i)
		{
			//reads a line and stores the integer into dsds
			if (fscanf ( stdin, "%lld", &dsds ) != EOF) {
						
				//x becomes dsds/2	
				x= dsds/2;
				//x becomes the square root of the previous definition of x
				x = sqrt(x);
				//Prints out the result. Y=x+1
				printf("The DSDS roots of %lld are %lld and %lld \n", (long long)dsds, (long long)x, (long long)x+1);
			}
		}
	}
}