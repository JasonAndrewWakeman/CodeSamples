//add an event listener to myself which will listen for a message and pass the payload into my helper function
self.addEventListener('message', monteCarloHelper, false);

function monteCarloHelper (e){
  var circleCount = 0;
  //for 1 through number of Iterations (received as payload of message) perform calculation using 2 random doubles
  for (var i = 0; i < e.data.numIts; i++ ) {
     var x = Math.random();
     var y = Math.random();
     if ((x * x + y * y) < 1){
       circleCount = circleCount + 1;
     }//end if
  }//end for loop
  //post a message to the sender of the message triggering this call to monteCarlo with payload circleCount
  self.postMessage(circleCount);
  //dismiss myself as i am done.
  close();
}//end monteCarloHelper
