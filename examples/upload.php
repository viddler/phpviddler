<?php
//Direct upload example, preferred upload method
include('../phpviddler.php');

//Create viddler object using HTTP:80
$v = new Viddler_V2('YOUR API KEY');

// Get a sessionid
$auth = $v->viddler_users_auth(array(
  'user'      => 'YOUR USERNAME', 
  'password'  => 'YOUR PASSWORD'
));

$session_id = (isset($auth['auth']['sessionid'])) ? $auth['auth']['sessionid'] : '';

if (! empty($session_id)) {
  // Call prepareUpload to retrieve the token and endpoint we need to use
  $prepare_resp = $v->viddler_videos_prepareUpload(array('sessionid' => $session_id));
  
  //If any errors print them out
  //This should NOT be done in a production application
  //An exception should be used instead
  //For example purposes only
  if (isset($prepare_resp['error'])) {
    echo '<pre>';
    print_r($prepare_resp);
    echo '</pre>';
    exit;
  }
  else {
    $upload_server = $prepare_resp['upload']['endpoint'];
    $upload_token  = $prepare_resp['upload']['token'];
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

<form method="post" action="<?= $upload_server ?>" enctype="multipart/form-data">
  <input type="hidden" name="uploadtoken" value="<?= $upload_token ?>" />
  <input type="hidden" name="callback" value="http://www.YOURCALLBACKURL.COM" />
  <label>Title:</label> <input type="text" name="title" /><br />
  <label>Description:</label> <input type="text" name="description" /><br />
  <label>Tags:</label> <input type="text" name="tags" /><br />
  <label>File:</label> <input type="file" name="file" /><br />
  <input type="submit" value="Upload" />
</form>