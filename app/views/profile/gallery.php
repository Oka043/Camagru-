<script src="/public/js/deleteImage.js"></script>
<script src="/public/js/followUser.js"></script>

<div class="containerGallery">
  
  <header>
    <div class="profile-avatar">
      <?php 
        if (isset($avatar_src)) {
          echo "<img src="."/".$avatar_src." alt='avatar'>";
        } else {
          if (isset($gender) && $gender == 1) {
            echo "<img src='/images/avatars/avatar_man.png' alt='avatar'>";
          } else if (isset($gender) && $gender == 2) {
            echo "<img src='/images/avatars/avatar_woman.png' alt='avatar'>";
          } else {
            echo "<img src='/images/avatars/avatar_unisex.png' alt='avatar'>";
          }
        }
      ?>
    </div>

    <div class="profile-info">

      <div class="profile-name-settings">
        <h1 class="profile-name"><?php echo isset($login) ? $login : "" ?></h1>        
        <?php
          if (isset($user_id) 
              && isset($_SESSION["user_id"])
              && $_SESSION["user_id"] == $user_id) {
            echo "<a href='/profile/settings'>Edit Profile</a>";
          } else if (isset($user_id) && isset($_SESSION["user_id"])) {
            if (isset($followed) && $followed == true)
              echo "<button type='button' class='follow-btn follow-unfollow' data-user_id=".$user_id.">Unfollow</button>";
            else 
              echo "<button type='button' class='follow-btn follow-unfollow' data-user_id=".$user_id.">Follow</button>";
          }
        ?>
      </div>
      
      <div class="profile-stats">
        <ul>

          <?php 
            if (isset($images))
              echo "<li><b>".count($images)."</b> posts</li>";
            else
              echo "<li><b>0</b> posts</li>";

            if (isset($followers))
              echo "<li><a href='/profile/followers?user=".$user_id."'><b class='followers'>".$followers."</b> followers</a></li>";
            else
              echo "<li><b>0</b> followers</li>";

            if (isset($following))
              echo "<li><a href='/profile/following?user=".$user_id."'><b>".$following."</b> following</a></li>";
            else
              echo "<li><b>0</b> following</li>";

          ?>
        </ul>
      </div>
      
      <div class="profile-bio">
        <p class="profile-real-name">
          <?php echo isset($first_name) ? $first_name : "" ?>
          <?php echo isset($last_name) ? $last_name : "" ?>
        </p>
        <p class="profile-description">
          <?php echo isset($bio) ? $bio : "" ?>
        </p>
      </div>

    </div>
  </header>


  <main>
    <div class="gallery">
      <?php 
        if ($images != NULL ) {
          foreach ($images as $key => $val) { 
      ?>
        <div class="gallery-item">
          <?php 
            if (isset($user_id) 
              && isset($_SESSION["user_id"])
              && $_SESSION["user_id"] == $user_id) {
              echo "<button class='closeButton' data-user-id=".$user_id." data-image-id=".$images[$key]["image_id"]."></button>";
            }
          ?>

          <img class="gallery-image" src=<?php echo "/".$images[$key]["image_src"] ?> >
          <a class="gallery-item-info" href=<?php echo "/profile/picture?user=".$images[$key]["user_id"]."&image=".$images[$key]["image_id"]; ?> >
            <ul>
              <li class="gallery-item-likes">&#xe800 
               <?php echo isset($images[$key]["likes"]) ? $images[$key]["likes"] : 0 ?>
              </li>
              <li class="gallery-item-comments">&#xe801 
                <?php echo isset($images[$key]["comments"]) ? $images[$key]["comments"] : 0 ?>
              </li>
            </ul>
          </a>
        </div>  
      <?php }} ?>
    </div>
  </main>

  
  <nav aria-label="...">
    <ul class="pagination">
      <?php 
        if (isset($prew_page) && $prew_page != NULL) {
          echo "<li class='page-item'>";
          echo "<a class='page-link' href='?user=".$user_id."&page=".$prew_page."'>Previous</a>";
          echo "</li>";
        } else {
          echo "<li class='page-item disabled'>";
          echo "<span class='page-link'>Previous</span>";
          echo "</li>";
        }
      ?>
      
      <li class="page-item">
        <?php 
          if (isset($next_page) && $next_page != NULL) {
            echo "<li class='page-item'>";
            echo "<a class='page-link' href='?user=".$user_id."&page=".$next_page."'>Next </a>";
            echo "</li>";
          } else {
            echo "<li class='page-item disabled'>";
            echo "<span class='page-link'>Next</span>";
            echo "</li>";
          }
        ?>
      </li>
    </ul>
  </nav>

</div>
