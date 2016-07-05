package cs311.hw7;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.ArrayList;
import java.util.Comparator;
import java.util.List;
import java.util.Scanner;

public class Main {

	@SuppressWarnings("unchecked")
	public static <S, T extends EdgeMeasure<? super T>> void main(String[] args) {

		// creates an instance of EM which implements EdgeMeasure
		EdgeMeasure<T> myEdgeMeasure = new EM<T>();

		// creates an instance of CSGraph<S, T>
		CSGraph<S, T> myG = new CSGraph<S, T>(false);
		Path filePath = Paths.get("ames2.txt");
		File inFile = new File(filePath.toString());

		// creates the minimum spanning tree of myG
//		CSGraph<S, T> minGraph = (CSGraph<S, T>) myG
//				.minimumSpanningTree(myEdgeMeasure);
		
		CSCoffeeTask task = new CSCoffeeTask();
		
		List<Integer> ingList = task.getSortedIngredientLocations();
		System.out.println(ingList); 
		List<Integer> path = task.getShortestRoute(inFile, ingList);
		System.out.println(path);
		System.out.println(task.getMSTCost(inFile));
	
	

		/*
		 * for (int i = 0; i < minGraph.allEdges.size(); i++){ Edge e =
		 * minGraph.allEdges.get(i); Double dist = new
		 * Double(e.distance.toString()); minTreeWeight = minTreeWeight +
		 * (double) dist; }
		 */
	//	System.out.println(minGraph.getTotalCost(myEdgeMeasure));
		// System.out.println(computeWeight(myG, minGraph, myEdgeMeasure));
	}

	private static <S, T> void readFile(String fileName, CSGraph<S, T> g)
			throws IOException {
		Path filePath = Paths.get(fileName);
		File inFile = new File(filePath.toString());

		FileInputStream fis = new FileInputStream(inFile);

		// Construct BufferedReader from InputStreamReader
		BufferedReader br = new BufferedReader(new InputStreamReader(fis));
		String line = br.readLine();
		int listedNumVert = getAnIntAfterColon(line);

		for (int i = 0; i < listedNumVert; i++) {
			// String[] tokens = br.readLine().split(",");//splits line by comma
			// delimeter.
			// sets the coordinates of each vertex. ToDO
			line = br.readLine();
			String[] tokens = line.split(",");
			g.addVertex(tokens[0], null);

		}
		
		String edgesLine = br.readLine();
		
		int listedEdges = getAnIntAfterColon(edgesLine);

		for (int i = 0; i < listedEdges; i++) {
			line = br.readLine();
			String[] tokens = line.split(",");

			g.addEdge(tokens[0], tokens[1], (T) tokens[2]);

		}
		br.close();

		System.out.println(g.getNeighbors("2"));
		System.out.println(g.numEdges);

		/*
		 * for(int i = 0; i <10; i ++){ Vertex<T,S> v2 = (Vertex<T, S>)
		 * g.vertices.get(i); for (int j = 0; j < v2.edges.size(); j++){
		 * System.out.println("index i =" + i + ", " + v2.edges.get(j).source +
		 * ", " + v2.edges.get(j).dest + ", " + v2.edges.get(j).distance); } }
		 */

	}

	private static int getAnIntAfterColon(String wholeLine) {
		String[] tokens = wholeLine.split(": ");
		return (Integer) Integer.parseInt(tokens[1]);
	}

	private static <S, T> double computeWeight(CSGraph g, List<String> path,
			EdgeMeasure<T> m) {
		
		double totalWeight = 0;
		for (int i = 0; i < (path.size() - 1); i++) {
			String sourceLabel = path.get(i);
			String destLabel = path.get(i + 1);
			@SuppressWarnings("unchecked")
			Vertex<S, T> v = (Vertex<S,T>) g.vertices.get(g.findIndex(sourceLabel));
			for (int j = 0; j < v.edges.size(); j++) {
				Edge<T> e = (Edge<T>) v.edges.get(j);
				if (e.dest.compareTo(destLabel) == 0) {
					totalWeight = totalWeight + m.getCost(e.distance);
				}

			}
		}
		return totalWeight;

	}
}
