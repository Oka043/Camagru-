<script src="/public/js/deleteImage.js"></script>
<script src="/public/js/followUser.js"></script>

<div class="containerFoto">

	<article class="user-info">
		<header>
			
			<div class="user-avatar">
				<?php 
					echo "<a title=".$login;
					echo " href='/profile/gallery?user=".$user_id."'>";
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
	        echo "</a>";
	      ?>
			</div>

			<div class="user-info-container">
			
				<div class="username-container">
					
					<div class="username">
						<?php 
							if (isset($login)) {
								echo "<a title=".$login;
								echo " href='/profile/gallery?user=".$user_id."'>";
								echo "<b>".$login."</b>";
								echo "</a>";
							}
			      ?>
					</div>

					<?php 
						if (isset($user_id) && isset($_SESSION["user_id"])) {
					?>
						<div class="follow-container">
							
							<?php
								if (isset($_SESSION["user_id"]) 
										&& $_SESSION["user_id"] != $user_id) {
									echo "<span> • </span>";
									if (isset($followed) 
										&& $followed == true)
			              echo "<button type='button' class='follow-btn follow-unfollow' data-user_id=".$user_id.">Unfollow</button>";
			            else 
			              echo "<button type='button' class='follow-btn follow-unfollow' data-user_id=".$user_id.">Follow</button>";
			          }
							?>
						</div>
					<?php } ?>
				</div>
			</div>
		</header>
	</article>


	<div>
		<div class="user-image">
			<?php 
				if (isset($image_src)) {
					echo "<img src='/".$image_src."'>";
				}
			?>
		</div>
	</div>


	<div class="photo-comments">
		<div>
			<ul class="comments">

				<!-- Description -->
				<ul>
					<li>
						<!-- Avatar, Left side -->
						<div class="user-avatar">
							<?php 
								echo "<a title=".$login;
								echo " href='/profile/gallery?user=".$user_id."'>";
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
				        echo "</a>";
				      ?>
						</div>

						<!-- Description, right side -->
						<div class="message">
							<?php 
								if (isset($login)) {
									echo "<a title=".$login;
									echo " href='/profile/gallery?user=".$user_id."'>";
									echo "<b>".$login."</b>";
									echo "</a>";
								}
				      ?>

							<?php
								if (isset($desc)) {
									echo "<span>".$desc."</span>";
								}
							?>
							
							<?php 
								echo "<time datetime='".$date."'>";
								if (isset($diff_d) && $diff_d > 1) {
									echo $diff_d." days. ago";
								} else if (isset($diff_h) && $diff_h > 1) {
									echo $diff_h." h. ago";
								} else if (isset($diff_m) && $diff_m != 0) {
									echo $diff_m." min. ago";
								} else {
									echo "right now";
								}
							?>
						</div>
					</li>
				</ul>

				<!-- Comments -->
				<?php 
					if (isset($comments)) { 
						foreach ($comments as $key => $val) { 
				?>
					<ul>
						<li>
							<!-- Avatar, Left side -->
							<div class="user-avatar">
								<?php 
									echo "<a title=".$comments[$key]['login'];
									echo " href='/profile/gallery?user=".$comments[$key]['user_id']."'>";
					        if (isset($comments[$key]["avatar_src"])) {
					          echo "<img src="."/".$comments[$key]["avatar_src"]." alt='avatar'>";
					        } else {
					          if (isset($comments[$key]['gender']) && $comments[$key]['gender'] == 1) {
					            echo "<img src='/images/avatars/avatar_man.png' alt='avatar'>";
					          } else if (isset($comments[$key]['gender']) && $comments[$key]['gender'] == 2) {
					            echo "<img src='/images/avatars/avatar_woman.png' alt='avatar'>";
					          } else {
					            echo "<img src='/images/avatars/avatar_unisex.png' alt='avatar'>";
					          }
					        }
					        echo "</a>";
					      ?>
							</div>

							<!-- Message, Right side -->
							<div class="message">
								<!-- LOGIN -->
								<?php 
									if (isset($comments[$key]['login'])) {
										echo "<a title=".$comments[$key]['login'];
										echo " href='/profile/gallery?user=".$comments[$key]['user_id']."'>";
										echo "<b>".$comments[$key]['login']."</b>";
										echo "</a>";
  								}
					      ?>

								<!-- Comment -->
								<?php 
									if (isset($comments[$key]['comment'])) {
										echo "<span>".$comments[$key]['comment']."</span>";
  								}
					      ?>
					      <!-- Date -->
								<?php 
									echo "<time datetime='".$comments[$key]['date']."'>";
									if (isset($diff_d) && $diff_d > 1) {
										echo $diff_d." days. ago";
									} else if (isset($diff_h) && $diff_h > 1) {
										echo $diff_h." h. ago";
									} else if (isset($diff_m) && $diff_m != 0) {
										echo $diff_m." min. ago";
									} else {
										echo "right now";
									}
									echo "</time>";
								?>
							</div>
						</li>
					</ul>
				
				<?php 
						}
					}
				?>
					
				</ul>
			</ul>
		</div>


		<div class="footer">
			<?php if (isset($_SESSION["user_id"])) { ?>
				<section>
					<?php if ($user_like_this_image) {
							echo "<button id='like-btn' class='liked-image'>";
						} else {
							echo "<button id='like-btn' class='unliked-image'>";
						}
					?>
						<span  aria-label="Like"></span>
					</button>
				</section>
			<?php } ?>

			<section class="likes-info">
				<button type="button">
					<?php
						if (isset($total_likes)) { 
							echo "<b id='total-likes'>".$total_likes."</b> Likes";
						} else {
							echo "<b id='total-likes'>0</b> Likes";
						}
					?>
				</button>
			</section>

			<!-- время публикации -->
			<?php 
				if (isset($date)) {
					echo "<time datetime='".$date."'>";
				}

				if (isset($diff_d) && $diff_d > 1) {
					echo $diff_d." days. ago";
				} else if (isset($diff_h) && $diff_h > 1) {
					echo $diff_h." h. ago";
				} else if (isset($diff_m) && $diff_m != 0) {
					echo $diff_m." min. ago";
				} else {
					echo "right now";
				}

				if (isset($date)) {
					echo "</time>";
				}
			?>
		</div>


		<!-- проверка на пользователя -->
		<section class="user-menu">
			<?php if (!isset($_SESSION["user_id"])) { ?>
				<span>
					<a href="/login">Войдите</a>, чтобы поставить «Нравится» или прокомментировать.
				</span>
			<?php } else { ?>
				<div>
					<form class="comment-form" method="POST">

						<div class="comment-message">
							<textarea name="message" placeholder="Message"></textarea>
						</div>

						<div class="leave-comment-btn-container">
							<button class="leave-comment-btn" type="submit">
								<span> Leave a Message </span>
							</button>
						</div>
					</form>
				</div>
			<?php } ?>

		</section>
	</div>


</div>
