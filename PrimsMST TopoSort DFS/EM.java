package cs311.hw7;

public class EM<T> implements EdgeMeasure<T> {

	@Override
	public double getCost(T edgeData) {
		double dubToReturn = new Double(edgeData.toString());
		return dubToReturn; 
	}

}
