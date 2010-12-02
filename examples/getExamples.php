<?php

include('../phpviddler2.php');
$v = new Viddler_V2('01171d24e48b43444556524f45b3'); // Get an API key by going to You > Profile & API on Viddler.

?>
<!DOCTYPE html>
<html>
	<head>
		<title>PHPViddler API v2 GET Examples</title>
		
	</head>
	
	<body>
		<h1>PHPViddler GET Examples</h1>
		<p>A few simple examples of retrieving some videos from Viddler's API using PHPViddler.</p>
		
		
		<h2><a href="http://developers.viddler.com/documentation/api-v2/">viddler.videos.getByUser</a></h2>
		<p>Some videos based on a user - viddlerdevtest (a Viddler account we use for testing)</p>
		<p><?php 
		
		// Get videos (page 1, videos 5)
		$videos = $v->viddler_videos_getByUser(array('user'=>'viddlerdevtest','per_page'=>5,'page'=>1));
		
		/* Debug only 
		echo '<pre>';
		print_r($videos);
		echo '</pre>';
		*/
		
		// Loop through videos showing just the thumbnail
		// with a link to the video itself.
		foreach($videos['list_result']['video_list'] as $video) {
			echo '<a href="'.$video['url'].'"><img src="'.$video['thumbnail_url'].'" alt="thumbnail" width="60" /></a> ';
		}
		?></p>
		
		
		<h2><a href="http://developers.viddler.com/documentation/api-v2/">viddler.videos.search</a></h2>
		<p>Search all of Viddler's public videos for "iPhone".</p>
		<p><?php 
		
		// Search videos: type=allvideos, query=iphone, videos 5
		$videos = $v->viddler_videos_search(array('type'=>'allvideos','query'=>'iphone','per_page'=>5));
		

		/* Debug only 
		echo '<pre>';
		print_r($videos);
		echo '</pre>';
		*/

		
		// Loop through videos showing just the thumbnail
		// with a link to the video itself.
		foreach($videos['list_result']['video_list'] as $video) {
			echo '<a href="'.$video['url'].'"><img src="'.$video['thumbnail_url'].'" alt="thumbnail" width="60" /></a> ';
		}
		?></p>
		
		
		<h2><a href="http://developers.viddler.com/documentation/api-v2/">viddler.users.getProfile</a></h2>
		<p>Show a user's profile.</p>
		<?php 
		
		// Search videos: type=allvideos, query=iphone, videos 5
		$userInfo = $v->viddler_users_getProfile(array('user'=>'cdevroe'));
		
		/*
		echo '<pre>';
		print_r($userInfo);
		echo '</pre>';
		*/
		
		echo '<p><strong>Username:</strong> '.$userInfo['user']['username'].'</p>';
		echo '<p><strong>Avatar:</strong> <img src="'.$userInfo['user']['avatar'].'" /></p>';
		echo '<p><strong>Number of videos:</strong> '.$userInfo['user']['video_upload_count'].'</p>';
	
		?>
		<p>Note: If Avatar is blank you should use default. More profile variables are available, review doc.</p>
		
		<p>Please feel free to add more examples. Most GET requests return similar results though.</p>
		
	</body>
</html>