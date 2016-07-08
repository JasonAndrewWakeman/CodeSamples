#ifndef p1header
#define p1header
#include "p1header.h"

simple* poutsimp;
int convertfromoutsimp(simple* sp);
	
int main(int argc, char *argv[]){

	int xcoord;
	int ycoord; 
	xcoord= atoi(argv[3]);
	ycoord= atoi(argv[4]);
    ip= fopen(argv[1], "rb");
      if(!ip){ goto error;
      }
	op= fopen(argv[2], "wb");
      if(!op){ goto error;
      }
    pinsimp= (simple*) malloc(sizeof(simple));
   	poutsimp= (simple*) malloc(sizeof(simple));

	pinsimp->w=getwidth(ip);
	pinsimp->h=getheight(ip);	
	poutsimp->w = atoi(argv[5]);
	poutsimp->h = atoi(argv[6]);	
	if(xcoord+poutsimp->w>pinsimp->w||ycoord+poutsimp->h>pinsimp->h){
		goto error; }
	pinsimp= (simple*) realloc( pinsimp, sizeof(simple) * sizeof(unsigned char) * pinsimp->w * pinsimp->h);
	poutsimp= (simple*) realloc(poutsimp, sizeof(simple) * sizeof(unsigned char) * poutsimp->w * poutsimp->w);

	pinsimp->matrix= (pixel**) malloc(pinsimp->w*sizeof(pixel*));
		for(i=0; i<pinsimp->w; i++){
			pinsimp->matrix[i]= (pixel*) malloc(pinsimp->h * sizeof(pixel));
			}
	poutsimp->matrix= (pixel**) malloc(poutsimp->w*sizeof(pixel*));
		for(i=0; i<poutsimp->w; i++){
			poutsimp->matrix[i]= (pixel*) malloc(poutsimp->h * sizeof(pixel));
			}	
		
	converttopixels(ip);	
	
		for(j=0;j<poutsimp->h;j++){
			for(i=0;i<poutsimp->w;i++){
				poutsimp->matrix[i][j]=pinsimp->matrix[i+xcoord][j+ycoord];
			}
		}
	convertfromoutsimp(poutsimp);
	free(pinsimp);
	free(poutsimp);
		
		
	return 0; 
		error: printf("there was a problem opening the file or with the dimensions used as arguments, the process was not completed as intended! :(");
}
int convertfromoutsimp(simple* sp){
   
	fwrite(&poutsimp->w, sizeof(int), 1, op);
	fwrite(&poutsimp->h, sizeof(int), 1, op);
		for(j=0; j<sp->h; j++){
			for(i=0; i<sp->w; i++){
				fwrite(&poutsimp->matrix[i][j].r, sizeof(unsigned char), 1, op);
				fwrite(&poutsimp->matrix[i][j].g, sizeof(unsigned char), 1, op);
				fwrite(&poutsimp->matrix[i][j].b, sizeof(unsigned char), 1, op);
				fwrite(&poutsimp->matrix[i][j].a, sizeof(unsigned char), 1, op);
			}
		}	
		return 1;
	}
#endif
