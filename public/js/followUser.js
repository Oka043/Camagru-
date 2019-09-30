"use strict";

function sendHttpMessage(url, data) {
  return new Promise(function (resolve, reject) {

    let xhttp = new XMLHttpRequest();
    let responseData = null;

    xhttp.timeout = 2000;
    xhttp.onreadystatechange = function(e) {
      if (this.readyState === 4) {
        if (this.status === 200) {
          let res = JSON.parse(this.responseText);
          resolve(res);
        } else {
          let error = this.status;
          reject(error);
        }
      }
    }
    xhttp.ontimeout = function () {
      reject('timeout')
    }

    // https://toster.ru/q/325109
    xhttp.open('POST', url, true)
    xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');  
    xhttp.send(data);
  })
}

function sendFollowRequest(el, user_id_to_follow) {
  sendHttpMessage("/requests/follow_unfollow", "follow_user_id=" + user_id_to_follow).then(
      res => (function (res) {
        if (res["result"]) {
          el.innerHTML = res["content"];
          let followers = document.querySelectorAll('b.followers')[0];
          if (followers && res["content"] == "Follow") {
            followers.innerHTML = parseInt(followers.innerHTML, 10) - 1;
          } else if (followers && res["content"] == "Unfollow") {
            followers.innerHTML = parseInt(followers.innerHTML, 10) + 1;
          }
        }
      }.bind(this, res))(),
      error => (function (error) {
        ;
        // console.log("Can't connect : " + error);
      })()
  );
}

class manageFollowButtons {
  constructor() {
    // Masks
    this.buttons = document.getElementsByClassName('follow-unfollow');
    
    this.addEventsOnButtons();
  }
  addEventsOnButtons() {
    [].forEach.call(this.buttons, function(el) {
      el.addEventListener("click", function() {
        sendFollowRequest(el, el.getAttribute("data-user_id"));
      }, false);
    });
  }
}

function sendLikeRequest(el) {
  let params = new URLSearchParams(location.search);
  let data = "user="+params.get('user')+"&image_id="+params.get('image');
  let url = "/requests/like_unlike";

  sendHttpMessage(url, data).then(
      res => (function (res) {
        if (res["result"]) {
          let like_btn = document.getElementById('like-btn');
          let total_likes = document.getElementById('total-likes');
          if (like_btn && res["content"] == "+") {
            like_btn.classList.add("liked-image");
            like_btn.classList.remove("unliked-image");
            if (total_likes) {
              total_likes.innerHTML = parseInt(total_likes.innerHTML, 10) + 1;
            }
          } else if (like_btn && res["content"] == "-") {
            like_btn.classList.add("unliked-image");
            like_btn.classList.remove("liked-image");
            if (total_likes) {
              total_likes.innerHTML = parseInt(total_likes.innerHTML, 10) - 1;
            }
          }
        }
      }.bind(this, res))(),
      error => (function (error) {
        ;
        // console.log("Can't connect : " + error);
      })()
  );
}

class manageLikeButtons {
  constructor() {
    this.button = document.getElementById('like-btn');
    if (this.button) {
      this.button.addEventListener("click", function() {
        sendLikeRequest(this.button);
      }, false);
    }
  }
}




window.onload = function() {
  let followButtons = new manageFollowButtons();
  let LikeButtons = new manageLikeButtons();
  addCloseBtn();
};









