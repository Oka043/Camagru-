<script src="/public/js/deleteImage.js"></script>
<script src="/public/js/followUser.js"></script>

<div class="followContainer">

  <div class="header">
    <h1>Following</h1>
  </div>


  <?php 
    if ($following != NULL ) {
      foreach ($following as $key => $val) { 
  ?>
  <div>
    <div class="follow-description">

      <div class="user-avatar">
        <?php 
          echo "<a title=".$following[$key]['login'];
          echo " href='/profile/gallery?user=".$following[$key]['user_id']."'>";
          if (isset($following[$key]["avatar_src"])) {
            echo "<img src="."/".$following[$key]["avatar_src"]." alt='avatar'>";
          } else {
            if (isset($following[$key]['gender']) && $following[$key]['gender'] == 1) {
              echo "<img src='/images/avatars/avatar_man.png' alt='avatar'>";
            } else if (isset($following[$key]['gender']) && $following[$key]['gender'] == 2) {
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
            if (isset($following[$key]["login"])) {
              echo "<a title=".$following[$key]["login"];
              echo " href='/profile/gallery?user=".$following[$key]['user_id']."'>";
              echo "<b>".$following[$key]["login"]."</b>";
              echo "</a>";
            }
          ?>
        </div>
        <div class="name">
          <?php echo isset($following[$key]["first_name"]) ? $following[$key]["first_name"] : "" ?>
          <?php echo isset($following[$key]["last_name"]) ? $following[$key]["last_name"] : "" ?>
        </div>
      </div>

      <?php 
        if (isset($following[$key]["user_id"]) && isset($_SESSION["user_id"])) {
      ?>
      <div class="follow">
        <?php
          if ($following[$key]["followed"] && $following[$key]["followed"] == 1)
            echo "<button type='button' class='follow-btn follow-unfollow' data-user_id=".$following[$key]["user_id"].">Unfollow</button>";
          else 
            echo "<button type='button' class='follow-btn follow-unfollow' data-user_id=".$following[$key]["user_id"].">Follow</button>";
        ?>
      </div>
      <?php } ?>
    </div>
  </div>

  <?php }} ?>
</div>