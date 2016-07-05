package cs311.hw7;
/**
 * An object that measures the cost of an edge in the
 * graph.
 * 
 * Similar to how in the first programming project you
 * used the Comparitor interface to compare objects of
 * an arbitrary type, this interface turns an arbitrary
 * edge data object into a numeric "cost" that can be
 * used in calculating shortest paths and spanning trees.
 * 
 * For instance, your edge data might include lots of
 * information (street names, distances, speed limits,
 * etc.). This interface allows you to transform that
 * object with all that information into a "cost". This
 * cost might be the length of the street, or the expected
 * time in minutes to traverse the street. The possibilities
 * are endless!
 *
 * @param <T>  The type of edge data stored in the graph.
 */
public interface EdgeMeasure<T>
{
	/**
	 * Computes the cost (sometimes referred to as the
	 * weight) of the edge.
	 */
	public double getCost(T edgeData);
}