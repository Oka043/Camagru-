"use strict";

function	sendRequest(user_id, image_id) {



  return new Promise(function (resolve, reject) {
    let fd = new FormData();
    let xhr = new XMLHttpRequest();

    if (!user_id || !image_id)
    	reject("undefined id's");

    console.log("user_id : ", user_id);
    console.log("image_id : ", image_id);

    fd.append("user_id", user_id);
    fd.append("image_id", image_id);

    xhr.timeout = 2000;
    xhr.onreadystatechange = function(e) {
      if (this.readyState === 4) {
        if (this.status === 200) {
          console.log("1 : ", this.responseText);
        	if (this.responseText == "success") {
            console.log("2");
          	resolve(this.responseText);
          } else {
          	reject(this.responseText);
          }
        } else {
          reject(this.status);
        }
      }
    }
    
    xhr.ontimeout = function () {
      reject('timeout')
    }
    // Opening request 
    xhr.open('POST', '/requests/delete_picture', true);
    // Send request data
    xhr.send(fd);
  });
}



function deletePicture() {
	let image_id;
	let user_id;
  // var attribute = this.getAttribute("data-myattribute");
  if (this.hasAttribute("data-user-id")){
  	user_id = this.dataset.userId;
  }
  if (this.hasAttribute("data-image-id")){
  	image_id = this.dataset.imageId;
  }


  sendRequest(user_id, image_id).then(
      res => (function (res) {
        this.parentNode.remove();
      }.bind(this, res))(),
      error => (function (error){
      	console.log("here : ", error);
      })()
  );


  
  // let a_tag = this.parentElement.getElementsByTagName("a")[0];
  // if (a_tag) {
  // 	let url = a_tag.href;
   //  var queryString = url ? url.split('?')[1] : "";

   //  console.log(queryString);
   //  console.log(window.location.search.slice(1));
  // }
	    
};


function addCloseBtn() {
	var deleteButtons = document.getElementsByClassName("closeButton");

	if (deleteButtons) {
		for (var i = 0; i < deleteButtons.length; i++) {
	    deleteButtons[i].addEventListener('click', deletePicture, false);
		}
	}
}

