<?php

// These examples are in PHP 5.

include('../php5viddler.php');
$v = new Phpviddler('01171d24e48b43444556524f45b3'); // Get an API key by going to You > Profile & API on Viddler.

?>
<!DOCTYPE html>
<html>
	<head>
		<title>PHPViddler POST Examples</title>
		
	</head>
	
	<body>
		<h1>PHPViddler POST Examples</h1>
		<p>A few simple examples of POSTing data to Viddler's API using PHPViddler.</p>
		
		<h2><a href="http://developers.viddler.com/documentation/api/method-users-auth/">viddler.users.auth</a></h2>
		<p>A quick example of authenticating a user.</p>
		
		<form method="post" action="postExamples.php">
			<p><label for="username">Username:</label> <input type="text" id="username" name="username" /></p>
			<p><label for="password">Password:</label> <input type="password" id="password" name="password" /></p>
			<p><input type="submit" value=" Test login " />
		</form>
		
		<p><?php 
		
		if ($_POST) {
			// Search videos: type=allvideos, query=iphone, videos 5
			$user = $v->user_authenticate($_POST['username'],$_POST['password']);
			
			// Check for authentication error
			if (isset($user['error'])) {
				echo 'There was an error trying to log in the user. Username and/or Password is incorrect.';
			} else {
				echo 'The username/password is valid and this is the SessionID: '.$user['auth']['sessionid'];
			}
		
		} // end $_POST

		?></p>
		<p><small>Notes: Session IDs last for 15 minutes after inactivity but are automatically renewed with activity. Adding a "1" as a final argument will return a record token that can be used to record a video <a href="http://developers.viddler.com/documentation/articles/howto-record/">using Viddler's Flash video recorder</a>.</small></p>
		
		<h2><a href="http://developers.viddler.com/documentation/api/method-videos-getrecordtoken/">viddler.videos.getRecordToken</a></h2>
		<p>This method simply returns a record token using a Session ID. It is recommended that, if possible, you use the viddler.users.auth method when generating a new record token because it also generates a new Session ID.</p>
		
		<?php if($_POST && !isset($user['error'])) {
			echo '<p>Record token: '.$v->video_getrecordtoken($user['auth']['sessionid']).'</p>';
		} else {
			echo '<p>Fill out the authentication form example above to generated a record token.</p>';
		} ?>
		
		<p><small>Notes: Unlike a Session ID a record token <em>never expires</em> but can only be used once.</small></p>
		
		<h2>Left TO DO</h2>
		<p><strike>viddler.users.auth</strike>, viddler.videos.upload, <strike>viddler.videos.getRecordToken</strike>, viddler.videos.setThumbnail.</p>
		
		
		
		
	</body>
</html>