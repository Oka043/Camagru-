<script src="/public/js/deleteImage.js"></script>
<script src="/public/js/followUser.js"></script>

<div class="followContainer">

  <div class="header">
    <h1>Followers</h1>
  </div>


  <?php 
    if ($followers != NULL ) {
      foreach ($followers as $key => $val) { 
  ?>
  <div>
    <div class="follow-description">

      <div class="user-avatar">
        <?php 
          echo "<a title=".$followers[$key]['login'];
          echo " href='/profile/gallery?user=".$followers[$key]['user_id']."'>";
          if (isset($followers[$key]["avatar_src"])) {
            echo "<img src="."/".$followers[$key]["avatar_src"]." alt='avatar'>";
          } else {
            if (isset($followers[$key]['gender']) && $followers[$key]['gender'] == 1) {
              echo "<img src='/images/avatars/avatar_man.png' alt='avatar'>";
            } else if (isset($followers[$key]['gender']) && $followers[$key]['gender'] == 2) {
              echo "<img src='/images/avatars/avatar_woman.png' alt='avatar'>";
            } else {
              echo "<img src='/images/avatars/avatar_unisex.png' alt='avatar'>";
            }
          }
          echo "</a>";
        ?>
      </div>

      <div class="user-info">
        <div class="login">
          <?php 
            if (isset($followers[$key]["login"])) {
              echo "<a title=".$followers[$key]["login"];
              echo " href='/profile/gallery?user=".$followers[$key]['user_id']."'>";
              echo "<b>".$followers[$key]["login"]."</b>";
              echo "</a>";
            }
          ?>
        </div>
        <div class="name">
          <?php echo isset($followers[$key]["first_name"]) ? $followers[$key]["first_name"] : "" ?>
          <?php echo isset($followers[$key]["last_name"]) ? $followers[$key]["last_name"] : "" ?>
        </div>
      </div>

      <?php 
        if (isset($followers[$key]["user_id"]) 
          && isset($_SESSION["user_id"])
          && $followers[$key]["user_id"] != $_SESSION["user_id"]) {
      ?>
      <div class="follow">
        <?php
        if ($followers[$key]["followed"] && $followers[$key]["followed"] == 1)
          echo "<button type='button' class='follow-btn follow-unfollow' data-user_id=".$followers[$key]["user_id"].">Unfollow</button>";
        else 
          echo "<button type='button' class='follow-btn follow-unfollow' data-user_id=".$followers[$key]["user_id"].">Follow</button>";
        ?>
      </div>
      <?php } ?>
    </div>
  </div>

  <?php }} ?>
</div>