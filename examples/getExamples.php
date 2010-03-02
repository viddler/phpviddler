<?php

// These examples are in PHP 5.

include('../php5viddler.php');
$v = new Phpviddler(''); // Get an API key by going to You > Profile & API on Viddler.

?>
<!DOCTYPE html>
<html>
	<head>
		<title>PHPViddler GET Examples</title>
		
	</head>
	
	<body>
		<h1>PHPViddler GET Examples</h1>
		<p>A few simple examples of retrieving some videos from Viddler's API using PHPViddler.</p>
		
		
		<h2><a href="http://developers.viddler.com/documentation/api/method-videos-getbyuser/">viddler.videos.getByUser</a></h2>
		<p>Some videos based on a user - viddlerdevtest (a Viddler account we use for testing)</p>
		<p><?php 
		
		// Get videos (page 1, videos 5)
		$videos = $v->videos_listbyuser('viddlerdevtest',1,5);
		
		// Loop through videos showing just the thumbnail
		// with a link to the video itself.
		foreach($videos['video_list']['video'] as $video) {
			echo '<a href="'.$video['url'].'"><img src="'.$video['thumbnail_url'].'" alt="thumbnail" width="60" /></a> ';
		}
		?></p>
		
		
		<h2><a href="http://developers.viddler.com/documentation/api/method-videos-search/">viddler.videos.search</a></h2>
		<p>Search all of Viddler's public videos for "iPhone".</p>
		<p><?php 
		
		// Search videos: type=allvideos, query=iphone, videos 5
		$videos = $v->video_search(array('type'=>'allvideos','query'=>'iphone','per_page'=>5));
		
		// Loop through videos showing just the thumbnail
		// with a link to the video itself.
		foreach($videos['video_list']['video'] as $video) {
			echo '<a href="'.$video['url'].'"><img src="'.$video['thumbnail_url'].'" alt="thumbnail" width="60" /></a> ';
		}
		?></p>
		
		
		<h2><a href="http://developers.viddler.com/documentation/api/method-users-getprofile/">viddler.users.getProfile</a></h2>
		<p>Show a user's profile.</p>
		<?php 
		
		// Search videos: type=allvideos, query=iphone, videos 5
		$userInfo = $v->user_profile('viddlerdevtest');
		
		echo '<p><strong>Username:</strong> '.$userInfo['user']['username'].'</p>';
		echo '<p><strong>Avatar:</strong> <img src="'.$userInfo['user']['avatar'].'" /></p>';
		echo '<p><strong>Number of videos:</strong> '.$userInfo['user']['video_upload_count'].'</p>';
	
		?>
		<p>Note: If Avatar is blank you should use default. More profile variables are available, review doc.</p>
		
		<h2>Creating a Viddler short URL</h2>
		<p>Create a short URL using http://go.viddler.com/ using the following URL as an example:<br />URL: <a href="http://www.viddler.com/explore/cdevroe/videos/133/">http://www.viddler.com/explore/cdevroe/videos/133/</a></p>
		
		<?php $shortURL = $v->video_go('http://www.viddler.com/explore/cdevroe/videos/133/');?>
		<p>Short URL: <a href="<?=$shortURL;?>"><?=$shortURL;?></a></p>
		
		<p>Please feel free to add more examples. Most GET requests return similar results though.</p>
		
		
		
		
	</body>
</html>