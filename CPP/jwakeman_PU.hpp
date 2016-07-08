/*
 * PU.hpp
 *
 *  Created on: Mar 30, 2014
 *      Author: JWakeman
 */


#ifndef PU_HPP_
#define PU_HPP_

#include "Actor.hpp"
#include "GraphMap.hpp"
#include <list>
#include <iostream>
using namespace std;

namespace jwakeman {


class SmartPowerup : public Actor
{
	protected:
	     struct pair
             {

    		int xco;
    		int yco;
    		bool unreachable;
    				
	     };

	struct lists 
	{
	    list<pair> oneMove;
	    list<pair> twoMoves;
	    list<pair> threeMoves;
	    list<pair> fourMoves;
	    list<pair> fiveMoves;
	};


	int mode;
	int firstMove;
	int initV;
	int badge;
	int currentV;
	void updateVertices(GraphMap* map);
	int findPath(GraphMap* map, int first, int second, int& tempNextMove, int& tempShortestPath);
	bool useFast; 
	static int numEdibles;
	static int totalVertices;
	static int totalActors;
	int numMoves;
	static list<int> targets;
	void makeLists(GraphMap* map);
	list<int> makeMinMax();
        static SmartPowerup::lists arrayOfLists[10000];
	static int numPowerups;
        static int numHeroes;
        static int numEnemies;
        static int powerups[1000];
        static int enemies[500];
        static int heroes[500];
	static int S[10000];
	static int edibles[5000];


	public:
		SmartPowerup(int type);
		virtual ~SmartPowerup();
		virtual int selectNeighbor(GraphMap* map, int x, int y);
		virtual Actor* duplicate();
		virtual const char* getActorId();
 		virtual const char* getNetId();
		
					
};
#endif
}