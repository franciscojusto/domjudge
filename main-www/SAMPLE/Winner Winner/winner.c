#include <stdio.h>

int main(){
	int games, contestants, max, current, game, i;
	
	// Using scanf is a very simple way to read in numbers or words at a time.
	scanf("%d", &games);
	for(game = 0; game < games; game++){
		scanf("%d", &contestants);
		
		max = 0;
		for(i = 0; i < contestants; i++){
			scanf("%d", &current);
			if(current > max){
				max = current;
			}
		}
		
		// All output should go through printf
		printf("The winning score is... %d points!\n", max);
	}
	
	return 0;
}