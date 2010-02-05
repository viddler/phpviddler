<?php

// These examples are in PHP 5.

include('../php5viddler.php');
$v = new Phpviddler('YOUR API KEY HERE'); // Get an API key by going to You > Profile & API on Viddler.

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
		
		<p>Please feel free to add more. Most GET requests return similar results though.</p>
		
		
		
		
	</body>
</html>