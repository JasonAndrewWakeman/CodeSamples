package pi_future
import scala.concurrent.{ Await, Future }
import scala.concurrent._
import scala.concurrent.duration._
import concurrent.ExecutionContext
import scala.collection.mutable.ArrayBuffer

object Pi extends App {

  /**
   * Approximates PI using the Monte Carlo method.  Demonstrates
   * use of Futures and thread pools.
   */

  private final var numSamples: Integer = 10000000;
  private final var numThreads: Integer = 10;

  // numIterations per thread
  private final var numIterations: Integer = numSamples / numThreads;
  
  new Master().doRun(numIterations, numThreads);

  /* Creates workers to run the Monte Carlo simulation
 * and aggregates the results.
 */
}
class Master {
  def doRun(numIterations: Integer, numWorkers: Integer): Double = {
    var total: Long = 0
    //defines an executin context to be used for 'future' threads
    implicit val ec = ExecutionContext.fromExecutor(new java.util.concurrent.ForkJoinPool(numWorkers))
    //create a list to hold all of our futures
    var futures: ArrayBuffer[Future[Long]] = new ArrayBuffer()
    //used to time execution
    val start: Long = System.currentTimeMillis();
    for (i <- 1 to numWorkers) //starts the computation for each worker thread, which are added to futures immediately and will finish later
    {
      futures += (Future {
        doWork(numIterations)
      });
    }
    //waits for each future to acquire a result for up to 10 seconds for each. adds the result to the total sum of results
    while (futures != Nil) {
      total += Await.result(futures.head, 10 seconds)
      futures = futures.tail
    }
    //evaluates the approximation of pi using the Monte Carlo method and the sum of all circles found by each worker contained in their corresponding future
    val pi: Double = 4.0 * total / numIterations / numWorkers;

    val elapsed: Long = System.currentTimeMillis() - start;

    println("Pi : " + pi);
    println("Time in Milliseconds: " + elapsed);
    return pi
  }

  def doWork(numIterations: Integer): Long =
    {
      println(Thread.currentThread().getName() + " " + numIterations);
      var circleCount: Long = 0;
      val rand = new scala.util.Random()
      for (i <- 1 to numIterations) {

        var x: Double = rand.nextDouble();
        var y: Double = rand.nextDouble();
        if ((x * x + y * y) < 1) circleCount = circleCount + 1;
      }
      return circleCount;
    }

}
