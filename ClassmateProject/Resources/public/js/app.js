var extensionInstalled = false;
var videoplaying = false;
var broadcaststarted = false;
var audiostarted = false;
var videoElement = document.getElementById('video');
var audioElement = document.getElementById('audio');
var stream;
var audStream;

    var idTextBox = document.getElementById("classid");
   // idTextBox.value = idTextBox.value + text;

var peer = new Peer(idTextBox.value,{key: '42fletdy618dj9k9'});

peer.on('open', function(id) {
  console.log('My peer ID is: ' + id);
 // add(id);
});

peer.on('connection', function(conn) {
  conn.on('data', function(data){
    // Will print user id
    console.log('connection from:', data);
    if(data.streamRequested == 'video'){
      var vid = true;
    }
    if(data.streamRequested == 'audio'){
      var aud = true;
    }
    if(broadcaststarted && data.streamRequested == 'video'){
       var call = peer.call(data.ID, stream); 
    }
    else if(audiostarted && data.streamRequested == 'audio'){
       var call = peer.call(data.ID, audStream); 
    }
  else{
        if(vid){
          var conn = peer.connect(data.ID);
            conn.on('open', function(){
            conn.send('novideo');
        });
        }
        if(aud){
          var conn = peer.connect(data.ID);
            conn.on('open', function(){
          conn.send('noaudio');
           });
        }
      }
  });
});


/**
function add(text){
    var idTextBox = document.getElementById("classid");
    idTextBox.value = idTextBox.value + text;
}
*/

document.getElementById('start').addEventListener('click', function() {
  // send request to content-script
  if (!extensionInstalled){
    var message = 'Please install the extension:\n' +
                  '1. Go to https://chrome.google.com/webstore/detail/class-mate/jclohdjnfkijimebaioenmgjljjejkac\n' +
                  '2. Install Extension\n' +
                  '3. Reload this page';
    alert(message);
  }
  if(broadcaststarted){
	var message = 'Broadcast is already started!'
		alert(message);
	}
	else{
		window.postMessage({ type: 'SS_UI_REQUEST', text: 'start' }, '*');
	}
});

document.getElementById('startaudio').addEventListener('click', function() {
  if(audiostarted){
    alert('Audio Stream is already Started!');
  }
  else{
    startAudioStream();
  }
});

document.getElementById('pause').addEventListener('click', function() {
	if(videoplaying && broadcaststarted){
		pauseVideo();
	}
	else if((!videoplaying) && broadcaststarted){
		var message = 'The video is already paused!'
		alert(message);
	}
	else if(!broadcaststarted){
		var message = 'You have not started the broadcast yet!'
		alert(message);
	}
});

document.getElementById('resume').addEventListener('click', function() {
	if(videoplaying && broadcaststarted){
		var message = 'Broadcast is already playing!'
		alert(message);
	}
	else if((!videoplaying) && broadcaststarted){
		resumeVideo();	
	}
	else if(!broadcaststarted){
		var message = 'Broadcast has not started! Nothing to resume!'
		alert(message);
	}
});

// listen for messages from the content-script
window.addEventListener('message', function (event) {
  if (event.origin != window.location.origin) return;

  // content-script will send a msg if extension is installed
  if (event.data.type && (event.data.type === 'SS_PING')) {
    extensionInstalled = true;
  }

  // user chose a stream
  if (event.data.type && (event.data.type === 'SS_DIALOG_SUCCESS')) {
    startScreenStreamFrom(event.data.streamId);
  }

  // user clicked cancel
  if (event.data.type && (event.data.type === 'SS_DIALOG_CANCEL')) {
    console.log('User cancelled!');
  }
});

function startScreenStreamFrom(streamId) {
  navigator.webkitGetUserMedia({
    audio: false,
    video: {
      mandatory: {
        chromeMediaSource: 'desktop',
        chromeMediaSourceId: streamId,
        maxWidth: window.screen.width,
        maxHeight: window.screen.height
      }
    }
  },
  
  function(screenStream) {
	stream = screenStream;
    videoElement.src = URL.createObjectURL(screenStream);
    videoElement.play();
	videoplaying = true;
	broadcaststarted = true;
  },

  function(error) {
    console.log('getUserMedia failed!: ' + error);
  });
}

function startAudioStream() {
  navigator.webkitGetUserMedia({
    audio: true,
    video: false
  },
  
  function(audioStream) {
    audStream = audioStream;
    audioElement.src = URL.createObjectURL(audioStream);
    audioElement.play();
    audiostarted = true;
  },

  function(error) {
    console.log('getUserMedia failed!: ' + error);
  });
}

function pauseVideo() {
    videoElement.pause();
	videoplaying = false;
}
  
function resumeVideo() {
    videoElement.play();
	videoplaying = true;
}

function makeid()
{
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 5; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}