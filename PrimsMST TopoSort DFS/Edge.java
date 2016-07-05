/**
 * 
 */
package cs311.hw7;

/**
 * @author apaulo
 *
 */
public class Edge<T> {
	
	String source;
	String dest;
	T distance;
	public Edge(String Source, String Dest, T Distance){
		this.source = Source;
		this.dest = Dest;
		this.distance = Distance;
	}

}
