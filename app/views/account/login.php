<div class="container mt-5">
  <form class="form-signin" method="POST">
    <h3>Login</h3>
    <br>

    <?php if ($fmsg != '') { ?> 
    	<div class="alert alert-danger" role="alert">
				<?php echo $fmsg ?> 
			</div> 
		<?php } ?>

    <input type="text" name="login" class="form-control" placeholder="Login" required>
    <br>
    <input type="password" name="password" class="form-control" placeholder="Password" required>
    <br>
    <a href="/forgot_pass">Forgot password?</a>
    <br>
    <br>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
  </form>
</div>