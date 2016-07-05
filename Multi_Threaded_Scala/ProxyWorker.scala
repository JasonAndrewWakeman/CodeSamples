package Multi_Threaded_Scala
import akka.actor.Actor
import java.net._
import java.io._
import scala.io._

class ProxyWorker extends Actor {

  def receive = {

    case Request(msgID, txt) => getResultFromDB(msgID, txt.toInt)

  }

  // copied from hw1 and modified using http://web.cs.iastate.edu/~smkautz/cs430s16/examples/scala/TestClient.scala
  def getResultFromDB(msgID: Long, key: Integer) {
    // open a connection to the server
    val sock = new Socket(InetAddress.getByName("localhost"), 2222)
    val pw = new PrintWriter(sock.getOutputStream())

    //outputs to server a line containing the key requested
    pw.println("" + key);
    pw.flush(); // don't forget to flush...    
    // read response, which we expect to be line-oriented text
    val in = Source.fromInputStream(sock.getInputStream()).getLines()
    //for every line returned from server, send the result to the requester
    for (line <- in) {
      sender ! Result(msgID, key, line);
    }
    sock.close();
  }
}
