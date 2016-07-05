package cs311.hw7;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.ArrayList;
import java.util.List;

public class CSCoffeeTask implements CoffeeTask {

	public CSCoffeeTask(){
		
	}
	@Override
	public List<Integer> getSortedIngredientLocations() {

		ArrayList<Integer> listInOrder = new ArrayList<Integer>();

		// creates the graph modeling the dependencies
		// creates an instance of CSGraph<S, T>
		CSGraph constrained = new CSGraph(true);

		// adds vertices for each location in list

		constrained.addVertex("1067", null);
		constrained.addVertex("981", null);
		constrained.addVertex("1653", null);
		constrained.addVertex("524", null);
		constrained.addVertex("1864", null);
		constrained.addVertex("1119", null);
		constrained.addVertex("1104", null);
		constrained.addVertex("826", null);

		// adds edges for each constraint

		Object ew = null;

		constrained.addEdge("524", "981", ew);
		constrained.addEdge("1104", "981", ew);
		constrained.addEdge("981", "1067", ew);
		constrained.addEdge("524", "1653", ew);
		constrained.addEdge("1653", "1067", ew);
		constrained.addEdge("1864", "1653", ew);
		constrained.addEdge("1864", "524", ew);
		constrained.addEdge("1119", "524", ew);
		constrained.addEdge("524", "1104", ew);
		constrained.addEdge("1119", "1104", ew);
		constrained.addEdge("826", "1067", ew);

		List<String> backwardsList = constrained.topologicalSort();

		for (int i = (backwardsList.size() - 1); i >= 0; i--) {
			String lab = backwardsList.get(i);
			listInOrder.add(Integer.parseInt(lab));
		}
		return listInOrder;
	}

	@Override
	public List<Integer> getShortestRoute(File amesFile, List<Integer> ingList) {

		List<Integer> combinedList = new ArrayList<Integer>();

		// creates an instance of EM which implements EdgeMeasure
		EdgeMeasure myEdgeMeasure = new EM();

		// creates an instance of CSGraph<S, T>
		CSGraph myG = new CSGraph(false);

		try {

			readFile(amesFile, myG);
		} catch (IOException e) {
			System.out.println("Could not read the input file");
			e.printStackTrace();
		}

		// for each int in the list find the shortest path and add the list
		// returned by each to a combined list

		for (int i = 0; i < (ingList.size() - 1); i++) {

			// get the label of the vertex for each vert index
			Vertex v = (Vertex) myG.vertices.get(ingList.get(i));
			String sourceLabel = v.label;
			Vertex v2 = (Vertex) myG.vertices.get(ingList.get(i + 1));
			String destLabel = v2.label;
			
			//adds every vertex returned by shortest path to the combinedList
			combinedList.addAll(myG.shortestPath(sourceLabel, destLabel,
					myEdgeMeasure));
			// if this is not the last iteration of the for loop remove the
			// repeat vertex
			if (!(i == (ingList.size() - 2))) {
				combinedList.remove((combinedList.size() - 1));
			}
		}

		return combinedList;
	}

	@Override
	public double getMSTCost(File amesFile) {
		double totalCost = 0;
		// creates an instance of EM which implements EdgeMeasure
		EdgeMeasure myEdgeMeasure = new EM();

		// creates an instance of CSGraph<S, T>
		CSGraph myG = new CSGraph(false);

		try {

			// reads the amesFile file.
			readFile(amesFile, myG);
		} catch (IOException e) {
			System.out.println("Could not read the input file");
			e.printStackTrace();
		}

		CSGraph minGraph = (CSGraph) myG.minimumSpanningTree(myEdgeMeasure);

		totalCost = minGraph.getTotalCost(myEdgeMeasure);

		return totalCost;
	}

	private static <S, T> void readFile(File fileName, CSGraph<S, T> g)
			throws IOException {
		// Path filePath = Paths.get(fileName);
		// File inFile = new File(filePath.toString());
		File inFile = fileName;
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
	}

	private static int getAnIntAfterColon(String wholeLine) {
		String[] tokens = wholeLine.split(": ");
		return (Integer) Integer.parseInt(tokens[1]);
	}

}
