/*
 * Smart.hpp
 *
 *  Created on: Mar 30, 2014
 *      Author: JWakeman
 */

#ifndef Smart_HPP_
#define Smart_HPP_

#include "Actor.hpp"
#include "GraphMap.hpp"
#include <list>
#include <iostream>
using namespace std;

namespace jwakeman {
class SmartHero : public Actor
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
	int badge;
	int currentV;
	void updateVertices(GraphMap* map);
	int findPath(GraphMap* map, int first, int second, int &tempNextMove, int& tempShortestPath);
	bool useFast; 
	static int numEdibles;
	static int totalVertices;
	static int totalActors;
	int numMoves;
	int initV;
	static list<int> targets;
	void makeLists(GraphMap* map);
	list<int> makeMinMax();
        static SmartHero::lists arrayOfLists[10000];
	static int numPowerups;
        static int numHeroes;
        static int numEnemies;
        static int powerups[1000];
        static int enemies[500];
        static int heroes[500];
	static int S[10000];
	static int edibles[5000];

	public:
		SmartHero(int type);
		virtual ~SmartHero();
		virtual int selectNeighbor(GraphMap* map, int x, int y);
		virtual Actor* duplicate();
		virtual const char* getActorId();
 		virtual const char* getNetId();
		
					
};
#endif
}