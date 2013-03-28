<?php
//Example to auth as you and get your videos

include '../phpviddler.php';

//Create viddler object using HTTPS:443
$v = new Viddler_V2('YOUR API KEY', TRUE);

//Authenticate as you
//Always a good idea even if your profile is public
//Because if at anytime you turn to a private account
//Your application will still work ;)
$auth = $v->viddler_users_auth(array(
  'user'      => 'YOUR USERNAME',
  'password'  => 'YOUR PASSWORD'
));

//Get your session id
$session_id = (isset($auth['auth']['sessionid'])) ? $auth['auth']['sessionid'] : '';

//If no session id, print errors
if (! empty($session_id)) {
  $info = $v->viddler_users_getProfile(array(
    'sessionid' =>  $session_id
  ));
  
  //If any errors print them out
  //This should NOT be done in a production application
  //An exception should be used instead
  //For example purposes only
  if (isset($info['error'])) {
    echo '<pre>';
    print_r($info);
    echo '</pre>';
    exit;
  }

}
//In a live example you SHOULD NOT show errors, rather write an exception
else {
  echo '<pre>';
  print_r($auth);
  echo '</pre>';
  exit;
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Get Your Profile</title>
	</head>
	<body>
		<h1>Get Your Profile</h1>
		<p>A few simple examples of retrieving some videos from Viddler's API using PHPViddler.</p>
		<h2><a href="http://developers.viddler.com/documentation/api-v2/#toc-viddler-users-getprofile">viddler.users.getProfile</a></h2>
		<p>Show your profile.</p>
		<p>Username: <?=$info['user']['username']?></p>
		<p>Avatar: <img src="<?=$info['user']['avatar']?>" /></p>
		<p>Total Videos: <?=$info['user']['video_upload_count']?></p>
		<p>Note: If Avatar is blank you should use default. More profile variables are available, review doc.</p>
		<p>Please feel free to add more examples. Most GET requests return similar results.</p>
	</body>
</html>