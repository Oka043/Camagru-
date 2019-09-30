<?php 
	if (isset($_SESSION["user_id"])) {
		header('Location: /');
	} else {
?>

	<div class="container mt-5">
	  <form class="form-signin" method="POST">
	    <h3>Регистрация</h3>
	    <br>
	    <?php if ($smsg != '') { ?> <div class="alert alert-success" role="alert"><?php echo $smsg ?></div> <?php } ?>
	    <?php if ($fmsg != '') { ?> <div class="alert alert-danger" role="alert"><?php echo $fmsg ?></div> <?php } ?>
	    <input type="text" name="login" class="form-control" placeholder="Login" required>
	    <br>
	    <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
	    <br>
	    <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
	    <br>
	    <input type="email" name="email" class="form-control" placeholder="Email" required>
	    <br>
	    <input type="password" name="password" class="form-control" placeholder="Password" required>
	    <br>
	    <div class="form-check form-check-inline">
	      <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="1" required>
	      <label class="form-check-label" for="inlineRadio1">Male</label>
	    </div>
	    <div class="form-check form-check-inline">
	      <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="2" required>
	      <label class="form-check-label" for="inlineRadio2">Female</label>
	    </div>
	    <br>
	    <br>

	    <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
	  </form>
	</div>

<?php } ?>