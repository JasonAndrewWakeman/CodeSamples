package cs311.hw7;

import java.util.ArrayList;

public class Vertex<S,T> {

	S data;
	String label;
	int state; 
	int ind;
	ArrayList<Edge<T>> edges;
	int parent;
	int id; 
	  
	public Vertex(S data, String lab) {
		this.data = data;
		this.label = lab;
		this.state = 0;
		this.ind = -1; 
		this.parent = -1; ;
		edges = new ArrayList<Edge<T>>(); 
		
	}

}
