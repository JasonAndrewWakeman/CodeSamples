package Multi_Threaded_Scala
import akka.actor.Actor
import akka.actor.ActorRef
import scala.collection.mutable.ArrayBuffer
import scala.collection.mutable.HashMap

//Component Representing an abstraction of a database takes in a proxyWorker actor known as worker
class Proxy(worker: ActorRef) extends Actor {

   /**
   * Maps our id to original request.
   */
  private val clientRequests = new HashMap[Long, String]
  /**
   * maps a request with the original requester.
   */
  private val pendingRequestsRefs = new HashMap[Long, ActorRef]

  def receive = {
    
    case Request(msgID, txt) =>
   
      //adds a mapping from the message id to the pending request message
      clientRequests.put(msgID, txt);
      //adds a mapping from the message id to a reference of sender
      pendingRequestsRefs.put(msgID, sender);
      worker ! Request(msgID, txt)
      
    case Result(msgID, key, result) => 
   
    // find the original request and make the worker available
    clientRequests.remove(msgID);
    //find the reference to the original sender
    val origSender =  pendingRequestsRefs.remove(msgID).get;
    
    // if we got a valid result from the worker, send result to client
    if (result != null)
    {
      origSender ! Result(msgID, key, result)
    }
  
  }
}
 