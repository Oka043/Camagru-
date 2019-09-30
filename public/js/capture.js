"use strict";

// Capture a photo by fetching the current contents of the video
// and drawing it into a canvas, then converting that to a PNG
// format data URL. By drawing it on an offscreen canvas and then
// drawing that to the screen, we can change its size and/or apply
// other changes before drawing it.
(function() {

  let masks = null;
  let buttons = null;
  // |canStreaming| indicates whether or not we're currently canStreaming
  // video from the camera. Obviously, we start at false.
  let canStreaming = false;

  // The letious HTML elements we need to configure or control. These
  // will be set by the startup() function.

  let video = null;
  let canvas = null;

  function startup() {
    video = document.getElementById('video');
    canvas = document.getElementById('canvas');
  
    // Try to use camera
    navigator.mediaDevices.getUserMedia({video: true, audio: false})
    .then(function(stream) {
      video.srcObject = stream;
      video.play();
    })
    .catch(function(err) {
      // if user block the camera - display error in console.
      buttons = new manageButtons(false);
    });


    // resize canvas
    video.addEventListener('canplay', function(ev) {
      if (!canStreaming) {
        buttons = new manageButtons(true);
      }
    }, false);
  }

  // Set up our event listener to run the startup process
  // once loading is complete.
  window.addEventListener('load', startup, false);
})();


window.onload = function() {
  initAbilityToDragStiker();
};




