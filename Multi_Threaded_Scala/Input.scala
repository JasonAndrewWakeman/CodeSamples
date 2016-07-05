package Multi_Threaded_Scala
import akka.actor.Actor
import akka.actor.ActorRef

//Constructed Input Component with reference to Client Actor
class Input(clientRef: ActorRef) extends Actor {

  def receive = {
    case Repeat =>
      println("Enter id number to look up, 'd' to display list, 'q' to quit")
      val in = scala.io.StdIn.readLine()
      clientRef ! Request(IDGenerator.next, in)
  }

  //uses java's atomic long to provide unique ids
  object IDGenerator {
    private val n = new java.util.concurrent.atomic.AtomicLong
 
    def next = n.getAndIncrement()
  }
}
