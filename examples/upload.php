<?php
//Direct upload example, preferred upload method
include('../phpviddler.php');

$user = 'YOUR USERNAME';
$pass = 'YOUR PASSWORD';
$api_key = 'YOUR API KEY';
$callback_url = 'CALLBACK';

$v = new Viddler_V2($api_key);

// Get a sessionid
$auth = $v->viddler_users_auth(array('user' => $user, 'password' => $pass));

$sessionid = $auth['auth']['sessionid'];

// Call prepareUpload to retrieve the token and endpoint we need to use
$prepare_resp = $v->viddler_videos_prepareUpload(array('sessionid' => $sessionid));

$upload_server = $prepare_resp['upload']['endpoint'];
$upload_token  = $prepare_resp['upload']['token'];

?>

<form method="post" action="<?= $upload_server ?>" enctype="multipart/form-data">
  <input type="hidden" name="uploadtoken" value="<?= $upload_token ?>" />
  <input type="hidden" name="callback" value="<?= $callback_url ?>" />
  <label>Title:</label> <input type="text" name="title" /><br />
  <label>Description:</label> <input type="text" name="description" /><br />
  <label>Tags:</label> <input type="text" name="tags" /><br />
  <label>File:</label> <input type="file" name="file" /><br />
  <input type="submit" value="Upload" />
</form>