 
var videoElement = document.getElementById('video');
var peer = new Peer({key: '42fletdy618dj9k9'});
var idTextBox = document.getElementById("classid");
var id;
var searchTrm = "";

function archiveWiki() {
  var archiveWikiUrl = Routing.generate('archive_this');
  var tempStr = document.getElementById('display').innerHTML;
  $.ajax({
    type: 'post',
    url: archiveWikiUrl,
    data: {tempString: tempStr, typeOf: 'wiki', name: searchTrm},
      success: function (response) { 

        var data = JSON.parse(response);

        
          document.getElementById("display").innerHTML = "";
           
           return false;
       
      }
  });
}



function archiveNote(value, typeOfArc) {
  var tempStr = value;
  var arcName = tempStr.substring(0, 50);
  var archiveTextUrl = Routing.generate('archive_this');

  $.ajax({
    type: 'post',
    url: archiveTextUrl,
    data: {tempString: tempStr, typeOf: typeOfArc, name: arcName },
      success: function (response) {

        var data = JSON.parse(response);

        if(data.wasSuccessful == "true")
        {
          document.getElementById('textNote').value = "";
        }
        else
        {        
           return false;
        }  
      }
  });
}

  $(function searchWiki() {
    var getWikiUrl = Routing.generate('get_some');

    $('#wikiSearch').on('submit', function (e) {

      e.preventDefault();
   
      $.ajax({
        type: 'post',
        url: getWikiUrl,
        data: $('#wikiSearch').serialize(),
        success: function (response) {
      
        var data = JSON.parse(response);

        if(data.wasSuccessful == "true")
        {
          searchTrm = data.searchTerm; 
          document.getElementById("display").innerHTML= data.data1;
        }
        else
        { 
          document.getElementById("display").innerHTML = "We could not find a wikipedia page associated with your search term";
        } 
        
        return false;
      }

    });

  });

});
         

peer.on('open', function(thisid) {
  console.log('My peer ID is: ' + thisid);
  id = thisid;
});
peer.on('error', function(err) {
alert('Invalid Class ID!');
});

peer.on('call', function(call) {
console.log('inside peer.on');
call.answer();
    call.on('stream', function(remoteStream) {
	console.log('inside call.on');
      // Show stream in some video element.
	    videoElement.src = URL.createObjectURL(remoteStream);
		videoElement.play();
    });
  }, function(err) {
    console.log('Failed to get local stream' ,err);
  });

document.getElementById('start').addEventListener('click', function() {
	if(idTextBox.value != ''){
	var conn = peer.connect(idTextBox.value);
	conn.on('open', function(){
	conn.send(id);
	});
	}
	else{
	alert('Please Enter A Class ID!');
	}
});