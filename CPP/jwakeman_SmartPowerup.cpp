 /*  Created on: Mar 30,2014
 *      Author: jwakeman
 */


#include "jwakeman_PU.hpp"
#include <time.h>
#include <math.h>
#include <ncurses.h>
#include <stdlib.h>
#include <queue>
#include <cstring>
#include <list>
	
using namespace jwakeman;
	SmartPowerup::lists SmartPowerup::arrayOfLists[10000];
	int SmartPowerup::numPowerups=0;
        int SmartPowerup::numHeroes=0;
        int SmartPowerup::numEnemies=0;
	int SmartPowerup::numEdibles=0;
	int SmartPowerup::edibles[5000];
        int SmartPowerup::powerups[1000];
        int SmartPowerup::enemies[500];
        int SmartPowerup::heroes[500];
	int SmartPowerup::totalActors;
	int SmartPowerup::totalVertices;
	int SmartPowerup::S[10000];



SmartPowerup::SmartPowerup(int type) : Actor(type)
{
	static int id=0;
	this->badge= id;
	this->firstMove=0;
	this->initV=0;
	id++; 
	
}

SmartPowerup::~SmartPowerup()
{
	
}



int SmartPowerup::selectNeighbor( GraphMap* map, int x, int y )
{
	
	int tempNextMove=0;
	int curNextMove=0;
	int curShortestPath=9999;
	int tempShortestPath=9999;
	int tempV;
	int tempV2;
	int tempV3;
	bool foundPath=false;
	static int counter=0;	
	static bool newMap=false;
	int tempNumEdibles=0;
	static int firstCall=0;
	memset(SmartPowerup::S, 0, 10000);

   

	if(firstCall==0){     //this if statement is only executed the first time select neighbor is called.
	
		totalActors = map->getNumActors();
		totalVertices = map->getNumVertices();
		makeLists(map);	
		updateVertices(map);
		firstCall=1;

	}  

	 if(this->firstMove==0){
	    this->initV=powerups[(this->badge-2)];
	    this->currentV=initV;

	    this->firstMove=1;
	}
	for(int i=0; i<SmartPowerup::totalActors; i++){
 	
	 if((map->getActorType(i) & ( ACTOR_EATABLE | ACTOR_POWERUP)) !=0){
	      if((map->getActorType(i) & ACTOR_DEAD) !=0){

	      }
	   
	      else{
			tempNumEdibles++;
              }
	  }
	}

	if(tempNumEdibles>(SmartPowerup::numEdibles+SmartPowerup::numHeroes)){
	   counter=SmartPowerup::numPowerups;
	   updateVertices(map);
	   newMap=true;
	
	}
	if(newMap==true){
		this->currentV=this->initV;
	}
	
	if(counter==0){	
   	    updateVertices(map);
	    counter=SmartPowerup::numPowerups;
	    newMap=false;
	}
   	

       
	
	for(int i = 0; i < numPowerups; i++){

	    tempV=powerups[i];

	    if(tempV==this->currentV){

	/*	for(list<pair>::const_iterator iterator = SmartPowerup::arrayOfLists[tempV].oneMove.begin(), end = SmartPowerup::arrayOfLists[tempV].oneMove.end(); iterator != end; ++iterator) {
		
		    for(int j = 0; j < numEnemies; j++){
	 		
		        if(iterator->xco==enemies[j] && S[iterator->xco]==0){
	    		     this->currentV=iterator->xco;
				return iterator->yco;
			    
			}
		    }
		}*/
	

		for(int j = 0; j < SmartPowerup::numHeroes; j++){
		
		    for(list<pair>::const_iterator iterator = SmartPowerup::arrayOfLists[heroes[j]].fiveMoves.begin(), end = SmartPowerup::arrayOfLists[heroes[j]].fiveMoves.end(); iterator != end; ++iterator) {
			 	   		
			 SmartPowerup::S[iterator->xco]=1;
		    }
		}

		for(int j = 0; j < SmartPowerup::numEnemies; j++){

		   tempV2=findPath(map, tempV, SmartPowerup::enemies[j], tempNextMove, tempShortestPath);

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
		memset(SmartPowerup::S, 0, 10000);

		for(int j = 0; j < SmartPowerup::numHeroes; j++){
		
		   for(list<pair>::const_iterator iterator = SmartPowerup::arrayOfLists[heroes[j]].fourMoves.begin(), end = SmartPowerup::arrayOfLists[heroes[j]].fourMoves.end(); iterator != end; ++iterator) {
			 	   		
		        SmartPowerup::S[iterator->xco]=1;
		   }
		}
		for(int j = 0; j < SmartPowerup::numEnemies; j++){

		   tempV2=findPath(map, tempV, SmartPowerup::enemies[j], tempNextMove, tempShortestPath);

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

		memset(SmartPowerup::S, 0, 10000);

		for(int j = 0; j < SmartPowerup::numHeroes; j++){
		
		   for(list<pair>::const_iterator iterator = SmartPowerup::arrayOfLists[heroes[j]].threeMoves.begin(), end = SmartPowerup::arrayOfLists[heroes[j]].threeMoves.end(); iterator != end; ++iterator) {
			 	   		
		        SmartPowerup::S[iterator->xco]=1;
		   }
		}
		for(int j = 0; j < SmartPowerup::numEnemies; j++){

		   tempV2=findPath(map, tempV, SmartPowerup::enemies[j], tempNextMove, tempShortestPath);

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
		memset(SmartPowerup::S, 0, 10000);

		for(int j = 0; j < SmartPowerup::numHeroes; j++){
		
		   for(list<pair>::const_iterator iterator = SmartPowerup::arrayOfLists[heroes[j]].twoMoves.begin(), end = SmartPowerup::arrayOfLists[heroes[j]].twoMoves.end(); iterator != end; ++iterator) {
			 	   		
		        SmartPowerup::S[iterator->xco]=1;
		   }
		}
		for(int j = 0; j < SmartPowerup::numEnemies; j++){

		   tempV2=findPath(map, tempV, SmartPowerup::enemies[j], tempNextMove, tempShortestPath);

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
		memset(SmartPowerup::S, 0, 10000);

		for(int j = 0; j < SmartPowerup::numHeroes; j++){
		
		   for(list<pair>::const_iterator iterator = SmartPowerup::arrayOfLists[heroes[j]].oneMove.begin(), end = SmartPowerup::arrayOfLists[heroes[j]].oneMove.end(); iterator != end; ++iterator) {
			 	   		
		        SmartPowerup::S[iterator->xco]=1;
		   }
		}
		for(int j = 0; j < SmartPowerup::numEnemies; j++){

		   tempV2=findPath(map, tempV, SmartPowerup::enemies[j], tempNextMove, tempShortestPath);

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
		
		counter--;
		return 0;
	    }
	}	
counter--;
return 0;
}

			

Actor* SmartPowerup::duplicate()
{


	return new SmartPowerup(ACTOR_POWERUP);

}


const char* SmartPowerup::getActorId()
{
	return "smartpowerup";
}


const char* SmartPowerup::getNetId()
{
	return "jwakeman";
}

void SmartPowerup::updateVertices(GraphMap* map){

	SmartPowerup::numPowerups=0;

	SmartPowerup::numHeroes=0;

	SmartPowerup::numEnemies=0;
 	SmartPowerup::numEdibles=0;
        memset(SmartPowerup::powerups, 0, 1000);
        memset(SmartPowerup::enemies, 0, 500);
        memset(SmartPowerup::heroes, 0, 500);
        memset(SmartPowerup::edibles, 0, 5000);
        int x=0;
        int y=0;

	for(int i=0; i<SmartPowerup::totalActors; i++){

	    if((map->getActorType(i) & (ACTOR_DEAD | ACTOR_HERO))==ACTOR_HERO){

		map->getActorPosition(i, x, y);
		SmartPowerup::heroes[numHeroes]=map->getVertex(x,y);
		SmartPowerup::numHeroes++;		
	    }
	
	    if((map->getActorType(i) & (ACTOR_DEAD | ACTOR_POWERUP))==ACTOR_POWERUP){
		
		map->getActorPosition(i, x, y);	
		SmartPowerup::powerups[numPowerups]=map->getVertex(x,y);
		SmartPowerup::numPowerups++;
	    }

            if((map->getActorType(i) & ( ACTOR_DEAD | ACTOR_ENEMY)) ==ACTOR_ENEMY){
		
		map->getActorPosition(i, x, y);	
		SmartPowerup::enemies[numEnemies]=map->getVertex(x,y);
		SmartPowerup::numEnemies++;
	    }

	   if((map->getActorType(i) & ( ACTOR_EATABLE | ACTOR_POWERUP)) !=0){
	      if((map->getActorType(i) & ACTOR_DEAD) !=0){

	      }
	   
	      else{
			map->getActorPosition(i, x, y);
			SmartPowerup::edibles[numEdibles]=map->getVertex(x,y);
			SmartPowerup::numEdibles++;
              }
	  }

       }
    
return;
}

void SmartPowerup::makeLists(GraphMap* map){
    pair temp;
    int a=0;
    int b=0;
    int c=0;
    int d=0;
    
	for(int i = 0; i < SmartPowerup::totalVertices; i++){
	
	    map->getPosition(i, a, b);
	    for(int j = 0, numNeigh=map->getNumNeighbors(a, b); j < numNeigh; j++){
		map->getNeighbor( a, b, j, c, d);
		temp.xco=map->getVertex(c,d);
	        temp.yco=j;
		SmartPowerup::arrayOfLists[i].oneMove.push_front(temp);
	    }
	}

	for(int i =0; i < totalVertices; i++){
	    for (list<pair>::const_iterator iterator = SmartPowerup::arrayOfLists[i].oneMove.begin(), end = SmartPowerup::arrayOfLists[i].oneMove.end(); iterator != end; ++iterator) {
    		     
		map->getPosition(iterator->xco, a, b);
		for(int j =0, numNeigh=map->getNumNeighbors(a,b); j < numNeigh; j++){
		    map->getNeighbor(a, b, j, c, d);
		    temp.xco=map->getVertex(c,d);
		    temp.yco=iterator->yco; 
		    SmartPowerup::arrayOfLists[i].twoMoves.push_front(temp);
		}
	    }
	}
	
	for(int i =0; i < totalVertices; i++){
	    for (list<pair>::const_iterator iterator = SmartPowerup::arrayOfLists[i].twoMoves.begin(), end = SmartPowerup::arrayOfLists[i].twoMoves.end(); iterator != end; ++iterator) {
    		     
		map->getPosition(iterator->xco, a, b);
		for(int j =0, numNeigh=map->getNumNeighbors(a,b); j  < numNeigh; j++){
		    map->getNeighbor(a, b, j, c, d);
		    temp.xco=map->getVertex(c,d);
		    temp.yco=iterator->yco; 
		    SmartPowerup::arrayOfLists[i].threeMoves.push_front(temp);
		}
	    }
	}
	
	for(int i = 0; i < SmartPowerup::totalVertices; i++){
	    for (list<pair>::const_iterator iterator = SmartPowerup::arrayOfLists[i].threeMoves.begin(), end = SmartPowerup::arrayOfLists[i].threeMoves.end(); iterator != end; ++iterator) {
    		     
		map->getPosition(iterator->xco, a, b);
		for(int j =0, numNeigh=map->getNumNeighbors(a,b); j  < numNeigh; j++){
		    map->getNeighbor(a, b, j, c, d);
		    temp.xco=map->getVertex(c,d);
		    temp.yco=iterator->yco; 
		    SmartPowerup::arrayOfLists[i].fourMoves.push_front(temp);
		}
	    }
	}
	
	for(int i =0; i < totalVertices; i++){
	    for (list<pair>::const_iterator iterator = SmartPowerup::arrayOfLists[i].fourMoves.begin(), end = SmartPowerup::arrayOfLists[i].fourMoves.end(); iterator != end; ++iterator) {
    		     
		map->getPosition(iterator->xco, a, b);
		for(int j =0, numNeigh=map->getNumNeighbors(a,b); j  < numNeigh; j++){
		    map->getNeighbor(a, b, j, c, d);
		    temp.xco=map->getVertex(c,d);
		    temp.yco=iterator->yco; 
		    SmartPowerup::arrayOfLists[i].fiveMoves.push_front(temp);
		}
	    }
	}

return;
}
   		
int SmartPowerup::findPath(GraphMap* map, int first, int second, int& tempNextMove, int& tempShortestPath){
	
	
	
	
	

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
	
	    int numNeigh=0;
	    int a=0;
            int b=0;
	    int c=0;
	    int d=0;
	    int v=Q.front();
	      
	 
	    map->getPosition(v, a, b);

	
	    numNeigh=map->getNumNeighbors( a, b );
	
	    for(int i=0; i<numNeigh && !done; i++){
 
		map->getNeighbor( a, b, i, c, d);
		neighborV=map->getVertex( c, d );
	
		if(S[neighborV]==0){
 		    for(int j= 0; j < SmartPowerup::numEnemies && !done; j++){   
		
		        if(SmartPowerup::enemies[j]==neighborV){
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



