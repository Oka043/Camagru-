<?php 
	if (isset($_SESSION["user_id"])) {
		header('Location: /');
	} else { 
?>

<div class="row mx-auto">
	  <div class="panel panel-default mx-auto">
	    <div class="panel-body">
	      <div class="text-center">
	        <h3>Enter a new password.</h3>
	        <?php if ($smsg != '') { ?> <div class="alert alert-success" role="alert"><?php echo $smsg ?></div> <?php } ?>
					<?php if ($fmsg != '') { ?> <div class="alert alert-danger" role="alert"><?php echo $fmsg ?></div> <?php } ?>
	        <div class="panel-body">

	          <form class="form-signin" method="post">
	  	        <div class="userSettingsField">
			          <div>Password:</div>
			          <input class="form-control" name="password" type="password" value="111111111111A">
			        </div>
			        <div class="userSettingsField">
			          <div>Confirm password:</div>
			          <input class="form-control" name="passRepeat" type="password" value="111111111111A">
			        </div>
			        <br>
	            <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
	          </form>

	        </div>
	      </div>
	    </div>
	  </div>
	</div>

<?php } ?>



