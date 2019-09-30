<div class="row mx-auto">
  <div class="panel panel-default mx-auto">
    <div class="panel-body">
      <div class="text-center">
        <h3><i class="fa fa-lock fa-4x"></i></h3>
        <h2 class="text-center">Forgot password?</h2>
				<?php if ($smsg != '') { ?> <div class="alert alert-success" role="alert"><?php echo $smsg ?></div> <?php } ?>
				<?php if ($fmsg != '') { ?> <div class="alert alert-danger" role="alert"><?php echo $fmsg ?></div> <?php } ?>
        <p>You can restore it using mail.</p>
        <div class="panel-body">

          <form class="form-signin" method="post">
            <input name="email" placeholder="email address" class="form-control"  type="email">
            <br>
            <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
          </form>

        </div>
      </div>
    </div>
  </div>
</div>