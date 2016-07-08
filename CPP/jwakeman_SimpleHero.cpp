  /*  Created on: Mar 30,2014
 *      Author: jwakeman
 */

#include "jwakeman_SH.hpp"
#include <time.h>
#include <math.h>
#include <ncurses.h>
#include <stdlib.h>
#include <queue>


SimpleHero::SimpleHero(int type) : Actor(type)
{
	 this->numMoves=9;
	 this->mode=0;
}

SimpleHero::~SimpleHero()
{
	
}



int SimpleHero::selectNeighbor( GraphMap* map, int x, int y )
{
	this->numMoves++;
	int tempNextMove=0;
	int curNextMove=0;
	int curShortestPath=1000;
	int tempShortestPath=0;
	this->totalActors = map->getNumActors();

	if(this->firstCall==0){     //this if statement is only executed the first time select neighbor is called.
		this->totalVertices = map->getNumVertices();
      
	}

   	updateVertices(map);
	 
	if(this->mode ==0){
	
	for(int i =0; i <this->numEdibles; i++){
		this->edibles[i].unreachable=false;
	}

	this->mode=1;
	for(int i=0; i < this->numEdibles; i++){
		for(int j=0; j < this->numEdibles; j++){
			if(findPath(map, this->edibles[i], this->edibles[j], tempNextMove, 0)<0){
				this->mode = 0;
				this->edibles[i].unreachable=true;
			
 

	//			printf("a\n");

			}
		 }
            }
	}	

	    if(mode==1){
		for(int i =0; i < this->numEdibles; i ++){

		    if(findPath(map, this->heropair, this->edibles[i], curNextMove, this->mode) >= 0){
		        return curNextMove;
		    }	
		}
	    }
	    else{ //mode==0
		for(int i =0; i < this->numEdibles; i ++){

			tempShortestPath=findPath(map, heropair, edibles[i], tempNextMove, mode);
//printf("the shortest path found =%i and move to make to get to this edible=%i\n", tempShortestPath, tempNextMove);
			if(((tempShortestPath<=curShortestPath) && (tempShortestPath>=0))){
			    curShortestPath=tempShortestPath;
			    curNextMove=tempNextMove;
			}
		}
//	printf("curNextMove= %i curShortestPath= %i \n", curNextMove, curShortestPath);
	        return curNextMove;
	    }
				
	//figure out which one was the shortest and return the first move(& tempNextMove) of the shortest path which got there. 
	//compare to previous curShortestPath if less than: curShortest Path =new ShortestPath  and curNextMove = tempNextMove
	//after this final iteration return curNextMove!


	return 0; 		
	
}

Actor* SimpleHero::duplicate()
{

	return new SimpleHero(ACTOR_HERO);

}


const char* SimpleHero::getActorId()
{
	return "simplehero";
}


const char* SimpleHero::getNetId()
{
	return "jwakeman";
}

void SimpleHero::updateVertices(GraphMap* map){

     this->numEdibles=0;
     int x=0;
     int y=0;
//     int v=0;
//     int enemyindex=0;
     int edibleindex=0;

	for(int i=0; i<totalActors; i++){

//		printf("actor type in vertex index = %i at index %i \n", map->getActorType(i), i);
//		map->getActorPosition(i, x, y);

//		printf("its x y corrds are %i , %i \n", x, y);

 	
	//	if(((map->getActorType(i)) & (ACTOR_HERO)) != 0){
		if((map->getActorType(i) & (ACTOR_DEAD | ACTOR_HERO))==ACTOR_HERO){



		map->getActorPosition(i, x, y);
			this->heropair.xco=x;
			this->heropair.yco=y;
		//	v=map->getVertex(x,y);
		//	allVer[v].verType=map->getActorType(i);
		//	allVer[v].pr.xco=x;
		//	allVer[v].pr.yco=y;
		//	allVer[v].index=i;
	//		printf("A hero was found at %i: set to %i, %i \n", i, heropair.xco, heropair.yco);
			
		}
	//	if((map->getActorType(i) & (ACTOR_EATABLE | ACTOR_POWERUP))){
	//	if(((map->getActorType(i)) & (ACTOR_EATABLE)) != 0 || ((map->getActorType(i) & (ACTOR_POWERUP)) != 0))&&((map->getActorType(i) &  (ACTOR_DEAD)) == 0)){
		if((map->getActorType(i) & ( ACTOR_EATABLE | ACTOR_POWERUP)) !=0){
if((map->getActorType(i) & ACTOR_DEAD) !=0){
//printf("The russians came %i \n", i);
}
else{
			map->getActorPosition(i, x, y);
			this->edibles[edibleindex].xco=x;
			this->edibles[edibleindex].yco=y;
			this->edibles[edibleindex].unreachable=false;
			this->numEdibles++;
//			printf("An edible was found at %i: set to %i %i numed=%i \n", i, edibles[edibleindex].pr.xco, edibles[edibleindex].pr.yco, this->numEdibles);
				
			edibleindex++;
		//	v=map->getVertex(x, y);
		//	allVer[v].verType=map->getActorType(i);
		//	allVer[v].pr.xco=x;
		//	allVer[v].pr.yco=y;
		//	allVer[v].index=i;
}
		}
/*		if(((map->getActorType(i)) & (ACTOR_ENEMY)) != 0){
		map->getActorPosition(i, x, y);
			enemypairs[enemyindex].xco=x;
			enemypairs[enemyindex].yco=y;
			
//			printf("An enemy was found at %i: set to %i %i \n", i, enemypairs[enemyindex].xco, enemypairs[enemyindex].yco);
			enemyindex++;
			v=map->getVertex(x, y);
			allVer[v].verType=map->getActorType(i);
			allVer[v].pr.xco=x;
			allVer[v].pr.yco=y;
			allVer[v].index=i;
			allVer[v].visited=true;
			
		}
*/	
	}	
return;
}

int SimpleHero::findPath(GraphMap* map, pair first, pair second, int& tempNextMove, int mode){
	if(second.unreachable==true){
		return -1;
	}

	
	int numNeigh=0;
	int a=0;
	int b=0;
	int c=0;
	int d=0;
	

	int count;
	int S[totalVertices];//int array indexed by vertex: 1 if visited
	int p[totalVertices]; //int array indexed by vertex int = move made to get here
	int last[totalVertices]; //int array indexed by vertex int=the vertex of its predecessor 
	std::queue<int> Q;
	bool done = false;
	int neighborV;
	for(int i=0; i < this->totalVertices; i++){
		S[i]=0;
		p[i]=0;
		last[i]=0;
		
	}
	int firstV = map->getVertex(first.xco, first.yco);
	int secondV = map->getVertex(second.xco, second.yco);
	  
	S[firstV]=1;//sets the Visited array at vertex of first to 1
	last[firstV]=firstV;
	p[firstV]=0;

	Q.push(firstV);

	while(Q.empty()==false && !done){
	    int v=Q.front();
	      
	 
	    map->getPosition(v, a, b);

	
	    numNeigh=map->getNumNeighbors( a, b );
	
	    for(int i=0; i<numNeigh && !done; i++){
 
		map->getNeighbor( a, b, i, c, d);
		neighborV=map->getVertex( c, d );
		   if(mode==1){
 			for(int j= 0; j < this->numEdibles && !done; j++){   
		
		    if(this->edibles[j].xco==c && this->edibles[j].yco==d){
			S[neighborV]=1;
		        p[neighborV]=i;
			last[neighborV]=v;
			done=true;
			Q.push(neighborV);

	            }
		    
		    }
	       }
		if(S[neighborV]==0){  //perform when neighborV first visited

		     S[neighborV]=1;  //S neighborV's vertex is set to visited
		     p[neighborV]=i;  //the move to get to neighborV is now in p
		    last[neighborV]=v;  //the predecessor's vertex for neighborV is set
		    Q.push(neighborV);  
		}
		
		if(neighborV==secondV){ //found the goal
		
		    done=true;
		
		}	

	
	    }  	    //end i for loop
	    Q.pop();
	}	//end while loop meaning all possible paths were checked and second was never reached
	
	if(done==true){  // second was reached and know referenced the last temp
		
	

	    while(last[neighborV] != firstV && count < 1000){

		neighborV=last[neighborV];
		count++; 

	    }  //after this loop neighborV should be vertex of first move.

	    tempNextMove=p[neighborV];
	    return count;
	    
	}

tempNextMove=0;
return -1;
}
