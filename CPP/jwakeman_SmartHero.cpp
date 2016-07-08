 /*  Created on: Mar 30,2014
 *      Author: jwakeman
 */

#include "jwakeman_Smart.hpp"
#include <time.h>
#include <math.h>
#include <ncurses.h>
#include <stdlib.h>
#include <queue>
#include <cstring>
#include <list>

using namespace jwakeman;
	SmartHero::lists SmartHero::arrayOfLists[10000];
	int SmartHero::numPowerups=0;
        int SmartHero::numHeroes=0;
        int SmartHero::numEnemies=0;
	int SmartHero::numEdibles=0;
	int SmartHero::edibles[5000];
        int SmartHero::powerups[1000];
        int SmartHero::enemies[500];
        int SmartHero::heroes[500];
	int SmartHero::totalActors;
	int SmartHero::totalVertices;
	int SmartHero::S[10000];
SmartHero::SmartHero(int type) : Actor(type)
{
	static int id=0;
	this->badge= id;
	this->firstMove=0;
	this->initV=0;
	this->currentV=0;
	id++; 
	
	
}

SmartHero::~SmartHero()
{
	
}



int SmartHero::selectNeighbor( GraphMap* map, int x, int y )
{
	//this->numMoves++;
	int tempNextMove=0;
	int curNextMove=0;
	int curShortestPath=9999;
	int tempShortestPath=9999;
	int tempV;
	int tempV2;
	int tempV3;
	int tempNumEdibles=0;
	static bool newMap=false;
	bool foundPath=false;
	static int counter=0;	
	static int firstCall=0;
	memset(SmartHero::S, 0, 10000);

	if(firstCall==0){     //this if statement is only executed the first time select neighbor is called.
		SmartHero::totalActors = map->getNumActors();
		SmartHero::totalVertices = map->getNumVertices();
		makeLists(map);
		updateVertices(map);
		firstCall=1;
	
	}

	if(this->firstMove==0){
	    this->initV=heroes[this->badge-2];
	    this->currentV=this->initV;
	    this->firstMove=1;

 	}  

	for(int i=0; i<SmartHero::totalActors; i++){
 	
	 if((map->getActorType(i) & ( ACTOR_EATABLE | ACTOR_POWERUP)) !=0){
	      if((map->getActorType(i) & ACTOR_DEAD) !=0){

	      }
	   
	      else{
			tempNumEdibles++;
              }
	  }
	}

	if(tempNumEdibles>(SmartHero::numEdibles+SmartHero::numHeroes)){
	   this->currentV=this->initV;
	   counter=SmartHero::numHeroes;
	   updateVertices(map);
	   newMap=true;
	}
	if(newMap==true){
	    this->currentV=this->initV;
	}

	if(counter==0){	
   	    updateVertices(map);
	    counter=SmartHero::numHeroes;
	    newMap=false;

	}
	
	for(int i = 0; i < numHeroes; i++){

	    tempV=heroes[i];

	    if(tempV==this->currentV){

		for(int j = 0; j < SmartHero::numEnemies; j++){
		
		   for(list<pair>::const_iterator iterator = SmartHero::arrayOfLists[enemies[j]].fiveMoves.begin(), end = SmartHero::arrayOfLists[enemies[j]].fiveMoves.end(); iterator != end; ++iterator) {
			 	   		
		       SmartHero::S[iterator->xco]=1;
		   }
		}
		for(int j = 0; j < SmartHero::numPowerups; j++){

		   tempV2=findPath(map, tempV, SmartHero::powerups[j], tempNextMove, tempShortestPath);

		     if(tempShortestPath<curShortestPath){
	
			curShortestPath=tempShortestPath;
		  	curNextMove=tempNextMove;
		 	tempV3=tempV2;
			foundPath=true;
		    }
		}
	       
		 if(foundPath==true){
	             counter--;   
	             this->currentV=tempV3;
	             return curNextMove;
	         }
		memset(SmartHero::S, 0, 10000);

		for(int j = 0; j < SmartHero::numEnemies; j++){
		
		   for(list<pair>::const_iterator iterator = SmartHero::arrayOfLists[enemies[j]].fourMoves.begin(), end = SmartHero::arrayOfLists[enemies[j]].fourMoves.end(); iterator != end; ++iterator) {
			 	   		
		        SmartHero::S[iterator->xco]=1;
		   }
		}
		for(int j = 0; j < SmartHero::numPowerups; j++){

		   tempV2=findPath(map, tempV, SmartHero::powerups[j], tempNextMove, tempShortestPath);

		     if(tempShortestPath<curShortestPath){
	
			curShortestPath=tempShortestPath;
		  	curNextMove=tempNextMove;
		 	tempV3=tempV2;
			foundPath=true;
		    }
		}
	       
		 if(foundPath==true){
	             counter--;   
	             this->currentV=tempV3;
	             return curNextMove;
	         }

		memset(SmartHero::S, 0, 10000);
		
		for(int j = 0; j < SmartHero::numEnemies; j++){
		
		   for(list<pair>::const_iterator iterator = SmartHero::arrayOfLists[enemies[j]].threeMoves.begin(), end = SmartHero::arrayOfLists[enemies[j]].threeMoves.end(); iterator != end; ++iterator) {
			 	   		
		        SmartHero::S[iterator->xco]=1;
		   }
		}
		for(int j = 0; j < SmartHero::numPowerups; j++){

		   tempV2=findPath(map, tempV, SmartHero::powerups[j], tempNextMove, tempShortestPath);

		     if(tempShortestPath<curShortestPath){
	
			curShortestPath=tempShortestPath;
		  	curNextMove=tempNextMove;
		 	tempV3=tempV2;
			foundPath=true;
		    }
		}
	       
		 if(foundPath==true){
	             counter--;   
	             this->currentV=tempV3;
	             return curNextMove;
	         }
		
		memset(SmartHero::S, 0, 10000);
	
		for(int j = 0; j < SmartHero::numEnemies; j++){
		
		   for(list<pair>::const_iterator iterator = SmartHero::arrayOfLists[enemies[j]].twoMoves.begin(), end = SmartHero::arrayOfLists[enemies[j]].twoMoves.end(); iterator != end; ++iterator) {
			 	   		
		        SmartHero::S[iterator->xco]=1;
		   }
		}
		for(int j = 0; j < SmartHero::numPowerups; j++){

		   tempV2=findPath(map, tempV, SmartHero::powerups[j], tempNextMove, tempShortestPath);

		     if(tempShortestPath<curShortestPath){
	
			curShortestPath=tempShortestPath;
		  	curNextMove=tempNextMove;
		 	tempV3=tempV2;
			foundPath=true;
		    }
		}
	       
		 if(foundPath==true){
	             counter--;   
	             this->currentV=tempV3;
	             return curNextMove;
	         }
		
		memset(SmartHero::S, 0, 10000);

		for(int j = 0; j < SmartHero::numEnemies; j++){
		
		   for(list<pair>::const_iterator iterator = SmartHero::arrayOfLists[enemies[j]].oneMove.begin(), end = SmartHero::arrayOfLists[enemies[j]].oneMove.end(); iterator != end; ++iterator) {
			 	   		
		        SmartHero::S[iterator->xco]=1;
		   }
		}
		for(int j = 0; j < SmartHero::numPowerups; j++){

		   tempV2=findPath(map, tempV, SmartHero::powerups[j], tempNextMove, tempShortestPath);

		     if(tempShortestPath<curShortestPath){
	
			curShortestPath=tempShortestPath;
		  	curNextMove=tempNextMove;
		 	tempV3=tempV2;
			foundPath=true;
		    }
		}
	       
		 if(foundPath==true){
	             counter--;   
	             this->currentV=tempV3;
	             return curNextMove;
	         }

	    	memset(SmartHero::S, 0, 10000); 

		for(int j = 0; j < SmartHero::numEnemies; j++){
		
		   for(list<pair>::const_iterator iterator = SmartHero::arrayOfLists[enemies[j]].fiveMoves.begin(), end = SmartHero::arrayOfLists[enemies[j]].fiveMoves.end(); iterator != end; ++iterator) {
			 	   		
		        SmartHero::S[iterator->xco]=1;
		   }
		}
		for(int j = 0; j < SmartHero::numEdibles; j++){

		   tempV2=findPath(map, tempV, SmartHero::edibles[j], tempNextMove, tempShortestPath);

		     if(tempShortestPath<curShortestPath){
	
			curShortestPath=tempShortestPath;
		  	curNextMove=tempNextMove;
		 	tempV3=tempV2;
			foundPath=true;
		    }
		}
	       
		 if(foundPath==true){
	             counter--;   
	             this->currentV=tempV3;
	             return curNextMove;
	         }
		memset(SmartHero::S, 0, 10000);

		for(int j = 0; j < SmartHero::numEnemies; j++){
		
		   for(list<pair>::const_iterator iterator = SmartHero::arrayOfLists[enemies[j]].fourMoves.begin(), end = SmartHero::arrayOfLists[enemies[j]].fourMoves.end(); iterator != end; ++iterator) {
			 	   		
		        SmartHero::S[iterator->xco]=1;
		   }
		}
		for(int j = 0; j < SmartHero::numEdibles; j++){

		   tempV2=findPath(map, tempV, SmartHero::edibles[j], tempNextMove, tempShortestPath);

		     if(tempShortestPath<curShortestPath){
	
			curShortestPath=tempShortestPath;
		  	curNextMove=tempNextMove;
		 	tempV3=tempV2;
			foundPath=true;
		    }
		}
	       
		 if(foundPath==true){
	             counter--;   
	             this->currentV=tempV3;
	             return curNextMove;
	         }

		memset(SmartHero::S, 0, 10000);
		
		for(int j = 0; j < SmartHero::numEnemies; j++){
		
		   for(list<pair>::const_iterator iterator = SmartHero::arrayOfLists[enemies[j]].threeMoves.begin(), end = SmartHero::arrayOfLists[enemies[j]].threeMoves.end(); iterator != end; ++iterator) {
			 	   		
		        SmartHero::S[iterator->xco]=1;
		   }
		}
		for(int j = 0; j < SmartHero::numEdibles; j++){

		   tempV2=findPath(map, tempV, SmartHero::edibles[j], tempNextMove, tempShortestPath);

		     if(tempShortestPath<curShortestPath){
	
			curShortestPath=tempShortestPath;
		  	curNextMove=tempNextMove;
		 	tempV3=tempV2;
			foundPath=true;
		    }
		}
	       
		 if(foundPath==true){
	             counter--;   
	             this->currentV=tempV3;
	             return curNextMove;
	         }
		
		memset(SmartHero::S, 0, 10000);
	
		for(int j = 0; j < SmartHero::numEnemies; j++){
		
		   for(list<pair>::const_iterator iterator = SmartHero::arrayOfLists[enemies[j]].twoMoves.begin(), end = SmartHero::arrayOfLists[enemies[j]].twoMoves.end(); iterator != end; ++iterator) {
			 	   		
		        SmartHero::S[iterator->xco]=1;
		   }
		}
		for(int j = 0; j < SmartHero::numEdibles; j++){

		   tempV2=findPath(map, tempV, SmartHero::edibles[j], tempNextMove, tempShortestPath);

		     if(tempShortestPath<curShortestPath){
	
			curShortestPath=tempShortestPath;
		  	curNextMove=tempNextMove;
		 	tempV3=tempV2;
			foundPath=true;
		    }
		}
	       
		 if(foundPath==true){
	             counter--;   
	             this->currentV=tempV3;
	             return curNextMove;
	         }
		
		memset(SmartHero::S, 0, 10000);

		for(int j = 0; j < SmartHero::numEnemies; j++){
		
		   for(list<pair>::const_iterator iterator = SmartHero::arrayOfLists[enemies[j]].oneMove.begin(), end = SmartHero::arrayOfLists[enemies[j]].oneMove.end(); iterator != end; ++iterator) {
			 	   		
		        SmartHero::S[iterator->xco]=1;
		   }
		}
		for(int j = 0; j < SmartHero::numEdibles; j++){

		   tempV2=findPath(map, tempV, SmartHero::edibles[j], tempNextMove, tempShortestPath);

		     if(tempShortestPath<curShortestPath){
	
			curShortestPath=tempShortestPath;
		  	curNextMove=tempNextMove;
		 	tempV3=tempV2;
			foundPath=true;
		    }
		}
	       
		 if(foundPath==true){
                     counter--;   
	             this->currentV=tempV3;
	             return curNextMove;
	         }

	     
		for(int j = 0; j < SmartHero::numEnemies; j++){

		memset(SmartHero::S, 0, 10000);

		for(int k = 0; k < SmartHero::numEnemies; k++){
		
		   for(list<pair>::const_iterator iterator = SmartHero::arrayOfLists[enemies[k]].oneMove.begin(), end = SmartHero::arrayOfLists[enemies[k]].oneMove.end(); iterator != end; ++iterator) {
			 	   		
		        SmartHero::S[iterator->xco]=1;
		   }
		}

		 for(list<pair>::const_iterator iterator = SmartHero::arrayOfLists[enemies[j]].threeMoves.begin(), end = SmartHero::arrayOfLists[enemies[j]].threeMoves.end(); iterator != end; ++iterator) {
			
		   tempV2=findPath(map, tempV, SmartHero::enemies[j], tempNextMove, tempShortestPath);

		     if(tempShortestPath<curShortestPath){
	
			curShortestPath=tempShortestPath;
		  	curNextMove=tempNextMove;
		 	tempV3=tempV2;
			foundPath=true;
		     }
		  }
	        }
	       
		 if(foundPath==true){
	             counter--;   
	             this->currentV=tempV3;
	             return curNextMove;
	         }
	


	    } //end if it is this hero loop
	} //end i for loop going through heroes

counter--;
return 0;	

}


Actor* SmartHero::duplicate()
{


	return new SmartHero(ACTOR_HERO);

}


const char* SmartHero::getActorId()
{
	return "smarthero";
}


const char* SmartHero::getNetId()
{
	return "jwakeman";
}

void SmartHero::updateVertices(GraphMap* map){


	SmartHero::numPowerups=0;

	SmartHero::numHeroes=0;

	SmartHero::numEnemies=0;
 	SmartHero::numEdibles=0;
     memset(SmartHero::powerups, 0, 1000);
     memset(SmartHero::enemies, 0, 500);
     memset(SmartHero::heroes, 0, 500);
     memset(SmartHero::edibles, 0, 5000);
     int x=0;
     int y=0;

	for(int i=0; i<SmartHero::totalActors; i++){

	    if((map->getActorType(i) & (ACTOR_DEAD | ACTOR_HERO))==ACTOR_HERO){

		map->getActorPosition(i, x, y);
		SmartHero::heroes[numHeroes]=map->getVertex(x,y);
		SmartHero::numHeroes++;		
	    }
	
	    if((map->getActorType(i) & (ACTOR_DEAD | ACTOR_POWERUP))==ACTOR_POWERUP){
		
		map->getActorPosition(i, x, y);	
		SmartHero::powerups[numPowerups]=map->getVertex(x,y);
		SmartHero::numPowerups++;
	    }

            if((map->getActorType(i) & ( ACTOR_DEAD | ACTOR_ENEMY)) ==ACTOR_ENEMY){
		
		map->getActorPosition(i, x, y);	
		SmartHero::enemies[numEnemies]=map->getVertex(x,y);
		SmartHero::numEnemies++;
	    }

	   if((map->getActorType(i) & ( ACTOR_EATABLE | ACTOR_POWERUP)) !=0){
	      if((map->getActorType(i) & ACTOR_DEAD) !=0){

	      }
	   
	      else{
			map->getActorPosition(i, x, y);
			SmartHero::edibles[numEdibles]=map->getVertex(x,y);
			SmartHero::numEdibles++;	
              }
	  }

       }
    
return;
}

void SmartHero::makeLists(GraphMap* map){
    pair temp;
    int a=0;
    int b=0;
    int c=0;
    int d=0;
    int numNeigh=0;
    d=numNeigh;
	for(int i = 0; i < SmartHero::totalVertices; i++){
	
	    map->getPosition(i, a, b);
	    for(int j = 0, numNeigh=map->getNumNeighbors(a, b); j < numNeigh; j++){
		map->getNeighbor( a, b, j, c, d);
		temp.xco=map->getVertex(c,d);
	        temp.yco=j;
		SmartHero::arrayOfLists[i].oneMove.push_front(temp);
	    }
	}

	for(int i =0; i < totalVertices; i++){
	    for (list<pair>::const_iterator iterator = SmartHero::arrayOfLists[i].oneMove.begin(), end = SmartHero::arrayOfLists[i].oneMove.end(); iterator != end; ++iterator) {
    		     
		map->getPosition(iterator->xco, a, b);
		for(int j =0, numNeigh=map->getNumNeighbors(a,b); j < numNeigh; j++){
		    map->getNeighbor(a, b, j, c, d);
		    temp.xco=map->getVertex(c,d);
		    temp.yco=iterator->yco; 
		    SmartHero::arrayOfLists[i].twoMoves.push_front(temp);
		}
	    }
	}
	
	for(int i =0; i < totalVertices; i++){
	    for (list<pair>::const_iterator iterator = SmartHero::arrayOfLists[i].twoMoves.begin(), end = SmartHero::arrayOfLists[i].twoMoves.end(); iterator != end; ++iterator) {
    		     
		map->getPosition(iterator->xco, a, b);
		for(int j =0, numNeigh=map->getNumNeighbors(a,b); j  < numNeigh; j++){
		    map->getNeighbor(a, b, j, c, d);
		    temp.xco=map->getVertex(c,d);
		    temp.yco=iterator->yco; 
		    SmartHero::arrayOfLists[i].threeMoves.push_front(temp);
		}
	    }
	}
	
	for(int i = 0; i < SmartHero::totalVertices; i++){
	    for (list<pair>::const_iterator iterator = SmartHero::arrayOfLists[i].threeMoves.begin(), end = SmartHero::arrayOfLists[i].threeMoves.end(); iterator != end; ++iterator) {
    		     
		map->getPosition(iterator->xco, a, b);
		for(int j =0, numNeigh=map->getNumNeighbors(a,b); j  < numNeigh; j++){
		    map->getNeighbor(a, b, j, c, d);
		    temp.xco=map->getVertex(c,d);
		    temp.yco=iterator->yco; 
		    SmartHero::arrayOfLists[i].fourMoves.push_front(temp);
		}
	    }
	}
	
	for(int i =0; i < totalVertices; i++){
	    for (list<pair>::const_iterator iterator = SmartHero::arrayOfLists[i].fourMoves.begin(), end = SmartHero::arrayOfLists[i].fourMoves.end(); iterator != end; ++iterator) {
    		     
		map->getPosition(iterator->xco, a, b);
		for(int j =0, numNeigh=map->getNumNeighbors(a,b); j  < numNeigh; j++){
		    map->getNeighbor(a, b, j, c, d);
		    temp.xco=map->getVertex(c,d);
		    temp.yco=iterator->yco; 
		    SmartHero::arrayOfLists[i].fiveMoves.push_front(temp);
		}
	    }
	}

return;
}
   		
int SmartHero::findPath(GraphMap* map, int first, int second, int& tempNextMove, int& tempShortestPath){
	
	
	int numNeigh=0;
	int a=0;
	int b=0;
	int c=0;
	int d=0;
	

	int count=1;
	int p[totalVertices]; //int array indexed by vertex int = move made to get here
	int last[totalVertices]; //int array indexed by vertex int=the vertex of its predecessor 
	std::queue<int> Q;
	bool done = false;
	int neighborV;

	for(int i=0; i < this->totalVertices; i++){

	     p[i]=0;
	     last[i]=0;
	}

	int firstV = first;
	int secondV = second;
	  
	S[firstV]=1; //sets the Visited array at vertex of first to 1
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
	
		if(S[neighborV]==0){
 		    for(int j= 0; j < SmartHero::numEdibles && !done; j++){   
		
		        if(SmartHero::edibles[j]==neighborV){
			     S[neighborV]=1;
		             p[neighborV]=i;
			     last[neighborV]=v;
			     done=true;
			     Q.push(neighborV);

	                }
		    }
	        }
		if(S[neighborV]==0){  //perform when neighborV first visited

		  if(neighborV==secondV){ //found the goal
		
		    done=true;
		
		  }
		     S[neighborV]=1;  //S neighborV's vertex is set to visited
		     p[neighborV]=i;  //the move to get to neighborV is now in p
		    last[neighborV]=v;  //the predecessor's vertex for neighborV is set
		    Q.push(neighborV);  
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
	    tempShortestPath=count;
	    return neighborV;
	    
	}

tempNextMove=0;
tempShortestPath=9999;
return this->currentV;
}
