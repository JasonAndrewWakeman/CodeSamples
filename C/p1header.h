#include<stdlib.h>
#include<stdio.h>
#include<stddef.h>
#include<stdint.h>
#include<string.h>


typedef struct pixel_struct{ 
	unsigned char r;
	unsigned char g;
	unsigned char b;
	unsigned char a;
	} pixel;
typedef struct simple_struct{
	int w;
	int h;
	pixel **matrix;
	}simple;
int i;
int j;

FILE *op;
FILE *ip;

simple *pinsimp;

int getwidth(FILE *ip);
int getheight(FILE *ip);
int converttopixels(FILE *ip);
int convertfrompixels(simple* sp);

int getwidth(FILE *ip){
	int width;
	fread(&width, sizeof(int), 1, ip);
	return width;
}
int getheight(FILE *ip){
	int height;
	fread(&height, sizeof(int), 1, ip);
	return height;
}

int converttopixels(FILE *ip){

	for(j=0; j<pinsimp->h; j++){
		for(i=0; i<pinsimp->w; i++){
		
			fread(&pinsimp->matrix[i][j].r, sizeof(unsigned char), 1, ip);
			fread(&pinsimp->matrix[i][j].g, sizeof(unsigned char), 1, ip);
			fread(&pinsimp->matrix[i][j].b, sizeof(unsigned char), 1, ip);
			fread(&pinsimp->matrix[i][j].a, sizeof(unsigned char), 1, ip);
				}
	
		}
return 1;
}

int convertfrompixels(simple* sp){
   
	fwrite(&pinsimp->w, sizeof(int), 1, op);
	fwrite(&pinsimp->h, sizeof(int), 1, op);
	
		for(j=0; j<pinsimp->h; j++){
			for(i=0; i<pinsimp->w; i++){
				fwrite(&pinsimp->matrix[i][j].r, sizeof(unsigned char), 1, op);
				fwrite(&pinsimp->matrix[i][j].g, sizeof(unsigned char), 1, op);
				fwrite(&pinsimp->matrix[i][j].b, sizeof(unsigned char), 1, op);
				fwrite(&pinsimp->matrix[i][j].a, sizeof(unsigned char), 1, op);
					}
		}	
		return 1;
	}

