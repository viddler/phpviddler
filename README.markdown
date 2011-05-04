phpViddler API v2 Library
http://developers.viddler.com/documentation/
================================

A PHP class to wrap Viddler's API version 2. To access version 1 of PHPViddler please see [the v1.0 tag on GitHub](https://github.com/viddler/phpviddler/tree/v1.0).

For more information, visit [our developer site](http://developers.viddler.com/)

Installation Instructions
--------------------------------
PHPViddler is fully OOP. Like snOOP dogg. Ok, not like him.

1. [Download the most recent version](https://github.com/viddler/phpviddler/tree/v2.0).
2. Upload phpviddler.php
3. Include phpviddler.php
4. Initiate Viddler class like this $v = new Viddler_V2('YOUR API KEY HERE');

Usage
--------------------------------
    $v = new Viddler_V2('Your API Key');
    
    // Example find videos by user
    $videos = $v->viddler_videos_getByUser('viddlerdevtest');
    foreach($videos['list_result']['video_list'] as $video) {
      print_r($video);
    }
    
Notes
--------------------------------
viddler_videos_upload() can accept a second parameter for defining the upload end-point that the API has given you to use. This is best practice for the very quickest upload possible. Find the example code in /examples/uploadExample.php and also the How to: Upload video article: http://developers.viddler.com/documentation/articles/howto-upload-video/

Included in PHPviddler is an /examples/ directory with a few code examples. These have been updated to use version 2 of our API and include GET, POST, and upload examples.

Embedding
--------------------------------
Viddler now supports a few different embed codes types. To retrieve a proper embed code, you may want to use: http://developers.viddler.com/documentation/api-v2/#toc-viddler-videos-getembedcodetypes  and then grab an embed code with http://developers.viddler.com/documentation/api-v2/#toc-viddler-videos-getembedcode

Need help?
--------------------------------
Subscribe to our Developer's Mailing List and ask the question there. Both the Viddler development community and team members are subscribed to help you: http://groups.google.com/group/viddler-development-talk


Licensing
--------------------------------
phpViddler is dual-licensed under the MIT License. The details of this can be found MITlicense.txt
