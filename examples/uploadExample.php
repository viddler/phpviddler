<?php
// You can run this on your server or via command line.

include('../phpviddler.php');

$api_key  = '';
$user     = '';
$password = '';
$file     = '';

$viddler = new Viddler_V2($api_key);
 
 
$user = $viddler->viddler_users_auth(array('user'=>$user, 'password'=>$password));
 
$params = array(
  'sessionid'=>$user['auth']['sessionid'],
  'title'=>'test20101215', 
  'tags'=>'tag1,tag2,tag3',
  'description'=>'desc here',
  'file'=>'@' . $file
  );

$prepare = $viddler->viddler_videos_prepareUpload(array('sessionid' => $user['auth']['sessionid']));
$results = $viddler->viddler_videos_upload($params, $prepare['upload']['endpoint']);
 
print_r($results); echo "\n";


?>