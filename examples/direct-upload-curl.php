<?
//Set no time limit (in case of big uploads)
set_time_limit(0);

//Include your viddler API wrapper
include '../phpviddler.php';

/**
1. Create viddler object
2. Authenticate as you
3. Set session id
**/
$v            = new Viddler_V2('YOUR API KEY');
$auth         = $v->viddler_users_auth(array('user'=>'YOUR USERNAME','password'=>'YOUR PASSWORD'));
$session_id   = (isset($auth['auth']['sessionid'])) ? $auth['auth']['sessionid'] : NULL;

/**
If session id is empty, something went wrong.
You probably want to handle the error better,
but this is for example purposes only.
**/
if (empty($session_id)) {
  print '<pre>';
  print_r($auth);
  print '</pre>';
  exit;
}

/**
1. Call prepareUpload
2. Get endpoint
3. Get token
**/
$response = $v->viddler_videos_prepareUpload(array('sessionid' => $session_id));
$endpoint = (isset($response['upload']['endpoint'])) ? $response['upload']['endpoint'] : NULL;
$token    = (isset($response['upload']['token'])) ? $response['upload']['token'] : NULL;

/**
Check for endpoint and token.
As in above, you will want to handle
this error better.
**/
if (empty($endpoint) || empty($token)) {
  print '<pre>';
  print_r($response);
  print '</pre>';
  exit;
}

/**
Set your direct upload form variables.
PHP 5.2.0+ requires these to be in array format.
Documentation: http://php.net/manual/en/function.curl-setopt.php (Search for 'CURLOPT_POSTFIELDS')

Please keep variables in this order. 

Also notice the '@' sign in front of the file.
This is required by curl to know it's binary.
After the @ sign should be the path to the file.
This example has the file in the same directory
as the example. If in a different directory I would
suggest using the full path.

IE: @/var/www/vhosts/yoursite.com/tmp/test.mov
**/
$query          =   array(
  'uploadtoken' =>  $token,
  'title'       =>  'Direct 2 Step',
  'description' =>  'This is a test for uploading from server to server.',
  'tags'        =>  'testing,upload',
  'file'        =>  '@test.mov'
);

/**
Start up curl

Example below tells curl to:
1. return the result rather than print to screen
2. follow any redirects
3. return the headers
4. return the body
5. have no timeout
6. use POST method
7. set POSTFIELDS
**/
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_HEADER, TRUE);
curl_setopt($ch, CURLOPT_NOBODY, FALSE);
curl_setopt($ch, CURLOPT_TIMEOUT, 0);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
$response     = curl_exec($ch);
$info         = curl_getinfo($ch);
$header_size  = $info['header_size'];
$header       = substr($response, 0, $header_size);
$video        = unserialize(substr($response, $header_size));
curl_close($ch);

/**
You can easily check for errors in the upload

1. If no video.id is found, print errors
    - Again, you will want to log this, not just print errors out :)
    
2. Else print video info
    - Again, you will not want to print this out but more than likely log it
**/
if (! isset($video['video']['id'])) {
  print 'Error Code: ' . $video['error']['code'] . '<br />';
  print 'Error Description: ' . $video['error']['description'] . '<br />';
  print 'Error Details: ' . $video['error']['details'] . '<br /><br />';
}
else {
  print 'Video ID: ' . $video['video']['id'] . '<br />';
  print 'Video Title: ' . $video['video']['title'] . '<br />';
  print 'Video Description: ' . $video['video']['description'] . '<br />';
  print 'Video URL: ' . $video['video']['url'] . '<br />';
  print 'Video Thumbnail: ' . $video['video']['thumbnail_url'] . '<br /><br />';
}


/**
Example print outs. Again you want to handle these better
but they are for example purposes only.

1. All curl info returned
    - This is handy info, tells you connection times, header sizes,
      body size, http codes, etc. Handy to debug
    
2. Print out of the headers returned
    - Again this is handy to debug with, do with it what you want
    
3. Print out of the video info returned
    - This will be the result form the API in serialized PHP format (if using PHP as response type)
      If successful, it will return the video id, title, description, url and thumbnail_url in a video object
        IE: video.id, video.title, video.description, video.url, video.thumbnail_url
**/
print '<pre>';
print_r($info);
print_r($header);
print_r($video);
print '</pre>';