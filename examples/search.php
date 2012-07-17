<?php
//Example to auth as you and get your videos

include '../phpviddler.php';

//Create viddler object using HTTP:80
$v = new Viddler_V2('YOUR API KEY');

// Search videos: type=allvideos, query=iphone, videos 5
$videos = $v->viddler_videos_search(array(
  'type'      =>  'allvideos',
  'query'     =>  'iphone',
  'per_page'  =>  5
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

//Set videos
$videos = (isset($videos['list_result']['video_list'])) ? $videos['list_result']['video_list'] : array();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Search All Videos</title>
	</head>
	<body>
		<h1>Search All Videos</h1>
		<p>A few simple examples of retrieving some videos from Viddler's API using PHPViddler.</p>
		<h2><a href="http://developers.viddler.com/documentation/api-v2/">viddler.videos.search</a></h2>
		<p>Search all of Viddler's public videos for "iPhone".</p>
		<? if (count($videos) > 0): ?>
		  <? foreach ($videos as $video): ?>
    	 <p><a href="<?=$video['url']?>"><img src="<?=$video['thumbnail_url']?>" alt="thumbnail" width="60" /></a></p>
     <? endforeach; ?>
		<? else: ?>
		  <p>Sorry, no results.</p>
		<? endif; ?>
	</body>
</html>