<div class="containerGallery">
  <main>
    <div class="gallery">
      <?php 
        if ($images != NULL ) {
          foreach ($images as $key => $val) { 
      ?>
        <div class="gallery-item">
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
          echo "<a class='page-link' href='?page=".$prew_page."'>Previous</a>";
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
            echo "<a class='page-link' href='?page=".$next_page."'>Next</a>";
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
