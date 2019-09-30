<div class="containerSettings">
	<?php if (isset($smsg) && $smsg != '') { ?> <div class="alert alert-success" role="alert"><?php echo $smsg ?></div> <?php } ?>
  <?php if (isset($fmsg) && $fmsg != '') { ?> <div class="alert alert-danger" role="alert"><?php echo $fmsg ?></div> <?php } ?>
  <h1>Edit Profile</h1>
  <div class="userSettings">
    <!-- left column -->
    <div class="avatar">
      
      <div class="userAvatar">
      	<?php 
      		if (isset($avatar_src)) 
						echo "<img src="."/".$avatar_src." alt='avatar'>";
					else {
						if (isset($gender) && $gender == 1) {
							echo "<img src='/images/avatars/avatar_man.png' alt='avatar'>";
						} else if (isset($gender) && $gender == 2) {
							echo "<img src='/images/avatars/avatar_woman.png' alt='avatar'>";
						} else {
							echo "<img src='/images/avatars/avatar_unisex.png' alt='avatar'>";
						}
					}
				?>
        <label class="uploadAvatar" for="uploadAvatar">Upload...</label>
      </div>
    </div>

    <!-- edit form column -->
    <div class="userInfo">
      <h3>Personal info</h3>

      <form role="form" method="POST" enctype="multipart/form-data">
        <div class="userSettingsField">
          <div>First name:</div>
          <input class="form-control" name="first_name" type="text" value=<?php echo isset($first_name) ? $first_name : "" ?>>
        </div>
        <div class="userSettingsField">
          <div>Last name:</div>
          <input class="form-control" name="last_name" type="text" value=<?php echo isset($last_name) ? $last_name : "" ?>>
        </div>
        <div class="userSettingsField">
          <div>Email:</div>
          <input class="form-control" name="email" type="email" value=<?php echo isset($email) ? $email : "" ?>>
        </div>
        <div class="userSettingsField">
          <div>Username:</div>
          <input class="form-control" name="login" type="text" value=<?php echo isset($login) ? $login : "" ?>>
        </div>
        <div class="userSettingsField">
          <div>Password:</div>
          <input class="form-control" name="password" type="password" value="111111111111Aa">
        </div>
        <div class="userSettingsField">
          <div>Confirm password:</div>
          <input class="form-control" name="passRepeat" type="password" value="111111111111Aa">
        </div>

        <div class="userSettingsField">
          <div>About you:</div>
          <textarea class="form-control" name="bio" type="text" rows="3"><?php echo (isset($bio) && $bio != '') ? $bio : "" ?></textarea>
        </div>

				<div class="mt-2 form-check form-check-inline">
		      <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="1" required <?php echo (isset($gender) && $gender == 1) ? "checked" : "" ; ?>>
		      <label class="form-check-label" for="inlineRadio1">Male</label>
		    </div>
		    <div class="mt-2 form-check form-check-inline">
		      <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="2" required <?php echo (isset($gender) && $gender == 2) ? "checked" : "" ;  ?>>
		      <label class="form-check-label" for="inlineRadio2">Female</label>
		    </div>

        <div class="userSettingsField">
          <label class="recieveEmailsCheckbox">
          	<span>Recieve Emails:</span>
          	<?php if (isset($recieve_mails) && $recieve_mails == 1) { ?>
          		<input type="checkbox" name="recieveEmails" checked>
        		<?php } else { ?>
            	<input type="checkbox" name="recieveEmails">
          	<?php } ?>
            <span class="checkmark"></span>
          </label>
        </div>
        <input id="uploadAvatar" type="file" name="avatar" style="display:none">
        <button class="btn btn-block btn-success mr-2" type="submit" >Save Changes</button>
      </form>
    </div>
  </div>
</div>