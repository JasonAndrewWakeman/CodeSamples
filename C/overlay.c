#ifndef p1header
#define p1header
#include "p1header.h"


FILE *ip2;
simple *pinsimp2;
int convertsecondtopixels(simple* sp2, FILE *ip2);
int getsecondwidth(FILE *ip);
int getsecondheight(FILE *ip);
int main(int argc, char *argv[]){
	
	int width1;
	int width2;
	int height1;
	int height2;
	int xcoord;
	int ycoord;
	xcoord= atoi(argv[4]);
	ycoord= atoi(argv[5]);

    ip= fopen(argv[1], "rb");
    	if(!ip) {goto error;
    } 
    ip2= fopen(argv[2], "rb");
		if(!ip2) {goto error;
    } 
	op= fopen(argv[3], "wb");
        if(!op){goto error;
    }
	width1 = getwidth(ip);
	height1 = getheight(ip);
	width2 = getsecondwidth(ip2);
	height2 = getsecondheight(ip2);
	
	pinsimp= (simple*) malloc(sizeof(simple) * sizeof(unsigned char) * width1 * height1);
	pinsimp2= (simple*) malloc(sizeof(simple) * sizeof(unsigned char) * width2 * height2);
	
	if(width1<width2 || height1 < height2)  { goto error;
		}
	pinsimp->w=width1;
	pinsimp->h=height1;
	pinsimp2->w=width2;
	pinsimp2->h=height2;
	
	pinsimp->matrix= (pixel**) malloc(pinsimp->w*sizeof(pixel*));
		for(i=0; i<pinsimp->w; i++){
			pinsimp->matrix[i]= (pixel*) malloc(pinsimp->h * sizeof(pixel));
		}
	pinsimp2->matrix= (pixel**) malloc(pinsimp2->w*sizeof(pixel*));
		for(i=0; i<pinsimp2->w; i++){
			pinsimp2->matrix[i]= (pixel*) malloc(pinsimp2->h * sizeof(pixel));
		}
	
	converttopixels(ip);	
	convertsecondtopixels(pinsimp2, ip2);
		if(width2 + xcoord >width1 || height2 + ycoord > height1){goto error;
	}

		for(j=0;j<pinsimp2->h;j++){
			for(i=0;i<pinsimp2->w;i++){
				if(pinsimp2->matrix[i][j].a == 255) {
					pinsimp->matrix[i+xcoord][j+ycoord]=pinsimp2->matrix[i][j];
				}
				else{
					unsigned char rr;
					unsigned char rg;
					unsigned char rb;
					unsigned char ra;
					float quotient1;
					float prod1;
					float prod2;
					float quot2;
					float prod3;
					float a1=pinsimp->matrix[i+xcoord][j+ycoord].a;
					float b1=pinsimp->matrix[i+xcoord][j+ycoord].b;
					float r1=pinsimp->matrix[i+xcoord][j+ycoord].r;
					float g1=pinsimp->matrix[i+xcoord][j+ycoord].g;
					float a2=pinsimp2->matrix[i][j].a;
					float b2=pinsimp2->matrix[i][j].b;
					float g2=pinsimp2->matrix[i][j].g;
					float r2=pinsimp2->matrix[i][j].r;

					quotient1= (a2/255);
					prod1=quotient1*(r2);
					prod2=a1*(255-a2);
					quot2=prod2/(255*255);
					prod3=quot2*r1;
					rr= prod1+prod3;

					prod1=quotient1*g2;
					prod3=quot2*g1;
					rg= prod1+prod3;

					prod1=quotient1*b2;
					prod3=quot2*b1;
					rb= prod1+prod3;

					ra=255*((a2/255) + ((a1*(255-a2))/(255*255)));

					pinsimp->matrix[i+xcoord][j+ycoord].a=ra;
					pinsimp->matrix[i+xcoord][j+ycoord].r=rr;
					pinsimp->matrix[i+xcoord][j+ycoord].g=rg;
					pinsimp->matrix[i+xcoord][j+ycoord].b=rb;
				}
			}
		}

		convertfrompixels(pinsimp);
		free(pinsimp);
		free(pinsimp2);
		
	return 0; 
		error: printf("there was a problem opening one of the file arguments or the dimensions or vertex were outside of range and the files failed to process properly! :(");
	return 1;
	}

	int convertsecondtopixels(simple *sp2, FILE *ip2){

	for(j=0; j<pinsimp2->h; j++){
		for(i=0; i<pinsimp2->w; i++){
		
			fread(&pinsimp2->matrix[i][j].r, sizeof(unsigned char), 1, ip2);
				
			fread(&pinsimp2->matrix[i][j].g, sizeof(unsigned char), 1, ip2);
			fread(&pinsimp2->matrix[i][j].b, sizeof(unsigned char), 1, ip2);
			fread(&pinsimp2->matrix[i][j].a, sizeof(unsigned char), 1, ip2);
				}
	
		}	
return 1;
}
int getsecondwidth(FILE *ip2){
	int width;
	fread(&width, sizeof(int), 1, ip2);
	return width;
}
int getsecondheight(FILE *ip2){
	int height;
	fread(&height, sizeof(int), 1, ip2);
	return height;
}
#endif
