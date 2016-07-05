/**
 * 
 */
package cs311.hw7;

import java.util.ArrayList;
import java.util.Collection;
import java.util.List;
import java.util.PriorityQueue;
import java.util.Queue;
import java.util.Stack;

/**
 * @author Jason Wakeman
 *
 */
public class CSGraph<S, T> implements Graph<S, T> {
	boolean isDirected;
	int numVertices;
	int numEdges;
	Stack updatingStack = new Stack();
	Stack processedVertices = new Stack();
	ArrayList<Edge<T>> allEdges = new ArrayList<Edge<T>>();
	ArrayList<Vertex<S, T>> vertices = new ArrayList<Vertex<S, T>>();


	// helper function which returns the index of a vertex by its label in vertices arraylist .
	int findIndex(String sourceLabel) {
		for (int i = 0; i < this.vertices.size(); i++) {
			if ((this.vertices.get(i)).label.compareTo(sourceLabel) == 0) {
				return i;
			}
		}
		return -1;
	}

	
	/** constructor initializes counters and sets isDirected
	 * @param b true if the graph is to be considered directed
	 * 
	 */
	public CSGraph(boolean b) {
		this.numVertices = 0;
		this.numEdges = 0;
		this.isDirected = b;
	}

	@Override
	public boolean isDirected() {
		return this.isDirected;
	}

	@Override
	public void addVertex(String vertexLabel, S vertexData) {
		Vertex<S, T> v = new Vertex<S, T>(vertexData, vertexLabel);
		v.ind = this.numVertices;
		this.vertices.add(v); 
		this.numVertices++;
		return;
	}

	@Override
	public void removeVertex(String vertexLabel) {

		this.vertices.remove(findIndex(vertexLabel));
		this.numVertices--;
		return;

	}

	@Override
	public void addEdge(String sourceLabel, String targetLabel, T edgeData) {

		Edge<T> e = new Edge<T>(sourceLabel, targetLabel, edgeData);

		// find the vertex with sourceLabel and add an edge to its list of
		// edges.
		int sourceIndex = findIndex(sourceLabel);
		this.vertices.get(sourceIndex).edges.add(e);
		this.allEdges.add(e);
		this.numEdges++;

		// if undirected graph, add another edge using target as source and
		// source as target.
		if (!(this.isDirected())) {

			Edge<T> e2 = new Edge<T>(targetLabel, sourceLabel, edgeData);

			// find the vertex with target Label and add an edge to its list of
			// edges.
			int targetIndex = findIndex(targetLabel);
			this.vertices.get(targetIndex).edges.add(e2);
			this.allEdges.add(e);
			this.numEdges++;
		}

	}

	@Override
	public T getEdgeData(String sourceLabel, String targetLabel) {
		int sourceIndex = findIndex(sourceLabel);
		Vertex source = this.vertices.get(sourceIndex);
		for (int i = 0; i < source.edges.size(); i++) {
			Edge<T> e = (Edge<T>) source.edges.get(i);
			if (e.dest.equals(targetLabel)) {
				return (T) e.distance;
			}
		}

		return null;
	}

	@Override
	public S getVertexData(String label) {
		int index = findIndex(label);
		return this.vertices.get(index).data;
	}

	@Override
	public int getNumVertices() {
		return this.numVertices;
	}

	@Override
	public int getNumEdges() {
		return this.numEdges;
	}

	@Override
	public Collection<String> getVertices() {
		ArrayList<String> arr = new ArrayList<String>();
		for (int i = 0; i < this.numVertices; i++) {
			arr.add(this.vertices.get(i).label);
		}

		return arr;
	}

	@Override
	public Collection<String> getNeighbors(String label) {
		ArrayList<String> arr = new ArrayList<String>();
		int sourceIndex = findIndex(label);
		for (int i = 0; i < this.vertices.get(sourceIndex).edges.size(); i++) {
			arr.add(this.vertices.get(sourceIndex).edges.get(i).dest);
		}

		return arr;
	}

	public int depthFirstTraversal(Graph<S, T> g, int startV) {

		if(this.vertices == null || this.getNumVertices() < 1){
			return -1;
		}
		// create a Stack and push the starting vertex onto it.

		this.vertices.get(startV).state = 1;
		this.updatingStack.push(startV);

		//

		// Traverse the graph by popping each element until there are none left.
		while (!this.updatingStack.isEmpty()) {
			
		

			// set inFocus to be the vertex on top of the queue.
			int indVFocus = (int) this.updatingStack.pop();

			 if (vertices.get(indVFocus).state == 2){ //then we know this vertex has already been processed so we have encountered a cycle and must return null
				 
				 return -1;
			 
			 }

			// Loop through every edge in inFocus's list of edges.
			for (int i = 0; i < this.vertices.get(indVFocus).edges.size(); i++) {

				// set edgeInFocus to be the edge at index i in the vertex in
				// focus's list of edges.
				Edge edgeInFocus = this.vertices.get(indVFocus).edges.get(i);
				// set destInFocus to be the edgeInFocus's destination vertex's
				// index
				int destIndex = this.findIndex(edgeInFocus.dest);
				// checks to see if the state of the vertex destIndex is
				// undiscovered
				if (this.vertices.get(destIndex).state == 0) {
					this.updatingStack.push(destIndex);
					this.vertices.get(destIndex).parent = indVFocus;
					this.vertices.get(destIndex).state = 1;
				}
			}

			// add the current vertex in focus to the Stack of Processed
			// Vertices (the order they are added can be reversed to find a
			// topological sort.)
			 vertices.get(indVFocus).state = 2;

			this.processedVertices.push(indVFocus);

		}
		return 0;
	}

	@Override
	public List<String> topologicalSort() {

		if(!this.isDirected()){
			//if not directed return null
			return null;
		}
		// Clear the state of each vertex in vertices.
		for (Vertex v : this.vertices) {
			v.parent = -1;
			v.state = 0;
		}
		ArrayList<String> listToReturn = new ArrayList<String>();

		Graph<S, T> gPrime = null;
		Stack<String> reverseOfProcessed = new Stack<String>();
		depthFirstTraversal(gPrime, 0); 
		// pop each index of vertex from the stack in the reverse order it was
		// processed and add its associated label to listToReturn
		while (!(this.processedVertices.isEmpty())) {
			reverseOfProcessed
					.add(this.vertices.get((int) this.processedVertices.pop()).label);

		}

		for (int i = 0; i < this.numVertices; i++) {
			if (this.vertices.get(i).state < 1) {
				if (depthFirstTraversal(gPrime, i) < 0){ //then there was a cycle and we should return null
					return null;
				}

				// pop each index of vertex from the stack in the reverse order
				// it was processed and add its associated label to listToReturn
				while (!(this.processedVertices.isEmpty())) {
					reverseOfProcessed.add(this.vertices.get((int) this.processedVertices
							.pop()).label);

				}

			}
		}

		while (!reverseOfProcessed.isEmpty()) {
			listToReturn.add(reverseOfProcessed.pop());
		}

		return listToReturn;
	}

	@Override
	public List<String> shortestPath(String startLabel, String destLabel,
			EdgeMeasure<T> measure) {

		// if (!this.isDirected()){
		// return null;
		// }

		// the list of strings to return.
		List<String> listOfVerticesInPath = new ArrayList<String>();

		if (startLabel.compareTo(destLabel) == 0) {// then we just return the
													// string of either vertex.
													// because we are already
													// there.
			listOfVerticesInPath.add(destLabel);
			return listOfVerticesInPath;
		}

		// creates an array indexed by index of destination vertex holding the
		// distance from the start vertex to the vertex indexed.
		double distances[] = new double[getNumVertices()];

		// creates a list of vertices for which the distance has been found.
		// ArrayList<Vertex<S, T>> doneFindingFor = new
		// ArrayList<Vertex<S,T>>();

		// creates a queue of vertices which has each vertex removed from it as
		// it is processed.
		PriorityQueue<Integer> q = new PriorityQueue<Integer>();

		// creates an array of immediate predecessors to the vertex associated
		// with each index. (EG if vertex with ind 5's immediate predecessor in
		// the shortest path from start to itself is 3, then immediatePred[5] =
		// 3.
		int immediatePred[] = new int[getNumVertices()];

		// for all x in vertices: set distance to x equal to the biggest Long
		// and add each to 'q';
		for (int i = 0; i < distances.length; i++) {
			distances[i] = Double.MAX_VALUE;
			immediatePred[i] = -1;
			// q.add(i);
		}

		// set the distance to start vertex equal to 0;
		int startIndex = this.findIndex(startLabel);
		distances[startIndex] = 0;
		immediatePred[startIndex] = startIndex;

		q.add(startIndex);

		while (!(q.isEmpty())) {

			// pop a vertex index off of q.
			Vertex vInFocus = this.vertices.get(q.remove());

			// doneFindingFor.add(vInFocus);

			// for each edge of vInFocus
			for (int i = 0; i < vInFocus.edges.size(); i++) {

				Edge<T> eInFocus = (Edge<T>) vInFocus.edges.get(i);

				// set destInFocus to be the dest vertex of the edgeInFocus
				Vertex<S, T> destInFocus = this.vertices
						.get(findIndex((eInFocus.dest)));

				// check to see if the new edge yields a shorter distance than
				// what was previously found.
				if (distances[destInFocus.ind] > distances[vInFocus.ind]
						+ measure.getCost(eInFocus.distance)) {
					// only add this vertex if the new distance to it is less
					// than the old
					// adds each of the vertexInFocus's neighboring vertices to
					// the queue
					q.add(destInFocus.ind);
					// now we know that we have a shorter path to destInFocus,
					// so we update the distances array and the way we got
					// there.
					distances[destInFocus.ind] = (long) (distances[vInFocus.ind] + measure
							.getCost(eInFocus.distance));
					// update the pred pointer of the current destinationInFocus
					// vertex.
					immediatePred[destInFocus.ind] = vInFocus.ind;
				}
			}// end the for loop of each edge in vInFocus

		}// q is now empty meaning we have searched every edge of every vertex
			// in Vertices

		// if the immediate predecesser to the destination label was never set
		// then we know there was no path from start to destination so we return
		// null;
		if (immediatePred[findIndex(destLabel)] == -1) {
			return null;
		}

		// we know backtrack through the predecessors starting at destination
		// and add each vertex along the path to the string to return.
		String nextPred = this.vertices.get(immediatePred[findIndex(destLabel)]).label;

		listOfVerticesInPath.add(destLabel); // adds the final destination label
												// first

		// while the nextPred does not equal the startVertex label keep adding
		// vertices to list
		while (nextPred.compareTo(startLabel) != 0) {
			listOfVerticesInPath.add(nextPred); // adds the immediate
												// predecessor to the last known
												// predecessor.
			nextPred = this.vertices.get(immediatePred[findIndex(nextPred)]).label;
		}

		// now we know that nextPred is the start label so we add it to the list
		listOfVerticesInPath.add(nextPred); // adds the immediate predecessor to
											// the last known predecessor.
	
		List<String> listToReturn = new ArrayList<String>();
		for (int i = listOfVerticesInPath.size(); i > 0; i--) {
			listToReturn.add(listOfVerticesInPath.get(i - 1));
		}

		return listToReturn;

	}

	@Override
	public Graph<S, T> minimumSpanningTree(EdgeMeasure<T> measure) {
		// Prim-MST(G)
		CSGraph<S, T> tPrim = new CSGraph<S, T>(false);
		ArrayList<Vertex> nonTree = new ArrayList<Vertex>();
		ArrayList<Vertex> tree = new ArrayList<Vertex>();

		for (Vertex v : this.vertices) {
			nonTree.add(v);
			v.state = 0;// state will be set to 1 when put into tPRIM
		}

		// Select an arbitrary vertex s to start the tree from.
		if (this.vertices.size() > 42) {
			tPrim.addVertex(this.vertices.get(42).label, this.vertices.get(42).data);
			nonTree.remove(this.vertices.get(42));
			tree.add(this.vertices.get(42));
			this.vertices.get(42).state = 1;
		} else {
			tPrim.addVertex(this.vertices.get(0).label, this.vertices.get(0).data);
			nonTree.remove(this.vertices.get(0));
			tree.add(this.vertices.get(0));
			this.vertices.get(0).state = 1;
		}

		// While (there are still nontree vertices)
		while (!(nonTree.isEmpty())) {

			// TODO have it make sure every vertex has been added to the tree.

			// for each Vertex in tree check the weights between it and it's
			// neighbors and if its neighbor is not in tree keep track of the
			// min
			// after all of these checks add the minimum

			double minimumSoFar = Double.MAX_VALUE;
			Edge<T> temp = new Edge<T>(null, null, null);

			// Select the edge of minimum weight between a tree and nontree
			// vertex

			for (Vertex v : tree) {

				for (Edge e : (ArrayList<Edge>) v.edges) {
					// if the destination of this edge is already in tPrim then
					// ignore.
	//				int index = findIndex(e.dest); 
					if (this.vertices.get(Integer.parseInt(e.dest)).state == 1) {
						// then we know it is in tree and we do not care about
						// this edge.
					} else {
						// check to see if this edge is less than any other edge
						// candidate and if so store it as edgeToAdd
						if ((double) measure.getCost((T) e.distance) < minimumSoFar) {
							temp = e;
							minimumSoFar = (double) measure
									.getCost((T) e.distance);

						}

					}

				}
			}
			if (temp.dest == null) {
				System.out.println("You should not be here");
				;
			} else {
				// now we have checked every edge leaving every vertex in tree
				// and have determined the one with minCost is now temp
				// so we add it to tPrim(along with the destVertex) and add its
				// dest to tree (and remove from nonTree) and change the state
				// of the newly added Vertex to 1.
				tPrim.addVertex(temp.dest, this.getVertexData(temp.dest));
				tPrim.addEdge(temp.source, temp.dest, temp.distance);
				tree.add(this.vertices.get(Integer.parseInt(temp.dest)));
				nonTree.remove(this.vertices.get(Integer.parseInt(temp.dest)));
				this.vertices.get(Integer.parseInt(temp.dest)).state = 1;
			}

		}


		return tPrim;
	}

	@Override
	public double getTotalCost(EdgeMeasure<T> measure) {

		double total = 0;
		for (int i = 0; i < allEdges.size(); i++) {
			total = total + measure.getCost(allEdges.get(i).distance);
		}

		if (this.isDirected()) {
			return total;
		} else // it is notdirected so there were twice as many edges in model
				// graph as real life version so divide by two.
		{
			return (total / 2);
		}
	}

	/*
	 * public void addVertex(Vertex<S,T> v) { vertices.add(v); return; }
	 */
}
