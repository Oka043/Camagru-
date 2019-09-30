<script src="/public/js/manageButtons.js"></script>
<script src="/public/js/capture.js"></script>
<script src="/public/js/stikerDragDrop.js"></script>
<!-- https://stackoverflow.com/questions/158750/can-you-combine-multiple-images-into-a-single-one-using-javascript -->

<div class="containerMakePhoto">
  
  <div id="field">
    <video id="video">Video stream not available.</video>
    <img src="" id="stikerImage">
    <canvas id="uploadedPicture" style="display:none;" style="border:1px solid red;"></canvas>
    <canvas id="outputPicture" style="display:none;"></canvas>
  </div>

  <div id="availableStikers">
    <!-- if this stiker selected, save button inactive -->
    <input type="radio" name="stiker" style="display:none;" id="emptyStiker" value="none" checked>
  </div>

  <div id="descriptionBlock">
    <b>Description:</b>
    <textarea class="form-control" id="description" name="description" type="text" rows="2"><?php echo (isset($bio) && $bio != '') ? $bio : "" ?></textarea>
  </div>
  <div class="buttons_container">
    <button id="useCameraBtn">Use Camera</button> 
    <label id="uploadPictureBtn">
      Upload Photo
      <input type="file" style="display: none;">
    </label>
    <button id="savePictureBtn">Save Photo</button> 
  </div>



</div>