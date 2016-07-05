package cs311.hw4;

import java.io.BufferedWriter;
import java.io.FileOutputStream;
import java.io.FileWriter;
import java.io.IOException;
import java.io.OutputStreamWriter;
import java.io.PrintWriter;
import java.io.Writer;
import java.util.Comparator;
import java.util.List;

public class InsertionSort<T> implements ISort<T> {

	// string containing runtime of run
//	private String runTime;

	InsertionSort() {

	}

	@Override
	public void sort(T[] arr, int start, int end, Comparator<T> comp)
			throws IllegalArgumentException {

		if (arr == null || comp == null) {
			throw new IllegalArgumentException("either arr or comp is null");
		}
		for (int i = 0; i < arr.length; i ++){
			if (arr[i] == null){
				throw new IllegalArgumentException("the array has a null element at index: " + i);
			}
			
		}

		if (!(Comparable.class.isAssignableFrom(arr[0].getClass()))) {
			throw new IllegalArgumentException(
					"this array is not an array of a type of Comparable Class");
		}

		if (start < 0 || start >= arr.length) {
			throw new IllegalArgumentException(
					"start is not an index within the array");
		}
		if (end < 0 || end >= arr.length) {
			throw new IllegalArgumentException(
					"end is not an index within the array");
		}

		if (end < start) {
			throw new IllegalArgumentException(
					"end is less than start, and that simply doesn't work");
		}

		// the index (inclusive) to start sorting
		int originalStart = start;

		// sets variable to be time at start of algorithm
		long startT = System.currentTimeMillis();

		// runs helper function which recursively sorts array
		sortR(arr, start, end, originalStart, startT, comp);

		// basecase of sortR was reached indicating the array is sorted so we
		// print to a file the runtime for the run
	/*	try {
			PrintWriter out = new PrintWriter(new BufferedWriter(
					new FileWriter("src//prog6000Worst.txt", true)));
			out.println(runTime);
			out.close();
		} catch (IOException e) {

			System.err.println("Error writing the file : ");

			e.printStackTrace();

		}
*/
		return;
	}

	@SuppressWarnings("hiding")
	<T> T[] sortR(T[] arr2, int start, int end, int originalStart, long startT,
			Comparator<T> comp) {

		// basecase is reached indicating array is sorted, record the running
		// time
		if (start == end) {

	//		long endT = System.currentTimeMillis();

	//		int runTimeInt = (int) (endT - startT);
			
	//		this.runTime = "1000:" + Integer.toString(runTimeInt);

			return arr2;
		}
		// checks to see if the upcoming element is in order, if it is call sort
		// on the next element.
		else if (comp.compare(arr2[start], arr2[start + 1]) < 0) {

			sortR(arr2, start + 1, end, originalStart, startT, comp);
			return arr2;
		}
		// the next element is out of order and must be inserted in the correct
		// position
		else {

			insert(arr2[start + 1], arr2, start, originalStart, comp);
			sortR(arr2, start + 1, end, originalStart, startT, comp);
			return arr2;
		}
	}

	// takes in the element which needs to be inserted, the array to insert it
	// into, the index of the element adjacent (immediately previous) to it, and
	// the index of the first element of the subarray to be sorted
	@SuppressWarnings({ "hiding" })
	<T> void insert(T leapFrogger, T[] arr2, int start, int originalStart,
			Comparator<T> comp) {

		// variable keeping track of how many places the leapfrogger needs to
		// leap over to get to where it wants to be
		int count = 0;
		// variable keeps track of whether or not the correct index was found
		// during checking and the leapfrog maneuver was initiated, or if the
		// leapfrogger needs to leap to the beginning of the subarray
		boolean alreadyLeapFrogged = false;

		// begins at the index immediately adjacent to the leapfrogger and goes
		// back to beginning of subarray checking for if the element is greater
		// or less than the leapfrogger and incrementing the count for every
		// element that must be leapfrogged
		for (int i = start; i >= originalStart; i--) {
			if (comp.compare(arr2[i], (leapFrogger)) < 0) {
				// we found the element immediately to the left of where the
				// leapfrogger needs to jump to
				// moves every element that must be leaped forward one index
				reverseLeapFrog(count, arr2, start);
				// sets the leapfroggers new index to contain the leapfrogger
				arr2[start - count + 1] = leapFrogger;
				// we performed the leap
				alreadyLeapFrogged = true;
				// we do not need to keep looking
				break;
			}
			// else we did not find the element and must look to the left one
			// more and increment the count
			count++;
		}
		// we know that temp is smaller than everything else and it needs to
		// reverseLeapFrog all if we have not already leapfrogged.

		if (alreadyLeapFrogged == false) {
			reverseLeapFrog(count, arr2, start);
			arr2[start - count + 1] = leapFrogger;
		}
	}

	@SuppressWarnings("hiding")
	// moves every element forward one place in the array arr up to count which
	// is how many indices in between the leapfrogger's current position and
	// where it needs to go
	<T> void reverseLeapFrog(int count, T[] arr2, int start) {

		for (int i = 0; i < count; i++) {
			arr2[start - i + 1] = arr2[start - i];

		}
		// System.out.println("right before returning from reverse leap frog " +
		// arr[0] + " " + arr[1] + " " + arr[2] + " " + arr[3] + " " + arr[4]);

	}

	public static void writeToFile(List<String> runTimes, String filePath) {

		Writer writer = null;

		try {

		

			writer = new BufferedWriter(new OutputStreamWriter(

			new FileOutputStream(filePath), "utf-8"));

			for (String line : runTimes) {

				@SuppressWarnings({ "unused", "resource" })
				PrintWriter out = new PrintWriter(new BufferedWriter(
						new FileWriter(line, true)));

				line += System.getProperty("line.separator");

				writer.write(line);

			}

		} catch (IOException e) {

		} finally {
			if (writer != null) {

				try {

					writer.close();

				} catch (IOException e) {

					System.err
							.println("the file is not null but still can't be closed: ");

					e.printStackTrace();

				}

			}

		}

	}

}