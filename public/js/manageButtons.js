"use strict";

class manageButtons {

  constructor(canStreaming) {
    // Stikers
    this._stikerImage       = document.getElementById("stikerImage");
    this._stikerContainer   = document.getElementById("availableStikers");

    this._stikerImage.src = "";
    this._stikerImage.style.display = 'none';

    this.getStikersSources().then(
        res => (function (res) {
          this._stikerData = res;
          this.addStikersOnPage(this._stikerContainer, '.', this._stikerData);
        }.bind(this, res))(),
        error => (function (error){
          ;
          // console.log("Cant load stikers");
        })()
    );

    this._stikerContainer.addEventListener('click', this.selectStiker.bind(this));

    this._loadedPictureData     = null;
    this._uploadedPictureReady  = false;
    this._canStreaming          = canStreaming;

    // Buttons
    this._useCameraBtn      = document.getElementById('useCameraBtn');
    this._uploadPictureBtn  = document.getElementById('uploadPictureBtn');
    this._savePictureBtn    = document.getElementById('savePictureBtn');

    this._uploadedPicture   = document.getElementById('uploadedPicture');
    this._outputPicture     = document.getElementById('outputPicture');

    this._video = document.getElementById('video');

    this._height  = 480;
    this._width   = 640;

    this._uploadedPicture.height  = this._height;
    this._uploadedPicture.width   = this._width;
    this._outputPicture.height    = this._height;
    this._outputPicture.width     = this._width;
    this._video.height            = this._height;
    this._video.width             = this._width;

    // can use camera?
    if (this._canStreaming) {
      this._useCameraBtn.addEventListener('click', this.useCamera.bind(this), false);
      this._useCameraBtn.classList.add("useCameraBtn", "activeBtn");
      
      this._uploadPictureBtn.classList.add("uploadPictureBtn");

      this._usingCamera = true;
    } else {
      this._useCameraBtn.classList.add("invalidBtn");

      this.prepareCanvas();
      this._uploadPictureBtn.classList.add("uploadPictureBtn", "activeBtn");

      this._usingCamera = false;
    }

    this._uploadPictureBtn.addEventListener('click', this.prepareCanvas.bind(this), false);
    this._uploadPictureBtn.addEventListener('change', this.useUploaded.bind(this), false);

    this._savePictureBtn.addEventListener('click', this.makePicture.bind(this), false);

  }


  useCamera(event) {

    if (this._canStreaming && !this._usingCamera) {
      this.setStikerDoDefault();

      this._video.setAttribute("style", "display:block;");
      this._uploadedPicture.setAttribute("style", "display:none;"); 

      this._uploadPictureBtn.classList.remove("activeBtn");
      this._useCameraBtn.classList.add("activeBtn");

      this._useCameraBtn.setAttribute("style", "background-color: red;");
      this._uploadPictureBtn.removeAttribute("style");
      this._usingCamera = true;
      this._uploadedPictureReady = false;
    }
    event.preventDefault();
  }


  useUploaded(event) {
    var file    = document.querySelector('input[type=file]').files[0]; //sames as here
    var reader  = new FileReader();

    if (file.type == "image/jpeg" || file.type == "image/png") {
      reader.onloadend = (function () {
        this._loadedPictureData = new Image();

        this._loadedPictureData.onload = (function () {
          
          var wRatio = 640 / this._loadedPictureData.width;
          var hRatio = 480 / this._loadedPictureData.height;
          
          var ratio  = Math.max ( wRatio, hRatio );
          
          var newWidth = this._loadedPictureData.width * ratio;
          var newHeight = this._loadedPictureData.height * ratio;

          var xOffset = newWidth > 640 ? ((newWidth - 640) / 2) : 0;
          var yOffset = newHeight > 480 ? ((newHeight - 480) / 2) : 0;

          this._uploadedPicture.getContext('2d').drawImage(this._loadedPictureData, -xOffset, -yOffset, newWidth, newHeight);
          this._uploadedPictureReady = true;
        }).bind(this);

        this._loadedPictureData.src = reader.result;
      }).bind(this);

      if (file) {
        reader.readAsDataURL(file); //reads the data as a URL
      }
    } else {
      this._uploadedPictureReady = false;
      this.clearCanvas(this._uploadedPicture);
      this._uploadedPicture.getContext("2d", {alpha: false}).fillStyle = "#606263";
      this._uploadedPicture.getContext("2d", {alpha: false}).font = "24px system-ui";
      this._uploadedPicture.getContext("2d", {alpha: false}).textAlign = "center";
      this._uploadedPicture.getContext("2d", {alpha: false}).fillText("Invalid File Format(only .jpeg or .png)", this._uploadedPicture.width / 2, this._uploadedPicture.height / 2);
    }
    event.preventDefault();
  }


  clearCanvas(canvas) {
    canvas.getContext('2d').fillStyle = "#f2f6f9";
    canvas.getContext('2d').fillRect(0, 0, this._width, this._height);
  }


  prepareCanvas(event) {
    this._usingCamera = false;
    this.setStikerDoDefault();
    this.clearCanvas(this._uploadedPicture);
    this.clearCanvas(this._outputPicture);

    this._uploadedPicture.getContext("2d", {alpha: false}).fillStyle = "#606263";
    this._uploadedPicture.getContext("2d", {alpha: false}).font = "24px system-ui";
    this._uploadedPicture.getContext("2d", {alpha: false}).textAlign = "center";
    this._uploadedPicture.getContext("2d", {alpha: false}).fillText("Upload Photo", this._uploadedPicture.width / 2, this._uploadedPicture.height / 2);

    this._video.setAttribute("style", "display:none;");
    this._uploadedPicture.setAttribute("style", "display:block;");

    if (this._canStreaming) {
      this._useCameraBtn.classList.remove("activeBtn");
      this._useCameraBtn.removeAttribute("style");
    }
    this._uploadPictureBtn.classList.add("activeBtn");
    this._uploadPictureBtn.setAttribute("style", "background-color: red;");
  }


  makePicture(event) {
    let stikerSource = this.stikerSources(document.querySelector('input[name="stiker"]:checked').value);

    if (stikerSource != "" && this._usingCamera && this._canStreaming) {
      // Draw picture in canvas, get picture from stream.
      this._outputPicture.getContext('2d').drawImage(video, 0, 0, 640, 480);
      let user_image = this._outputPicture.toDataURL("image/png");


      // Finding a mask to determine the coordinates
      let stikerCoord = this._stikerImage.getBoundingClientRect();                   // коорд маски
      let fieldCoord = document.getElementById('field').getBoundingClientRect();   // коорд поля
      let keys = document.querySelector('input[name="stiker"]:checked').value.split(" "); // выбранная маска

      // Send PICTURE to php server.
      if (keys && keys.length == 2){
        let key = keys[0];
        let id = keys[1];
        let pos_x = stikerCoord.left - fieldCoord.left;
        let pos_y = stikerCoord.top - fieldCoord.top;
        this.savePicture(user_image, pos_x, pos_y, key, id);
      }
    } else if (stikerSource != "" && !this._usingCamera && this._uploadedPictureReady) {
      // Resize input picture.
      var wRatio = 640 / this._loadedPictureData.width;
      var hRatio = 480 / this._loadedPictureData.height;
      var ratio  = Math.max ( wRatio, hRatio );
      var newWidth = this._loadedPictureData.width * ratio;
      var newHeight = this._loadedPictureData.height * ratio;
      var xOffset = newWidth > 640 ? ((newWidth - 640) / 2) : 0;
      var yOffset = newHeight > 480 ? ((newHeight - 480) / 2) : 0;
      // Draw picture in canvas, get picture from input.
      this._outputPicture.getContext('2d').drawImage(this._loadedPictureData, -xOffset, -yOffset, newWidth, newHeight);
      let user_image = this._outputPicture.toDataURL("image/png");

      // Finding a mask to determine the coordinates
      let stikerCoord = this._stikerImage.getBoundingClientRect();                   // коорд маски
      let fieldCoord = document.getElementById('field').getBoundingClientRect();   // коорд поля
      let keys = document.querySelector('input[name="stiker"]:checked').value.split(" "); // выбранная маска

      // Send PICTURE to php server.
      if (keys && keys.length == 2){
        let key = keys[0];
        let id = keys[1];
        let pos_x = stikerCoord.left - fieldCoord.left;
        let pos_y = stikerCoord.top - fieldCoord.top;
        this.savePicture(user_image, pos_x, pos_y, key, id);
      }
    } else {
      this.clearCanvas(this._outputPicture);
    }
    event.preventDefault();
  }


  savePicture(user_image, pos_x, pos_y, key, id) {
    this.sendPicture(user_image, pos_x, pos_y, key, id).then(
        res => (function (res) {
          // Добавить на страницу сообщение, что загрузка прошла успешно.
          console.log("saved succefully");
        }.bind(this, res))(),
        error => (function (error){
          console.log(error);
                      // Добавить на страницу сообщение, что загрузка прошлв с ошибкой.
          console.log("Cant save stikers");
        })()
    );
  }


  sendPicture(user_image, pos_x, pos_y, key, id) {

    return new Promise(function (resolve, reject) {
      let fd = new FormData();
      let xhr = new XMLHttpRequest();

      let description = document.getElementById('description').value;
      console.log(description);

      fd.append("key", key);
      fd.append("id", id);
      fd.append("pos_x", pos_x);
      fd.append("pos_y", pos_y);
      fd.append("picture", user_image);
      if (description)
        fd.append("description", description);
      else 
        fd.append("description", "");    
      console.log(description);


      xhr.timeout = 2000;
      xhr.onreadystatechange = function(e) {
        if (this.readyState === 4) {
          if (this.status === 200) {
            console.log(this.responseText);
            resolve(this.responseText);
          } else {
            reject(this.status);
          }
        }
      }
      
      xhr.ontimeout = function () {
        reject('timeout')
      }
      // Opening request 
      xhr.open('POST', '/requests/merge_save_pictures', true);
      // Send request data
      xhr.send(fd);
    });
  }










  // STIKERS STIKERS STIKERS STIKERS
  // STIKERS STIKERS STIKERS STIKERS
  // STIKERS STIKERS STIKERS STIKERS

  selectStiker(event) {
    // Выбраная маска
    let stikerSource = this.stikerSources(document.querySelector('input[name="stiker"]:checked').value);
    console.log("stiker src : ", stikerSource);


    if (stikerSource != "") {
      this._stikerImage.src = stikerSource;
      this._stikerImage.style.display = '';
      if (!this._usingCamera && this._uploadedPictureReady) {
        this._savePictureBtn.classList.add("savePictureBtn");
      } else if (this._usingCamera) {
        this._savePictureBtn.classList.add("savePictureBtn");
      }
      
    } else {
      this._stikerImage.src = stikerSource;
      this._stikerImage.style.display = 'none';
      this._savePictureBtn.classList.remove("savePictureBtn");
    }
  }

  setStikerDoDefault() {
    document.getElementById("emptyStiker").click();
  }

  stikerSources(value) {

    let keys = value.split(" ");
    if (keys.length == 2) {
      if (keys[0] in this._stikerData && keys[1] in this._stikerData[keys[0]]) {
        return this._stikerData[keys[0]][keys[1]];
      }
    }
    return "";
  }

  // This function handles arrays and objects
  addStikersOnPage(element, dir, data) {

    for (let key in data) {
      if (typeof data[key] == "object" && data[key] !== null) {
        let stikersContainer    = document.createElement("div");
        let previewStikerImage  = document.createElement("div");
        let allStikersImages    = document.createElement("div");
        
        stikersContainer.classList.add("stikersContainer");
        previewStikerImage.classList.add("previewStikerImage");
        allStikersImages.classList.add("allStikersImages");

        let image = document.createElement("img");
        image.src = data[key][0];
        previewStikerImage.appendChild(image);

        stikersContainer.appendChild(previewStikerImage);

        this.addStikersOnPage(allStikersImages, key, data[key]);
        stikersContainer.appendChild(allStikersImages);


        element.appendChild(stikersContainer);
      } else {
        let label = document.createElement("label");
        let image = document.createElement("img");
        let input = document.createElement("input");

        // fill info
        image.src = data[key];
        input.type = "radio";
        input.name = "stiker";
        input.value = dir + " " + key;

        // append elements to label and container.
        label.appendChild(image);
        label.appendChild(input);

        element.appendChild(label);
      }
    }
  }

  getStikersSources() {
    return new Promise(function (resolve, reject) {

      let xhttp = new XMLHttpRequest();
      let url = "/requests/get_stikers";
      let responseData = null;

      xhttp.timeout = 2000;
      xhttp.onreadystatechange = function(e) {
        if (this.readyState === 4) {
          if (this.status === 200) {
            // console.log(this.responseText);
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
      xhttp.open('POST', url, true)
      xhttp.send();
    })

  }

  get stikerData () {
    return this._stikerData;
  }
  get stikerContainer () {
    return this._stikerContainer;
  }
  get stikerImage () {
    return this._stikerImage;
  }




}
