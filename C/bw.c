#ifndef p1header
#define p1header
#include "p1header.h" 

int main(int argc, char *argv[]){
	
	unsigned char ave;
	
    ip= fopen(argv[1], "rb");
    if(!ip){ goto error;
      }
	op= fopen(argv[2], "wb");
 	if(!op){ goto error;
      }
	pinsimp= (simple*) malloc(sizeof(simple));
	pinsimp->w=getwidth(ip);
	pinsimp->h=getheight(ip);	
	if(pinsimp->w==0 || pinsimp->h==0){
		goto error;
	}
	pinsimp= (simple*) realloc(pinsimp, sizeof(simple) * sizeof(unsigned char) * pinsimp->w * pinsimp->h);
	pinsimp->matrix= (pixel**) malloc(pinsimp->w*sizeof(pixel*));
		for(i=0; i<pinsimp->w; i++){
			pinsimp->matrix[i]= (pixel*) malloc(pinsimp->h * sizeof(pixel));
			}
			
	converttopixels(ip);

		for(j=0;j<pinsimp->h;j++){
			for(i=0;i<pinsimp->w;i++){

			ave= (pinsimp->matrix[i][j].r + pinsimp->matrix[i][j].g + pinsimp->matrix[i][j].b) / 3;
				pinsimp->matrix[i][j].r = ave;
 				pinsimp->matrix[i][j].g = ave;
				pinsimp->matrix[i][j].b = ave;
				pinsimp->matrix[i][j].a = pinsimp->matrix[i][j].a;
			}
		} 

	convertfrompixels(pinsimp);
	
	free(pinsimp);
	
	return 0; 
		error: printf("there was a problem with one of the arguments and the files did not open or were not processed properly! :(");
	return 1;

		
	}

#endif