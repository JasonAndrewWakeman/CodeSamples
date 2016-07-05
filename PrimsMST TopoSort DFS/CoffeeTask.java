package cs311.hw7;

import java.io.File;
import java.util.List;

/**
 * A few methods Jim plans on using to fix his coffee problems.
 */
public interface CoffeeTask
{
	/**
	 * You must construct a graph representing the ingredient
	 * dependencies specified in the homework and then use
	 * topological sort to find a valid sorting.
	 * 
	 * The list returned is the list of vertex ids of the locations
	 * of each ingredient in the valid sorting.
	 */
	public List<Integer> getSortedIngredientLocations();
	
	/**
	 * Given a File to the Ames data and an ordering of
	 * ingredient location vertex ids, you are to parse the Ames file
	 * and create a directed graph, then find the shortest route from
	 * your location picking up the ingredients in the order specified,
	 * and then delivering them to Jim's location.
	 * 
	 * You are to use the distance provided in each edge of the Ames
	 * data as the weights of the edges.
	 * 
	 * The list returned is the order of vertex ids visited in the
	 * shortest path starting with your location and ending with
	 * Jim's location.
	 */
	public List<Integer> getShortestRoute(File amesFile, List<Integer> ingList);
	
	/**
	 * Given a File to the Ames data, you are to parse the file
	 * and create an undirected graph, then find a minimum spanning
	 * tree of the city, and return the total cost of the spanning
	 * tree.
	 * 
	 * Use the distance of every edge in the Ames file as the cost
	 * of the edges. The total cost is the sum of all the edge costs
	 * of the edges in the minimum spanning tree.
	 */
	public double getMSTCost(File amesFile);
}
