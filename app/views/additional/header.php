<!-- shadow-sm -->
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom">
  <h5 class="my-0 mr-md-auto font-weight-normal">Camagru</h5>
  <nav>
    <a class="p-2 text-dark" href="/">Main Page</a>

    <?php if (isset($_SESSION["user_id"])) { ?>
      <a class="p-2 text-dark" href="/profile/make_picture">Make photo</a>
    	<a class="p-2 text-dark" href="/profile/gallery">Gallery</a>
    	<a class="p-2 text-dark" href="/profile/settings">Settings</a>
    	<a class="btn btn-outline-primary" href="/logout">Logout</a>
    <?php } else { ?>
      <a class="btn btn-outline-primary mr-1" href="/register">Register</a>
    	<a class="btn btn-outline-primary" href="/login">Login</a>
		<?php } ?>
  </nav>
</div>