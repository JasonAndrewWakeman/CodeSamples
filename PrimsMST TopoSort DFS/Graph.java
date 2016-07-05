package cs311.hw7;

import java.util.Collection;
import java.util.List;

/**
 * A simple generic graph data structure in which every vertex
 * in the graph is identified by a String label. This graph also
 * supports attaching an arbitrary data object to each vertex and
 * edge. 
 *
 * @param <S>  The type of data stored in every VERTEX.
 * 
 * @param <T>  The type of data stored in every EDGE.
 */
public interface Graph<S,T>
{
	/**
	 * If this returns true, the graph is a directed
	 * graph. If false, it is undirected.
	 */
	public boolean isDirected();
	/**
	 * Adds a new vertex to the graph that is identified by the
	 * label and has the given vertex data attached to it.
	 * 
	 * @param vertexLabel  The identifying label of the graph.
	 * 
	 * @param vertexData   The data attached to the vertex.
	 */
	public void addVertex(String vertexLabel, S vertexData);
	
	/**
	 * Removes the vertex and all edges associated with it
	 * from the graph.
	 * 
	 * @param vertexLabel The vertex to be removed.
	 */
	public void removeVertex(String vertexLabel);
	
	/**
	 * Adds an edge to the graph from the source vertex to the
	 * target vertex. The edge also has the given data stored
	 * in it.
	 * 
	 * If the graph is undirected, both vertices become
	 * neighbors of one another. If it is directed, the target
	 * vertex is added as a neighbor to source.
	 * 
	 * @param sourceLabel  The label of the source vertex of
	 *                     the edge.
	 *                     
	 * @param targetLabel  The label of the target vertex of
	 *                     the edge.
	 *                     
	 * @param edgeData     The data attached to the edge.
	 */
	public void addEdge(String sourceLabel, String targetLabel, T edgeData);

	/**
	 * Returns the edge data associated with this edge.
	 * 
	 * @param sourceLabel  The source vertex of the edge.
	 * @param targetLabel  The target vertex of the edge.
	 */
	public T getEdgeData(String sourceLabel, String targetLabel);

	/**
	 * Returns the vertex data associated with this vertex.
	 * 
	 * @param label  The label of the vertex.
	 */
	public S getVertexData(String label);

	/**
	 * Returns the number of vertices in the graph.
	 */
	public int getNumVertices();

	/**
	 * Returns the number of edges in the graph.
	 */
	public int getNumEdges();
	
	/**
	 * Returns a collection of the labels of all the vertices
	 * in the graph.
	 */
	public Collection<String> getVertices();
	
	/**
	 * Returns a collection of all the adjacent vertices of the
	 * given vertex.
	 * 
	 * @param label  The label of the vertex.
	 */
	public Collection<String> getNeighbors(String label);
	
	/**
	 * Returns a valid topological sort if the graph is a
	 * directed acyclic graph and returns null otherwise.
	 */
	public List<String> topologicalSort();
	
	/**
	 * Returns the shortest path from the start vertex to
	 * the destination vertex where "short" is defined by
	 * the edge measure object. The path is a list of the
	 * vertex labels in the path starting with the start
	 * vertex and ending with the end vertex.
	 * 
	 * @param startLabel  Starting vertex label.
	 * @param destLabel   Destination vertex label.
	 * @param measure     Measure that defines the weight
	 *                    of every edge in the graph.
	 *                    
	 * @return The shortest path from start to destination.
	 */
	public List<String> shortestPath(String startLabel, String destLabel, EdgeMeasure<T> measure);
	
	/**
	 * Returns a minimum spanning tree for the graph with
	 * respect to the edge measure object. This tree is returned
	 * as a undirected graph.
	 * 
	 * @param Measure that defines the weight of every edge
	 *        in the graph.
	 */
	public Graph<S,T> minimumSpanningTree(EdgeMeasure<T> measure);
	
	/**
	 * Computes the total cost of the graph. The total cost is
	 * the sum of the costs of every edge in the graph.
	 * 
	 * @param measure The measure for how to determine the cost
	 *                of an edge.
	 */
	public double getTotalCost(EdgeMeasure<T> measure);
}