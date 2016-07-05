package cs311.hw4;

import java.util.Comparator;

public interface ISort<T>
{
	/**
	 * Sorts the subarray "in place" starting at index
	 * "start" and ending with index "end" (both inclusive).
	 * 
	 * @param arr   The array to be sorted
	 * @param start The starting index (inclusive) of the
	 *              subarray to be sorted
	 * @param end   The ending index (inclusive) of the
	 *              subarray to be sorted
	 * @param comp  The means of comparing two generic T objects
	 * @throws IllegalArgumentException If any of the arguments
	 *                                  are invalid
	 */
	public void sort(T[] arr, int start, int end, Comparator<T> comp);
}
