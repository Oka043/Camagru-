<?php 

return [
	'' => [
		'controller' => 'main',
		'action' => 'index',
	],



	// Profile managing.
	'login' => [
		'controller' => 'account',
		'action' => 'login',
	],

	'logout' => [
		'controller' => 'account',
		'action' => 'logout',
	],

	'register' => [
		'controller' => 'account',
		'action' => 'register',
	],

	'activate' => [
		'controller' => 'account',
		'action' => 'activateProfile',
	],

	'forgot_pass' => [
		'controller' => 'account',
		'action' => 'forgotPassword',
	],

	'restore_pass' => [
		'controller' => 'account',
		'action' => 'restorePassword',
	],


	// Profile 
	'profile/settings' => [
		'controller' => 'profile',
		'action' => 'settings',
	],

	'profile/gallery' => [
		'controller' => 'profile',
		'action' => 'gallery',
	],

	'profile/picture' => [
		'controller' => 'profile',
		'action' => 'picture',
	],

	'profile/make_picture' => [
		'controller' => 'profile',
		'action' => 'makePicture',
	],

	'profile/followers' => [
		'controller' => 'profile',
		'action' => 'followers',
	],

	'profile/following' => [
		'controller' => 'profile',
		'action' => 'following',
	],

	'profile/likes' => [
		'controller' => 'profile',
		'action' => 'likes',
	],

	

	// Javascript Requests managing.
	'requests/follow_unfollow' => [
		'controller' => 'HttpRequests',
		'action' => 'followUnfollow',
	],

	'requests/like_unlike' => [
		'controller' => 'HttpRequests',
		'action' => 'likeUnlike',
	],

	'requests/get_stikers' => [
		'controller' => 'HttpRequests',
		'action' => 'getAllStikers',
	],

	'requests/merge_save_pictures' => [
		'controller' => 'HttpRequests',
		'action' => 'mergeAndSave',
	],

	'requests/delete_picture' => [
		'controller' => 'HttpRequests',
		'action' => 'deletePicture',
	],

]

?>