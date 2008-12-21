<?php
/*
	#######################################################
	#  Viddler API / PHP Wrapper
	#  By: Colin Devroe | cdevroe@viddler.com
	#
	#  Docs: http://wiki.developers.viddler.com/index.php/Phpviddler
	#
	#  License(s): Dual licensed under: 
	#  MIT (MIT-LICENSE.txt)
    #  GPL (GPL-LICENSE.txt)
    # 
    #  Third-party code:
    #  XML Library by Keith Devens
	#  xmlparser.php
	#
	#  Version 0.4
	########################################################
*/

include_once('config.php');

# XML Library, by Keith Devens, version 1.2b
# http://keithdevens.com/software/phpxml
include_once('xmlparser.php');

# Models
include_once('models.php');

class Phpviddler {

	var $apiKey;  // To obtain an API key: http://developers.viddler.com/
	var $viddlerREST = 'http://api.viddler.com/rest/v1/'; // REST URL Version 1.0
	var $parser = true; // Use the included XML parser? Default: true.
	var $debug = false; // Switch for debug mode


  function __construct($apiKey=false) {
    if($apiKey) {
      $this->apiKey = $apiKey;
    }
  }
/*##########  User functions ########### */
	
	/* viddler.users.register
	/ accepts: $userInfo(array)
	/ returns: array or xml
	/ doc: http://wiki.developers.viddler.com/index.php/Viddler.users.register
	*/
	function user_register($userInfo=null) {
		
		$username = $this->sendRequest('viddler.users.register',$userInfo,'post');
		
		return $username;
	}
	
	
	/* viddler.users.auth
	/ accepts: $user(string),$pass(string),$getToken(1/optional)
	/ returns: array or xml
	/ doc: http://wiki.developers.viddler.com/index.php/Viddler.users.auth
	*/
	function user_authenticate($user=null,$pass=null,$getToken=null) {
		
		$session = $this->sendRequest('viddler.users.auth','user='.$user.'&password='.$pass.'&get_record_token='.$getToken);
		
		return $session;
	}
	
	/* viddler.users.getProfile
	/ accepts: $user(string)
	/ returns: array or xml
	/ doc: http://wiki.developers.viddler.com/index.php/Viddler.users.getProfile
	*/
	function user_profile($user=null) {
		
		$profile = $this->sendRequest('viddler.users.getProfile','user='.$user);
		
		return $profile;
	}
	
	/* viddler.users.setProfile
	/ requires: POST
	/ accepts: $profile(array)
	/ returns: array or xml
	/ doc: http://wiki.developers.viddler.com/index.php/Viddler.users.setProfile
	*/
	function user_setprofile($profile=null) {
		
		$newProfile = $this->sendRequest('viddler.users.setProfile',$profile,'post');
		
		return $newProfile;
	}
	
	/* viddler.users.setOptions
	/ requires: POST
	/ accepts: $options(array)
	/ returns: array or xml
	/ doc: http://wiki.developers.viddler.com/index.php/Viddler.users.setOptions
	*/
	function user_setoptions($options=null) {
	
		$response = $this->sendRequest('viddler.users.setOptions',$options,'post');
		
		return $response;
	}
	
/*##########  Video functions ########### */

	/* viddler.videos.upload
	/ requires: POST
	/ accepts: $videoInfo(array)
	/ returns: array or xml
	/ doc: http://wiki.developers.viddler.com/index.php/Viddler.videos.upload
	*/
	function video_upload($videoInfo=null) {
	  // tom@punkave.com: this didn't work as-is because curl doesn't know
    // the 'file' field is the path of a file to be uploaded unless
    // you tell it by prefixing the value with an '@' sign.
    if (isset($videoInfo['file']) && substr($videoInfo['file'],0,1) != '@')
    {
      $videoInfo['file'] = '@' . $videoInfo['file'];
    }
		$videoDetails = $this->sendRequest('viddler.videos.upload',$videoInfo,'post');
		
		return $videoDetails;	
	}
	
	/* viddler.videos.getRecordToken
	/ accepts: $sessionid(number)
	/ returns: number | string if error
	/ doc: http://wiki.developers.viddler.com/index.php/Viddler.videos.getRecordToken
	/ doc: http://wiki.developers.viddler.com/index.php/Record_With_Webcam_API
	*/
	function video_getrecordtoken($sessionid=null) {
		
		$token = $this->sendRequest('viddler.videos.getRecordToken','sessionid='.$sessionid);
		
		if ($token['error']) {
			return $token['error']['description'];
		} else {
			return $token['record_token'];
		}
	}

	/* viddler.videos.getStatus
	/ accepts: $videoid(number),$sessionid(number/optional)
	/ returns: array or xml
	/ Method specific responses {
	    1: Waiting in encode queue (failed=0)
		2: Encoding (failed=0)
		3: Encoding process failed (failed=1)
		4: Ready (failed=0)
		5: Deleted (failed=0)
		6: Wrong priviledges (failed=0)
	}
	/ doc: http://wiki.developers.viddler.com/index.php/Viddler.videos.getStatus
	*/
	function video_status($videoid=null,$sessionid=null) {
		
		$videoStatus = $this->sendRequest('video.status','video_id='.$videoid.'&sessionid='.$sessionid);
		
		return $videoStatus;
	}
	
	/* viddler.videos.getDetails
	/ accepts: $sessionid(number/optional) and $videoid(number)
	/ returns: array or xml
	/ doc: http://wiki.developers.viddler.com/index.php/Viddler.videos.getDetails
	*/
	function video_details($videoid=null,$sessionid=null) {
		
		$videoDetails = $this->sendRequest('viddler.videos.getDetails','sessionid='.$sessionid.'&video_id='.$videoid);
		
		return $videoDetails;
	}
	
	/* viddler.videos.getDetailsByUrl
	/ accepts $sessionid(number/optional) and $videourl(string)
	/ returns: array or xml
	/ doc: http://wiki.developers.viddler.com/index.php/Viddler.videos.getDetailsByUrl
	*/
	function video_detailsbyurl($videourl=null,$sessionid=null) {
	  if($videourl && !strpos($videourl, 'explore')) $videourl = str_replace('viddler.com/', 'viddler.com/explore/', $url);
	  
	  $videoDetails = $this->sendRequest('viddler.videos.getDetailsByUrl','sessionid='.$sessionid.'&url='.$videourl);
	  
	  return $videoDetails;
	}
	
	/* viddler.videos.setDetails
	/ accepts: array
	/ returns: array or xml
	/ doc: http://wiki.developers.viddler.com/index.php/Viddler.videos.setDetails
	*/
	function video_setdetails($videoDetails=null) {
	
		$newVideoDetails = $this->sendRequest('viddler.videos.setDetails',$videoDetails,'post');
		
		return $newVideoDetails;
	}
	
	/* viddler.videos.comments.add
	/ accepts $video_id(string), $text(string), $sessionid
	*/
	function video_addcomment($video_id=null, $text=null, $sessionid=null) {
		$array = array('video_id' => $video_id, 'text' => $text, 'sessionid' => $sessionid);
		$comment = $this->sendRequest('viddler.videos.comments.add',$array,'post');
		
		return $comment;
	}


	/* viddler.videos.getByUser
	/ accepts: $user(string), $page(number), $per_page(number), $sessionid(number)
	/ returns: array or xml
	/ doc: http://wiki.developers.viddler.com/index.php/Viddler.videos.getByUser
	*/
	function videos_listbyuser($user=null,$page=null,$per_page=null,$sessionid=null) {
		
		$videoList = $this->sendRequest('viddler.videos.getByUser','user='.$user.'&sessionid='.$sessionid.'&page='.$page.'&per_page='.$per_page);
		
		return $videoList;
	}
	
	
	/* viddler.videos.getByTag
	/ accepts: $tag = string, $page = number, $per_page = number
	/ returns: array or xml
	/ doc: http://wiki.developers.viddler.com/index.php/Viddler.videos.getByTag
	*/
	function videos_listbytag($tag=null,$page=null,$per_page=null) {
		
		$videoList = $this->sendRequest('viddler.videos.getByTag','tag='.$tag.'&page='.$page.'&per_page='.$per_page);
		
		return $videoList;
	}
	
	/* viddler.videos.getFeatured
	/ accepts: none
	/ returns: array or xml
	/ doc: http://wiki.developers.viddler.com/index.php/Viddler.videos.getFeatured
	*/
	function videos_listfeatured() {
		
		$featuredVideos = $this->sendRequest('viddler.videos.getFeatured');
		
		return $featuredVideos;
	} // end videos_listfeatured()
	
/*########## Extended Functions ###########
	Although not available via the API itself yet,
	these functions can be used to do common
	tasks. */
	
	/* video_addTag()
	/ accepts: $videoid(number), $sessionid(number), $newtag(string)
	/ returns: same as video_getdetails();
	*/
	function video_addTag($videoid=null,$sessionid=null,$newtag=null) {
		
		$videodetails = $this->video_details($videoid,$sessionid);
		
		if (is_array($videodetails['video']['tags']['global']) && count($videodetails['video']['tags']['global']) > 1) {
			for ($i=0;$i<count($videodetails['video']['tags']['global']);$i++) {
				$videodetails['video']['tags']['global'][$i] = '"'.$videodetails['video']['tags']['global'][$i].'"';
			}
			$tags = implode(",",$videodetails['video']['tags']['global']);
		} else {
			$tags = $videodetails['video']['tags']['global'];
		}
		
		if ($tags == '') {
			$tags = $newtag;
		} else {
			$tags .= ','.$newtag;
		}
				
		$newvideodetails = $this->video_setdetails(array('video_id'=>$videoid,'sessionid'=>$sessionid,'tags'=>$tags));
		
		return $newvideodetails;
	
	}
	
	/* video_removeTag()
	/ accepts: $videoid(number), $sessionid(number), $oldtag(string)
	/ returns: same as video_getdetails();
	*/
	function video_removeTag($videoid=null,$sessionid=null,$oldtag=null) {
		
		$videodetails = $this->video_details($videoid,$sessionid);
		
		if (is_array($videodetails['video']['tags']['global']) && count($videodetails['video']['tags']['global']) > 1) {
			
			if (in_array($oldtag,$videodetails['video']['tags']['global'])) {
				
				for ($i=0;$i<count($videodetails['video']['tags']['global']);$i++) {
						if ($videodetails['video']['tags']['global'][$i] == $oldtag) {
							unset($videodetails['video']['tags']['global'][$i]);
						} else {
							$videodetails['video']['tags']['global'][$i] = '"'.$videodetails['video']['tags']['global'][$i].'"';
						}
				}
			
			} else {
				return false;
			}
			
			$tags = implode(",",$videodetails['video']['tags']['global']);
		
		} elseif ($oldtag == $videodetails['video']['tags']['global']) {
			$tags = '';
		} else {
			return false;
		}
				
		$newvideodetails = $this->video_setdetails(array('video_id'=>$videoid,'sessionid'=>$sessionid,'tags'=>$tags));
		
		return $newvideodetails;
	
	}



/*##########  Misc. Functions ########### */

	/* video_getrecordembed()
	/ accepts: $token(number),$width(number),$height(number)
	/ returns: HTML
	*/
	function video_getRecordEmbed($token=null,$width=449,$height=400) {
		
		if (!$token) return false;
		
		$html = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="'.$width.'" height="'.$height.'" id="viddler_recorder" align="middle">
			<param name="allowScriptAccess" value="always" />
			<param name="allowNetworking" value="all" />
			<param name="movie" value="http://www.viddler.com/flash/recorder.swf" />
			<param name="quality" value="high" />
			<param name="scale" value="noScale">
			<param name="bgcolor" value="#000000" />
			<param name="flashvars" value="fake=1&recordToken='.$token.'" />
			<embed src="http://www.viddler.com/flash/recorder.swf" quality="high" scale="noScale" bgcolor="#000000" allowScriptAccess="always" allowNetworking="all" width="'.$width.'" height="'.$height.'" name="viddler_recorder" flashvars="fake=1&recordToken='.$token.'" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
		</object>';
		
		return $html;
	}
	
	/* video_getEmbed()
	/ accepts: $videoid(string),$type(string,(player or simple),default=player),$options
	/ $width(number),$height(number),$autoplay(boolean),$options(array or false)
	/ returns: HTML
  / autoplay & options added by tom@punkave.com
	*/
	function video_getEmbed($videoid=null,$type='player',$width=437,$height=370, $autoplay = false, $options = false) {
   if (!$options) {
      $options = array();    
   }
		
		if (!$videoid) return false;

    $html = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width=
"'.$width.'" height="'.$height.'" id="viddlerplayer-'.$videoid.'"><param name="m
ovie" value="http://www.viddler.com/'.$type.'/'.$videoid.'/" />';
    if ($autoplay) {
      $options['autoplay'] = 't';
    }
    foreach ($options as $key => $val) {
      $html .= '<param name="' . htmlspecialchars($key) . '" value="' .
        htmlspecialchars($val) . '" />';
    }
    $html .= '<param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="true" /><embed src="http://www.viddler.com/'.$type.'/'.$videoid.'/" width="'.$width.'" height="'.$height.'" type="application/x-shockwave-flash" allowScriptAccess="always" allowFullScreen="true"';
    if (count($options)) {
      $html .= ' flashvars="' . http_build_query($options) . '"';
    }
    $html .= ' name="viddlerplayer-'.$videoid.'" ></embed></object>';
    return $html;
	}

	function video_getoEmbed($videourl,$maxwidth) {
		
		$reqURL = 'http://labs.viddler.com/services/oembed/?format=html&url='.$videourl.'&maxwidth='.$maxwidth;
	
		$curl_handle = curl_init();
		curl_setopt ($curl_handle, CURLOPT_URL, $reqURL);
		curl_setopt ($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl_handle, CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt ($curl_handle, CURLOPT_HEADER, 0);
		curl_setopt ($curl_handle, CURLOPT_TIMEOUT, 0);
		$embedcode = curl_exec($curl_handle);
		
		if (!$response)	$response = curl_error($curl_handle);
		curl_close($curl_handle);
		
		return $embedcode;
	}
	
	
	/* buildArguments()
	/ accepts: $p(array)
	/ returns: string
	*/
	function buildArguments($p) {
    // tom@punkave.com: undefined warning otherwise
    $args = '';
		foreach ($p as $key => $value) {
			
			// Skip these
			if ($key == 'method' || $key == 'submit' || $key == 'MAX_FILE_SIZE') continue;
			
			$args .= $key.'='.urlencode($value).'&';
			
		} // end foreach
		
		// Chop off last ampersand
		return substr($args, 0, -1);
	} // end buildArguments()

	/* sendRequest()
	/ accepts: $method(string), $args(array), $postmethod(string / post,get)
	/ returns: array or xml
	*/
	function sendRequest($method=null,$args=null,$postmethod='get') {
	
		// Convert array to string

    // tom@punkave.com: this used to break file uploads. CURLOPT_POSTFIELDS
    // is only checked for names beginning with @ if it's an array, and
    // that's how PHP's cURL wrapper recognizes file uploads. This in itself
    // is a potential security hole (because PHP programmers have no idea
    // that an @ prefix will do this if they pass an array), and I've opened 
    // a PHP bug report on that subject (#46439), but for viddler API 
    // purposes it's safe because viddler doesn't want or accept file 
    // uploads for inappropriate fields. Someday PHP's behavior may
    // change to the new API I suggest in that bug report (requiring
    // some changes here). We can hope.

		$reqURL = $this->viddlerREST.'?api_key='.$this->apiKey.'&method='.$method;
		if ($postmethod == 'get') {
      if (is_array($args)) 
      {
        $getArgs = $this->buildArguments($args);
      }	
      else
      {
        $getArgs = $args;
      }
			$reqURL .= '&'.$getArgs;
		}
		
		$curl_handle = curl_init();
		curl_setopt ($curl_handle, CURLOPT_URL, $reqURL);
		curl_setopt ($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl_handle, CURLOPT_CONNECTTIMEOUT, 1);
		curl_setopt ($curl_handle, CURLOPT_HEADER, 0);
		curl_setopt ($curl_handle, CURLOPT_TIMEOUT, 0);
		if ($postmethod == 'post') {
			curl_setopt($curl_handle, CURLOPT_POST, 1);
			if ($method == 'viddler.videos.upload'){
				curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $args);
			} else {
				curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $this->buildArguments($args));
			}
		}
		$response = curl_exec($curl_handle);
		
		if (!$response)	$response = curl_error($curl_handle);
		curl_close($curl_handle);
		
		// Debug?
		if ($this->debug) {
			echo '<pre>';
				print_r($response);
			echo '</pre>';
		}
		
		// Return array or XML
		if ($this->parser) {
			return XML_unserialize($response);
		} else {
			return $response;
		}
        
	} // End sendRequest();

} // end phpviddler

?>