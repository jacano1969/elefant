<?php

/**
 * This is the settings form for the user app.
 */

$this->require_admin ();

require_once ('apps/admin/lib/Functions.php');

$page->layout = 'admin';
$page->title = __ ('User Settings');

$form = new Form ('post', $this);

$appconf['User']['login_methods'] = is_array ($appconf['User']['login_methods'])
	? $appconf['User']['login_methods']
	: array ();

$form->data = array (
	'facebook_app_id' => $appconf['Facebook']['application_id'],
	'facebook_app_secret' => $appconf['Facebook']['application_secret'],
	'twitter_key' => $appconf['Twitter']['consumer_key'],
	'twitter_secret' => $appconf['Twitter']['consumer_secret'],
	'login_openid' => in_array ('openid', $appconf['User']['login_methods']),
	'login_google' => in_array ('google', $appconf['User']['login_methods']),
	'login_facebook' => in_array ('facebook', $appconf['User']['login_methods']),
	'login_twitter' => in_array ('twitter', $appconf['User']['login_methods']),
	'login_persona' => in_array ('persona', $appconf['User']['login_methods'])
);

echo $form->handle (function ($form) {
	$login_methods = array ();
	if ($_POST['login_openid'] === 'yes') {
		$login_methods[] = 'openid';
	}
	if ($_POST['login_google'] === 'yes') {
		$login_methods[] = 'google';
	}
	if ($_POST['login_facebook'] === 'yes') {
		$login_methods[] = 'facebook';
	}
	if ($_POST['login_twitter'] === 'yes') {
		$login_methods[] = 'twitter';
	}
	if ($_POST['login_persona'] === 'yes') {
		$login_methods[] = 'persona';
	}
	if (count ($login_methods) === 0) {
		$login_methods = false;
	}

	if (! Ini::write (
		array (
			'User' => array (
				'login_methods' => $login_methods
			),
			'Facebook' => array (
				'application_id' => $_POST['facebook_app_id'],
				'application_secret' => $_POST['facebook_app_secret']
			),
			'Twitter' => array (
				'consumer_key' => $_POST['twitter_key'],
				'consumer_secret' => $_POST['twitter_secret']
			)
		),
		'conf/app.user.' . ELEFANT_ENV . '.php'
	)) {
		printf ('<p>%s</p>', __ ('Unable to save changes. Check your folder permissions and try again.'));
		return;
	}

	$form->controller->add_notification (__ ('Settings saved.'));
	$form->controller->redirect ('/user/admin');
});

?>