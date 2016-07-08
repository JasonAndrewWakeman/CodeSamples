/*
 * SH.hpp
 *
 *  Created on: Mar 30, 2014
 *      Author: JWakeman
 */

#ifndef SH_HPP_
#define SH_HPP_

#include "Actor.hpp"
#include "GraphMap.hpp"


class SimpleHero : public Actor
{
	protected:
	     struct pair
             {

    		int xco;
    		int yco;
    		bool unreachable;
    				
	     };

//	struct verData
//	{
  //  	     pair pr; 
  //  	     bool visited;
//	     int verType;
//	     int index;		
//	};

	pair edibles[1500];
//	verData allVer[10000];
	pair enemypairs[100];
	pair heropair;
	int mode;
	
	void updateVertices(GraphMap* map);
	int findPath(GraphMap* map, pair first, pair second, int& tempNextMove, int mode);
	bool useFast; 
	int numEdibles;
	int totalVertices;
	int totalActors;
	int firstCall;
	int numMoves;
	

	public:
		SimpleHero(int type);
		virtual ~SimpleHero();
		virtual int selectNeighbor(GraphMap* map, int x, int y);
		virtual Actor* duplicate();
		virtual const char* getActorId();
		virtual const char* getNetId();
					
};
#endif