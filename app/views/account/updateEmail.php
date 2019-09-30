<div class="container mt-5">
  <h3>Update Email Settings</h3>
  <?php if ($smsg != '') { ?> 
  	<div class="alert alert-success" role="alert"><?php echo $smsg ?></div> 
	<?php } ?>
  <?php if ($fmsg != '') { ?>

    <div class="alert alert-danger" role="alert"><?php echo $fmsg ?></div>
    <form class="form-activate" method="POST">
      <input type="email" name="email" class="form-control" placeholder="Email" required>
      <br>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Send again</button>

    </form>
  <?php } ?>
</div>