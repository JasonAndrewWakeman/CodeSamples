package Multi_Threaded_Scala
import akka.actor.Actor
import akka.actor.ActorRef
import scala.collection.mutable.ArrayBuffer
import scala.collection.mutable.HashMap

//constructed actor with reference to proxy component actor named db
class Client(db: ActorRef) extends Actor {
  //local cache used to store Records of key value pairs
  private val cache: ArrayBuffer[Record] = new ArrayBuffer()
  //maps a request to a key being requested
  private val pending = new HashMap[Long, Integer]
  //maps a request to the original requester.
  private val pendingRefs = new HashMap[Long, ActorRef]

  def receive = {
    case Request(msgID, txt) =>

      parseRequest(msgID, txt.trim)

    case Result(msgID, key, txt) =>

      parseResult(msgID, txt)

  }

  def parseRequest(msgID: Long, s: String) {

    if (s == "d") {
      display()
      sender ! Repeat
    } else {

      //defines the key to be the input if input is a positive number. 0 otherwise
      val key = toInt(s).getOrElse(0)

      //if key is not a positive number send instructions to console
      if (key == 0) {
        println("Please enter 'd' or an id number")
        sender ! Repeat
      } //else lookup the value for the key
      else
        doLookup(msgID, key);

    }

  }
  //tries to convert the string to a positive int, and returns an Option[Int]
  def toInt(s: String): Option[Int] = {
    try {
      Some(s.toInt)
    } catch {
      case e: NumberFormatException => None
    }
  }

  /**
   * Looks up the value for the given key, retrieving it from the
   * slow database if not present in the local list.
   * @param key
   */
  def doLookup(msgID: Long, key: Int) {
    val value = getLocalValue(key);
    if (value == null) {
      pending.put(msgID, key);
      pendingRefs.put(msgID, sender)
      db ! Request(msgID, key.toString())
    } else {
      println("Value for id " + key + ": " + value);
      sender ! Repeat
    }
  }

  /**
   * Returns the value for given key, or null if not present in the list.
   * @param key
   * @return
   */
  def getLocalValue(key: Int): String =
    {
      for (r <- cache) {
        if (r.key() == key)
          return r.value();
      }
      return null;
    }

  //we got a result from the database which must be put in the cache if not already there
  def parseResult(msgID: Long, txt: String) {

    val key: Int = pending.get(msgID).get
    //sets origSender to be the sender associated with the original message request
    val origSender: ActorRef = pendingRefs.get(msgID).get
    if (key != null.asInstanceOf[Int]) {
      val result = getLocalValue(key);
      if (result == null) {
        cache += (new Record(key, txt));
        println("Value for id " + key + ": " + txt);
      } 
      else
        println("Value for id " + key + ": " + result);

    }
    origSender ! Repeat
  }

  /**
   * Displays all key/value pairs in local list.
   */
  def display() {
    for (r <- cache) {
      println(r.key() + " " + r.value());
    }
  }

}

/**
 * Key/value pair.
 */

class Record(key: Int, value: String) {

  def key(): Int =
    {
      return key;
    }

  def value(): String =
    {
      return value;
    }
}
