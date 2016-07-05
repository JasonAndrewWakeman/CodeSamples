package Multi_Threaded_Scala

import akka.actor.Actor
import akka.actor.ActorSystem
import akka.actor.Props
 
case class Request(messageID : Long, text : String)
case class Result(messageID : Long, key : Int, text : String)
case object Repeat

object Main extends App {
  //creates actor system
  val system = ActorSystem("ScientistSearch")
   //creates the proxyWorker actor
  val proxyWorker = system.actorOf(Props[ProxyWorker], name = "proxyWorker")
  //creates a reference to the proxy worker
  val proxWorkRef = proxyWorker
  //creates a props the proxyComponent will use to be constructed with ref to proxy worker
  val proxyProps = Props(classOf[Proxy], proxWorkRef)
  //creates the proxyComponent actor
  val proxyComp = system.actorOf(proxyProps, "proxyComp")
  //creates a reference to the proxyComponent Actor
  val proxRef = proxyComp
  //creates a props the client will use to be constructed with ref to proxyComponent
  val cliProps = Props(classOf[Client], proxRef)
  //creates a client actor with a reference to the proxy Component
  val client = system.actorOf(cliProps, "client")
   //creates a reference to the client actor
  val cliRef = client
  //creates a props the input will use to be constructed with ref to client
  val inProps = Props(classOf[Input], cliRef)
  //creates an input actor with a reference to the client
  val input = system.actorOf(inProps, "input")
 

  
//initializes the input actor's action which in turn uses the client which will maintain the input actor
 input ! Repeat

}
