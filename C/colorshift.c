#ifndef p1header
#define p1header
#include "p1header.h"


int initialize_temppixr(simple* sp);
int initialize_temppixg(simple* sp);
int initialize_temppixb(simple* sp);
int count;
unsigned char* temppix;


int main(int argc, char *argv[]){
	

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
    temppix= (unsigned char*) malloc(sizeof(unsigned char) * pinsimp->w * pinsimp->h);		
	pinsimp= (simple*) realloc( pinsimp, sizeof(simple) * sizeof(unsigned char) * pinsimp->w * pinsimp->h);

	pinsimp->matrix= (pixel**) malloc(pinsimp->w*sizeof(pixel*));
		for(i=0; i<pinsimp->w; i++){
			pinsimp->matrix[i]= (pixel*) malloc(pinsimp->h * sizeof(pixel));
			}
	
	converttopixels(ip);
	
	if(!strcmp(argv[3], "RGB")) { 
		initialize_temppixr(pinsimp);
		count=0;
		for(i=0;i<pinsimp->w;i++){
			for(j=0;j<pinsimp->h;j++){
				pinsimp->matrix[i][j].r =pinsimp->matrix[i][j].b;
				pinsimp->matrix[i][j].b =pinsimp->matrix[i][j].g;
				pinsimp->matrix[i][j].g = temppix[count];
				count++;
			}
		}
	}	
	if(!strcmp(argv[3], "GBR")) { 
		initialize_temppixg(pinsimp);
		count=0;
		for(i=0;i<pinsimp->w;i++){
			for(j=0;j<pinsimp->h;j++){
				pinsimp->matrix[i][j].g =pinsimp->matrix[i][j].r;
				pinsimp->matrix[i][j].r =pinsimp->matrix[i][j].b;
				pinsimp->matrix[i][j].b = temppix[count];
				count++;
			}
		}
	}
	if(!strcmp(argv[3], "BRG")) { 
		initialize_temppixb(pinsimp);
		count=0;
		for(i=0;i<pinsimp->w;i++){
			for(j=0;j<pinsimp->h;j++){
				pinsimp->matrix[i][j].b =pinsimp->matrix[i][j].g;
				pinsimp->matrix[i][j].g =pinsimp->matrix[i][j].r;
				pinsimp->matrix[i][j].r = temppix[count];
				count++;
			}
		}
	}
	if(!strcmp(argv[3], "RBG")) { 
		initialize_temppixr(pinsimp);
		count=0;
		for(i=0;i<pinsimp->w;i++){
			for(j=0;j<pinsimp->h;j++){
				pinsimp->matrix[i][j].r =pinsimp->matrix[i][j].g;
				pinsimp->matrix[i][j].g =pinsimp->matrix[i][j].b;
				pinsimp->matrix[i][j].b = temppix[count];
				count++;
			}
		}
	}
	if(!strcmp(argv[3], "BGR")) { 
		initialize_temppixb(pinsimp);
		count=0;
		for(i=0;i<pinsimp->w;i++){
			for(j=0;j<pinsimp->h;j++){
				pinsimp->matrix[i][j].b =pinsimp->matrix[i][j].r;
				pinsimp->matrix[i][j].r =pinsimp->matrix[i][j].g;
				pinsimp->matrix[i][j].g = temppix[count];
				count++;
			}
		}
	}
	if(!strcmp(argv[3], "GRB")) { 
		initialize_temppixg(pinsimp);
		count=0;
		for(i=0;i<pinsimp->w;i++){
			for(j=0;j<pinsimp->h;j++){
				pinsimp->matrix[i][j].g =pinsimp->matrix[i][j].b;
				pinsimp->matrix[i][j].b =pinsimp->matrix[i][j].r;
				pinsimp->matrix[i][j].r = temppix[count];
				count++;
			}
		}
	}
	if(!strcmp(argv[3], "RG")||!strcmp(argv[3], "GR")) { 
		initialize_temppixr(pinsimp);
		count=0;
		for(i=0;i<pinsimp->w;i++){
			for(j=0;j<pinsimp->h;j++){
				pinsimp->matrix[i][j].r =pinsimp->matrix[i][j].g;
				pinsimp->matrix[i][j].g = temppix[count];
				count++;
			}
		}
	}
	if(!strcmp(argv[3], "RB")||!strcmp(argv[3], "BR")) { 
		initialize_temppixr(pinsimp);
		count=0;
		for(i=0;i<pinsimp->w;i++){
			for(j=0;j<pinsimp->h;j++){
				pinsimp->matrix[i][j].r =pinsimp->matrix[i][j].b;
				pinsimp->matrix[i][j].b = temppix[count];
				count++;
			}
		}
	}
	if(!strcmp(argv[3], "BG")||!strcmp(argv[3], "GB")) { 
		initialize_temppixb(pinsimp);
		count=0;
		for(i=0;i<pinsimp->w;i++){
			for(j=0;j<pinsimp->h;j++){
				pinsimp->matrix[i][j].b =pinsimp->matrix[i][j].g;
				pinsimp->matrix[i][j].g = temppix[count];
				count++;
			}
		}
	}
	
	convertfrompixels(pinsimp);
	free(pinsimp);	
return 0;
	error: printf("there was a problem with one of the arguments and the files did not open or were not processed properly! :(");
return 1;
}
		 int initialize_temppixr(simple* sp){
			count = 0; 
			for(i=0;i<pinsimp->w;i++){
				for(j=0;j<pinsimp->h;j++){
					temppix[count]=pinsimp->matrix[i][j].r;
					count ++; 
				}
			}
		return 0;
}
		int initialize_temppixg(simple* sp){
			count = 0; 
			for(i=0;i<pinsimp->w;i++){
				for(j=0;j<pinsimp->h;j++){
					temppix[count]=pinsimp->matrix[i][j].g;
					count ++; 
				}
			}
		return 0;
}
		int initialize_temppixb(simple* sp){
			count = 0; 
			for(i=0;i<pinsimp->w;i++){
				for(j=0;j<pinsimp->h;j++){
					temppix[count]=pinsimp->matrix[i][j].b;
					count ++; 
				}
			}
		
		return 0;
}
#endif
