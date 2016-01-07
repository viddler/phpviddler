phpViddler API v2 Library
http://developers.viddler.com/documentation/
================================

A PHP class for Viddler's API version 2.
For more information, visit [our developer site](http://developers.viddler.com/)


Important!
--------------------------------
Please see the new cURL option for TLS1.2 connectivity issues.

Before updating to version 4.2 (or higher), Please note the following:

Due to PHP 5.5.0 deprecating the "@" method of cURL binary transfer in favor of the [CURLFile class](http://php.net/manual/en/class.curlfile.php), viddler.videos.setThumbnail's 'file' parameter must only be the path to the file:
	
	// OLD
	$v->viddler_videos_setThumbnail(array(
		'sessionid' => SESSIONID,
		'video_id' => VIDEO_ID,
		'file' => '@./path/to/file.jpg',
		));
	// NEW
	$v->viddler_videos_setThumbnail(array(
		'sessionid' => SESSIONID,
		'video_id' => VIDEO_ID,
		'file' => './path/to/file.jpg',
		));

The wrapper will handle the rest depending on the PHP version installed.

Installation Instructions
--------------------------------
1. [Download the most recent version](https://github.com/viddler/phpviddler/downloads).
2. Upload phpviddler.php
3. Include phpviddler.php
4. Initiate Viddler class like this $v = new Viddler_V2('YOUR API KEY HERE');

Usage
--------------------------------
    $v = new Viddler_V2('Your API Key');
    
    // Authenticate and get your videos
    $auth = $v->viddler_users_auth(array('user'=>'USERNAME','password'=>'PASSWORD'));
    if (isset($auth['auth']['sessionid'])) {
      $videos = $v->viddler_videos_getByUser(array('sessionid'=>$auth['auth']['sessionid']));
      print '<pre>';
      print_r($videos);
      print '</pre>';
    }
    
Examples
--------------------------------
Included in this repo is an /examples/ directory with a few code examples from uploading, uploading with curl, searching, get your profile and getting your videos.

Embedding
--------------------------------
Viddler supports a few different embed codes types. To retrieve a proper embed code, you may want to use: [viddler.videos.getEmbedCodeTypes](http://developers.viddler.com/documentation/api-v2/#toc-viddler-videos-getembedcodetypes)  and then grab an embed code with [viddler.videos.getEmbedCode](http://developers.viddler.com/documentation/api-v2/#toc-viddler-videos-getembedcode)

Need help?
--------------------------------
Submit a ticket[here](https://support.viddler.com)


Licensing
--------------------------------
phpViddler is dual-licensed under the MIT License. The details of this can be found MITlicense.txt


Changelog (started with tag 3.9) - Read overall API changelog [here](http://developers.viddler.com/documentation/api-changelog/)
--------------------------------

### 4.2 - July 18, 2014

- Updated binary transfer method for viddler.videos.setThumbnail to use CURLFile class in PHP 5.5.0+
- Organized method list
- Added version number to top comment (for convenience)
- Version number no longer tied to internal platform versioning
- Added composer.json (Thanks Israel Shirk!)

### 4.1 - November 27, 2012

- Add new methods: viddler.logins.[add|delete|update] to POST array

### 4.0.1 - November 19, 2012

- Fixed type of method viddler.resellers.removeSubaccount, was removeSubaccounts

### 4.0 - July 18, 2012

- Added direct upload example using cURL
- Removed method viddler.videos.upload
- Updated README file

### 3.9 - April 26, 2012

- Jumped tag numbers like whoa to keep consistent with internal versioning
- Added new method 'viddler.videos.addClosedCaptioning' (POST)
- Added new method 'viddler.videos.delClosedCaptioning' (POST)
- Added new method 'viddler.videos.setClosedCaptioning' (POST)
- Updates to viddler.encoding.(set|get)Settings
- Added new method viddler.videos.comments.get (GET)
- Updates to viddler.videos.(get|set)Details
- Added new examples in the examples directory
- Added support for HTTPS for all calls
