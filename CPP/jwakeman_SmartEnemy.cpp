 /*  Created on: Mar 30,2014
 *      Author: jwakeman
 */
 
#include "jwakeman_SE.hpp"
#include <time.h>
#include <math.h>
#include <ncurses.h>
#include <stdlib.h>
#include <queue>
#include <cstring>
#include <list>

using namespace jwakeman;

	SmartEnemy::lists SmartEnemy::arrayOfLists[10000];
	int SmartEnemy::numPowerups=0;
	
        int SmartEnemy::numHeroes=0;
	
        int SmartEnemy::numEnemies=0;
	int SmartEnemy::numEdibles=0;
	int SmartEnemy::edibles[1500];
        int SmartEnemy::powerups[1000];
        int SmartEnemy::enemies[500];
        int SmartEnemy::heroes[500];
	list<int> SmartEnemy::targets;
	int SmartEnemy::totalActors;
	int SmartEnemy::totalVertices;
	list<int> SmartEnemy::targeted;
SmartEnemy::SmartEnemy(int type) : Actor(type)
{
	static int id=0;
	this->badge= id;
	this->firstMove=0;
	id++; 
	
	
}

SmartEnemy::~SmartEnemy()
{
	
}



int SmartEnemy::selectNeighbor( GraphMap* map, int x, int y )
{
	//this->numMoves++;
	int tempNextMove=0;
	int curNextMove=0;
	int curShortestPath=9999;
	int tempShortestPath=9999;
	list<int>::iterator ittodelete;
	int tempV;
	int tempV2;
	int tempV3;
	int tempNumEdibles=0;
	static bool newMap=false;
	bool foundPath=false;
	static int counter=0;	
	static int firstCall=0;
	
	if(firstCall==0){     //this if statement is only executed the first time select neighbor is called.
		SmartEnemy::totalActors = map->getNumActors();
		SmartEnemy::totalVertices = map->getNumVertices();
		makeLists(map);
		updateVertices(map);
		firstCall=1;

	}

	if(this->firstMove==0){
	    this->initV=enemies[this->badge-2];
	    this->currentV=this->initV;
	    this->firstMove=1;
	}  
	for(int i=0; i<SmartEnemy::totalActors; i++){
 	
	 if((map->getActorType(i) & ( ACTOR_EATABLE | ACTOR_POWERUP)) !=0){
	      if((map->getActorType(i) & ACTOR_DEAD) !=0){

	      }
	   
	      else{
			tempNumEdibles++;
              }
	  }
	}

	if(tempNumEdibles>(SmartEnemy::numEdibles+SmartEnemy::numHeroes)){
	   this->currentV=this->initV;
	   counter=SmartEnemy::numEnemies;
	   updateVertices(map);
	   newMap=true;
	   SmartEnemy::targets=makeMinMax();
	   SmartEnemy::targeted.clear();

	}
	if(newMap==true){
	    this->currentV=this->initV;
	}

	if(counter==0){	
   	    updateVertices(map);
	    counter=SmartEnemy::numEnemies;
	    newMap=false;
	    SmartEnemy::targets=makeMinMax();
	    SmartEnemy::targeted.clear();
	}

		
	for(int i = 0; i < numEnemies; i++){

	    tempV=enemies[i];

	    if(tempV==this->currentV){


		if(counter==(SmartEnemy::numEnemies-2)){

		    for(int j = 0; j < SmartEnemy::numEdibles; j++){
			
			tempV2=findPath(map, tempV, SmartEnemy::edibles[j], tempNextMove, tempShortestPath);
	
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
	      	
			
			  
		}

		for(list<pair>::const_iterator iterator = SmartEnemy::arrayOfLists[tempV].fiveMoves.begin(), end = SmartEnemy::arrayOfLists[tempV].fiveMoves.end(); iterator != end; ++iterator) {
		
		    for(int j = 0; j < SmartEnemy::numHeroes; j++){
	 	   		
			if(iterator->xco==SmartEnemy::heroes[j]){
				
			    this->currentV=findPath(map, tempV, SmartEnemy::heroes[j], tempNextMove, tempShortestPath);
			    counter--;
			    return tempNextMove;
			    
			}
		    }
		}
            
                for (list<int>::iterator it = SmartEnemy::targets.begin(), end = SmartEnemy::targets.end(); it != end; ++it) {
    		
		
		    tempV2=findPath(map, tempV, *it, tempNextMove, tempShortestPath);
	
		    if(tempShortestPath<curShortestPath){
			ittodelete=it;
			curShortestPath=tempShortestPath;
		  	curNextMove=tempNextMove;
		 	tempV3=tempV2;
			foundPath=true;
		    }
		}
	       
		 if(foundPath==true){
	             targets.erase (ittodelete);
	             counter--;   
	             this->currentV=tempV3;
	             return curNextMove;
	         }
	      	
		 for (int k=0; k < SmartEnemy::numHeroes; k++) {
    		
		
		  tempV2=findPath(map, tempV, SmartEnemy::heroes[k], tempNextMove, tempShortestPath);
	
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

              
	    }
	}

counter--;
return 0;	

}/*
	
		for(list<pair>::const_iterator iterator = SmartEnemy::arrayOfLists[tempV].fourMoves.begin(), end = SmartEnemy::arrayOfLists[tempV].fourMoves.end(); iterator != end; ++iterator) {

		    dontVisit[iterator->xco]=1;
	   	}

		for(int j = 0; j < SmartEnemy::numHeroes; j++){
	 	   
           	     for(list<pair>::const_iterator iterator2 = arrayOfLists[heroes[j]].fiveMoves.begin(), end = arrayOfLists[heroes[j]].fiveMoves.end(); iterator2 != end; ++iterator2) {

              	    	dontVisit[iterator2->xco]=1;
		    }
		}
	 	   
           	for(list<pair>::const_iterator iterator = arrayOfLists[tempV].fiveMoves.begin(), end = arrayOfLists[tempV].fiveMoves.end(); iterator != end; ++iterator) {

		    if(dontVisit[iterator->xco]==0){

			map->getPosition(currentV, a, b);
			map->getNeighbor( a, b, iterator->yco, c, d);
			this->currentV=map->getVertex(c,d);
			return iterator->yco;
		    }
		}

	        for(int j=0; j < numHeroes; j++){

		    for(list<pair>::const_iterator iterator2 = arrayOfLists[heroes[j]].oneMove.begin(), end = arrayOfLists[heroes[j]].oneMove.end(); iterator2 != end; ++iterator2) {
			
			dontVisit2[iterator2->xco]=1;
		    }
		}

		for(list<pair>::const_iterator iterator = arrayOfLists[tempV].oneMove.begin(), end = arrayOfLists[tempV].oneMove.end(); iterator != end; ++iterator) {
		    
 		    if(dontVisit2[iterator->xco]==0){

			map->getPosition(currentV, a, b);
			map->getNeighbor( a, b, iterator->yco, c, d);
			this->currentV=map->getVertex(c,d);
			return iterator->yco;
		    }
		}
		
//		printf("WTF WTF\n");
		return 0;
	    }
	}	
//printf("THERE IS NOW WAY\n");
return 0;
}
*/


Actor* SmartEnemy::duplicate()
{


	return new SmartEnemy(ACTOR_ENEMY);

}


const char* SmartEnemy::getActorId()
{
	return "smartenemy";
}


const char* SmartEnemy::getNetId()
{
	return "jwakeman";
}

void SmartEnemy::updateVertices(GraphMap* map){


	SmartEnemy::numPowerups=0;

	SmartEnemy::numHeroes=0;

	SmartEnemy::numEnemies=0;
 	SmartEnemy::numEdibles=0;
     memset(SmartEnemy::edibles, 0, 1500);
     memset(SmartEnemy::powerups, 0, 1000);
     memset(SmartEnemy::enemies, 0, 500);
     memset(SmartEnemy::heroes, 0, 500);

     int x=0;
     int y=0;

	for(int i=0; i<SmartEnemy::totalActors; i++){

	    if((map->getActorType(i) & (ACTOR_DEAD | ACTOR_HERO))==ACTOR_HERO){

		map->getActorPosition(i, x, y);
		SmartEnemy::heroes[numHeroes]=map->getVertex(x,y);
		SmartEnemy::numHeroes++;		
	    }
	
	    if((map->getActorType(i) & (ACTOR_DEAD | ACTOR_POWERUP))==ACTOR_POWERUP){
		
		map->getActorPosition(i, x, y);	
		SmartEnemy::powerups[numPowerups]=map->getVertex(x,y);
		SmartEnemy::numPowerups++;
	    }

            if((map->getActorType(i) & ( ACTOR_DEAD | ACTOR_ENEMY)) ==ACTOR_ENEMY){
		
		map->getActorPosition(i, x, y);	
		SmartEnemy::enemies[numEnemies]=map->getVertex(x,y);
		SmartEnemy::numEnemies++;
	    }
 
	   if((map->getActorType(i) & ( ACTOR_EATABLE | ACTOR_POWERUP)) !=0){
	      if((map->getActorType(i) & ACTOR_DEAD) !=0){

	      }
	   
	      else{
			map->getActorPosition(i, x, y);
			SmartEnemy::edibles[numEdibles]=map->getVertex(x,y);
			SmartEnemy::numEdibles++;	
              }
	  }

       }
    
return;
}

void SmartEnemy::makeLists(GraphMap* map){
    pair temp;
    int a=0;
    int b=0;
    int c=0;
    int d=0;
    int numNeigh=0;
    d=numNeigh;
	for(int i = 0; i < SmartEnemy::totalVertices; i++){
	
	    map->getPosition(i, a, b);
	    for(int j = 0, numNeigh=map->getNumNeighbors(a, b); j < numNeigh; j++){
		map->getNeighbor( a, b, j, c, d);
		temp.xco=map->getVertex(c,d);
	        temp.yco=j;
		SmartEnemy::arrayOfLists[i].oneMove.push_front(temp);
	    }
	}

	for(int i =0; i < totalVertices; i++){
	    for (list<pair>::const_iterator iterator = SmartEnemy::arrayOfLists[i].oneMove.begin(), end = SmartEnemy::arrayOfLists[i].oneMove.end(); iterator != end; ++iterator) {
    		     
		map->getPosition(iterator->xco, a, b);
		for(int j =0, numNeigh=map->getNumNeighbors(a,b); j < numNeigh; j++){
		    map->getNeighbor(a, b, j, c, d);
		    temp.xco=map->getVertex(c,d);
		    temp.yco=iterator->yco; 
		    SmartEnemy::arrayOfLists[i].twoMoves.push_front(temp);
		}
	    }
	}
	
	for(int i =0; i < totalVertices; i++){
	    for (list<pair>::const_iterator iterator = SmartEnemy::arrayOfLists[i].twoMoves.begin(), end = SmartEnemy::arrayOfLists[i].twoMoves.end(); iterator != end; ++iterator) {
    		     
		map->getPosition(iterator->xco, a, b);
		for(int j =0, numNeigh=map->getNumNeighbors(a,b); j  < numNeigh; j++){
		    map->getNeighbor(a, b, j, c, d);
		    temp.xco=map->getVertex(c,d);
		    temp.yco=iterator->yco; 
		    SmartEnemy::arrayOfLists[i].threeMoves.push_front(temp);
		}
	    }
	}
	
	for(int i =0; i < totalVertices; i++){
	    for (list<pair>::const_iterator iterator = SmartEnemy::arrayOfLists[i].threeMoves.begin(), end = SmartEnemy::arrayOfLists[i].threeMoves.end(); iterator != end; ++iterator) {
    		     
		map->getPosition(iterator->xco, a, b);
		for(int j =0, numNeigh=map->getNumNeighbors(a,b); j  < numNeigh; j++){
		    map->getNeighbor(a, b, j, c, d);
		    temp.xco=map->getVertex(c,d);
		    temp.yco=iterator->yco; 
		    SmartEnemy::arrayOfLists[i].fourMoves.push_front(temp);
		}
	    }
	}
	
	for(int i =0; i < totalVertices; i++){
	    for (list<pair>::const_iterator iterator = SmartEnemy::arrayOfLists[i].fourMoves.begin(), end = SmartEnemy::arrayOfLists[i].fourMoves.end(); iterator != end; ++iterator) {
    		     
		map->getPosition(iterator->xco, a, b);
		for(int j =0, numNeigh=map->getNumNeighbors(a,b); j  < numNeigh; j++){
		    map->getNeighbor(a, b, j, c, d);
		    temp.xco=map->getVertex(c,d);
		    temp.yco=iterator->yco; 
		    SmartEnemy::arrayOfLists[i].fiveMoves.push_front(temp);
		}
	    }
	}
/*for(int i =0; i < totalVertices; i++){
	    for (list<pair>::const_iterator iterator = SmartEnemy::arrayOfLists[i].oneMove.begin(), end = SmartEnemy::arrayOfLists[i].oneMove.end(); iterator != end; ++iterator) {
    		     map->getPosition(iterator->xco, a, b);

		    printf("%3d, %3d AT vertex %i xco=%2i yco=%2i\n", iterator->xco, iterator->yco, i, a, b); 
		}
}
*/

return;
}
   		
int SmartEnemy::findPath(GraphMap* map, int first, int second, int& tempNextMove, int& tempShortestPath){
	
	
	int numNeigh=0;
	int a=0;
	int b=0;
	int c=0;
	int d=0;
	

	int count=0;
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
	/*	   if(mode==1){
 			for(int j= 0; j < this->numEdibles && !done; j++){   
		
		    if(this->edibles[j].xco==c && this->edibles[j].yco==d){
			S[neighborV]=1;
		        p[neighborV]=i;
			last[neighborV]=v;
			done=true;
			Q.push(neighborV);

	            }
		    
		    }
	       }*/
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
	    tempShortestPath=count;
	    return neighborV;
	    
	}

tempNextMove=0;
tempShortestPath=9999;
return this->currentV;
}

list<int> SmartEnemy::makeMinMax(){

list<int> listtoreturn;
int smallest=SmartEnemy::totalVertices;
int biggest=0;

	for(int i =0; i < SmartEnemy::numHeroes; i++){
	    for (list<pair>::const_iterator iterator = SmartEnemy::arrayOfLists[SmartEnemy::heroes[i]].fiveMoves.begin(), end = SmartEnemy::arrayOfLists[SmartEnemy::heroes[i]].fiveMoves.end(); iterator != end; ++iterator) {
    		     
		if(iterator->xco <= smallest){
		    smallest=iterator->xco;
		}
		if(iterator->xco >= biggest){
		    biggest=iterator->xco;
		}
		
	    }
	
	    listtoreturn.push_back(smallest);
	    listtoreturn.push_back(biggest);
	}

 return listtoreturn;
}


