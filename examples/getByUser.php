<?php
//Example to auth as you and get your videos

include('../phpviddler.php');

//Create viddler object using HTTPS:443
$v = new Viddler_V2('YOUR API KEY', TRUE);

//Authenticate as you
//Always a good idea even if your profile/videos are public
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
  $videos = $v->viddler_videos_getByUser(array(
    'sessionid' =>  $session_id,
    'per_page'  =>  5,
    'page'      =>  1
  ));
  
  //If any errors print them out
  //This should NOT be done in a production application
  //An exception should be used instead
  //For example purposes only
  if (isset($videos['error'])) {
    echo '<pre>';
    print_r($videos);
    echo '</pre>';
    exit;
  }
  
  $videos = (isset($videos['list_result']['video_list'])) ? $videos['list_result']['video_list'] : array();
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
		<title>Get Your Videos</title>
	</head>
	<body>
		<h1>Get Your Videos</h1>
		<p>A few simple examples of retrieving some videos from Viddler's API using PHPViddler.</p>
		<h2><a href="http://developers.viddler.com/documentation/api-v2/#toc-viddler-videos-getbyuser">viddler.videos.getByUser</a></h2>
		<p>Some videos based on a user - viddlerdevtest (a Viddler account we use for testing)</p>
    <? if (count($videos) > 0): ?>
		  <? foreach ($videos as $video): ?>
    	 <p><a href="<?=$video['url']?>"><img src="<?=$video['thumbnail_url']?>" alt="thumbnail" width="60" /></a></p>
     <? endforeach; ?>
		<? else: ?>
		  <p>Sorry, no videos.</p>
		<? endif; ?>
	</body>
</html>