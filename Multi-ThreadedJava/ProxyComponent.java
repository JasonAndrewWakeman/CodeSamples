package hw3_sample_code;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.concurrent.ArrayBlockingQueue;
import java.util.concurrent.BlockingQueue;
import java.util.concurrent.ConcurrentHashMap;
import java.util.concurrent.SynchronousQueue;
import java.util.concurrent.atomic.AtomicInteger;

/**
 * Component representing an abstraction of a simple database.
 */
public class ProxyComponent extends ThreadedComponent
{  
  /**
   * Maps our id to original request.
   */
  private Map<Integer, IMessage> clientRequests = new ConcurrentHashMap<Integer, IMessage>();
  
  private final ArrayList<ProxyWorker> workers = new ArrayList<ProxyWorker>(10); 

  //maps a messageId to a the index of proxyWorker being blocked while waiting for it
  private ConcurrentHashMap<Integer, Integer> proxyWorkersMap = new ConcurrentHashMap<Integer, Integer>();
  //for each integer in this queue if present the worker with the corresponding index in workers is not busy. 
  private ArrayBlockingQueue<Integer> freeList = new ArrayBlockingQueue<Integer>(10); 
  
  public ProxyComponent(ProxyWorker worker)
  {
  
	//builds the final ArrayList workers and adds indices 0-9 to freeList
    workers.add(0, worker); 
    freeList.add(0); 
    for(int i = 1; i < 10; i++){
    	workers.add(i, new ProxyWorker());
    	freeList.add(i);
    }
    startAllWorkers(); 
 
  }
 
  private void startAllWorkers() {
	  for(int i = 1; i < 10; i++){
	    	workers.get(i).start(); 
	  }
	
}

@Override
  public void handle(RequestMessage msg)
  {

	  //locks so two threads don't try to both use the last available worker at the same time
	  synchronized(freeList){
	  //if there is not available Workers ignore the request.
	  if(freeList.isEmpty()){
		return; 
	  }
	  //Remove an index from freeList and tell the worker at that index in the workers array to do work
	  int index = freeList.poll();
	  RequestMessage ourRequest = new RequestMessage(this, msg.getKey());
	  clientRequests.put(ourRequest.getId(), msg);
	  //puts the freeworker from the array to work	
	  workers.get(index).send(ourRequest);
	  proxyWorkersMap.put(ourRequest.getId(), index);

	
	}//end synchronized on freeList
	return;
	
  }
  //performs housekeeping by adding the index of the worker who experienced the timeout back to the freeList
  @Override 
  public void handle(TimeoutMessage msg)
  {
	  int ourId = msg.getCorrelationId();
	  clientRequests.remove(ourId);
	  int keyOfWorker = ourId; 
	  int index = proxyWorkersMap.get(keyOfWorker);
	  proxyWorkersMap.remove(keyOfWorker); 
	  freeList.add(index);
  }
  @Override
  public void handle(ResultMessage msg)
  {
    int ourId = msg.getCorrelationId();
    String result = msg.getResult();
    
    // find the original request and make the worker available
    IMessage originalRequest = clientRequests.remove(ourId);
    int keyOfWorker = ourId; 
    int index = proxyWorkersMap.get(keyOfWorker);
    proxyWorkersMap.remove(keyOfWorker); 
    freeList.add(index);
    // if we got a valid result from the worker, send result to client
    if (result != null)
    {
      IMessage reply = new ResultMessage(originalRequest.getId(), this, result);
      originalRequest.getSender().send(reply);
    }
  }
  
}
