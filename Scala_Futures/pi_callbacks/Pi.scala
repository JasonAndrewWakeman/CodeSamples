package pi_callbacks
import scala.concurrent.Future
import scala.util.{ Success, Failure }
import concurrent.ExecutionContext

object Pi extends App {

  /**
   * Approximates PI using the Monte Carlo method.  Demonstrates
   * use of Callables, Futures, and thread pools.
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
    //a java CountDownLatch used to safely synchronize concurrently finishing futures' callbacks
    val cdLatch = new java.util.concurrent.CountDownLatch(numWorkers)
    //a java read/write lock
    val lock: java.util.concurrent.locks.ReentrantReadWriteLock = new java.util.concurrent.locks.ReentrantReadWriteLock()
    var total: Long = 0
    //defines an execution context to be used for 'future' threads
    implicit val ec = ExecutionContext.fromExecutor(new java.util.concurrent.ForkJoinPool(numWorkers))
    //used to time execution
    val start: Long = System.currentTimeMillis();
    for (i <- 1 to numWorkers) //starts the computation for each worker thread immediately and will countdown the Latch on completion.
    {
      val fut = Future {
        doWork(numIterations)
      }
      //creates a callback which will update the total with this futures result and count down the latch asynchronously
      fut.onComplete {
        case Success(value) =>
          //update total within a lock
          lock.writeLock().lock()
            total = total + value
          lock.writeLock().unlock()
          cdLatch.countDown()
        case Failure(value) =>
          println("Result is undetermined")
          cdLatch.countDown()
      }
    }
    //blocks until all of numWorkers have completed their futures and counted down the latch
    cdLatch.await()
    //evaluates the approximation of pi using the Monte Carlo method and the sum of all circles found by each worker contained in their corresponding future
    //unnecessarily accesses total within the lock even though we know no more threads will be writing to it. 
    lock.readLock().lock()
      val pi: Double = 4.0 * total / numIterations / numWorkers;
    lock.readLock().unlock()

    val elapsed: Long = System.currentTimeMillis() - start;

    println("Pi : " + pi);
    println("Time in Milliseconds: " + elapsed);
    return pi
  }

  //the asynchronous work performed to provide a future circleCount for numIterations from a background thread
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

