<?php
/**
* Simple PHP Viddler Wrapper Class
*
* @author Jeff Johns <phpfunk@gmail.com>
* @license MIT License
*/
class Viddler {
	public $api_key = NULL;
	public $response_type = 'php';
	
	/**
  * Sets API Key as object param if submitted.
  *
  * @param  string  $api_key  Your Viddler API Key
  */
	public function __construct($api_key=NULL)
	{
		$this->api_key = (! empty($api_key)) ? $api_key : $this->api_key;
	}
	
	/**
  * Called when you call a method that doesn't exist.
  *
  * @param  string  $method   method called
  * @param  array   $args     array of arguments
  * @return xml | array | json
  */
	protected function __call($method, $args)
	{
		$method = $this->format_method($method);
		
		//If method is false - return the error
		if ($method === FALSE) {
			return $this->format_reponse("Invalid Method ($method)");
		}
		
		//Method arrays for secure, post and binary methods
		$secure_methods = array("viddler.users.auth");
		$post_methods = array(
			"viddler.videos.upload","viddler.users.setProfile","viddler.users.setOptions","viddler.videos.setDetails",
			"viddler.videos.setPermalink","viddler.videos.comments.add","viddler.videos.comments.remove","viddler.videos.delete",
			"viddler.videos.setThumbnail"
		);
		$binary_methods = array("viddler.videos.setThumbnail","viddler.videos.upload");
		
		//Set binary, post and protocol
		$binary = (in_array($method, $binary_methods)) ? TRUE : FALSE;
		$post = (in_array($method, $post_methods)) ? TRUE : FALSE;
		$protocol = (in_array($method, $secure_methods)) ? "https" : "http";
		
		//Find type and API Key, reason it is done this way is because you can change API keys
		//or response type in the argument array on any call
		$type = (isset($args[0]['response_type'])) ? strtolower($args[0]['response_type']) : $this->response_type;
		$api_key = (isset($args[0]['api_key'])) ? $args[0]['api_key'] : $this->api_key;
		
		//Set Base URL
		$url = $protocol . "://api.viddler.com/rest/v1/?api_key=" . $api_key . "&method=" . $method;
		
		//Figure the query string
		$query = array();
		if (@count($args[0]) > 0 && is_array($args[0])) {
			foreach ($args[0] as $k => $v) {
				if ($k != "response_type" && $k != "api_key") {
					array_push($query, "$k=$v");
				}
			}
			$query_arr = $query;
			$query = implode("&", $query);
			if ($post === FALSE) {
				$url .= (!empty($query)) ? "&" . $query : "";
			}
		}
		else {
			$query = NULL;
			$args[0] = array();
		}
		
		//Make it happen
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, TRUE);
		curl_setopt ($ch, CURLOPT_HEADER, FALSE);
		curl_setopt ($ch, CURLOPT_TIMEOUT, FALSE);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		
		//Figure POST vs. GET
		if ($post == true) {
			curl_setopt($ch, CURLOPT_POST, TRUE);
			$query = ($binary === true) ? $args[0] : $query;
		  curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
		}
		else {
			curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
		}
		
		//Get the response
		$response = curl_exec($ch);
		if (!$response) {
			$response = curl_error($ch);
		}
		else {
		  if ($type != 'xml') {
		    $response = $this->unserialize_xml($response);
		    if ($type == 'json') {
		      $response = json_encode($response);
		    }
		  }
		}
		curl_close($ch);
		return $response;
	}
	
	/**
  * Called to format the method called and return it.
  *
  * @param  string  $method   method called
  * @return string
  */
	protected function format_method($method)
	{
		$prepend = (stristr($method, 'viddler')) ? false : true;
		
		if (stristr($method, "_")) {
			$method = str_replace("_", ".", $method);
			$method = ($prepend === true) ? 'viddler.' . $method : $method;
		}
		else {
			$string = $method;
			$method = NULL;
			$main = array("viddler");
			$subset = array("users","videos", "api");
			
			if ($prepend === false) {
			 $tmp = $this->stitch($string, $main);
			 if (!isset($tmp['method']) || empty($tmp['method'])) {
			   return false;
			 }
			 $method = $tmp['method'];
      }
      else {
        $tmp = array();
        $tmp['string'] = $string;
        $method = 'viddler';
      }
      
			$tmp = $this->stitch($tmp['string'], $subset);
			if (strtolower(substr($tmp['string'], 0, 8)) == "comments") {
				$last = str_replace("comments","comments.", strtolower($tmp['string']));
			}
			else {
				$last = strtolower(substr($tmp['string'], 0, 1)) . substr($tmp['string'], 1);
			}
			$method .= "." . $tmp['method'] . "." . $last;
		}
		
		return $method;
	}

	/**
  * Formats error if the method submitted is not
  * in a valid format.
  *
  * @param  string  $string The error string to write
  * @return string
  */
	protected function format_reponse($string)
	{
		if ($this->response_type == 'php' || $this->reponse_type == 'json') {
		  $arr = array('error' => array('code'=>'1000',"description"=>$string));
		  return ($this->response_type == 'json') ? json_encode($arr) : $arr;
		}
		else {
			return '<error><code>1000</code><description>$string</description></error>';
		}
	}
	
	/**
  * Breaks down and stitches the method back together.
  *
  * @param  string  $string   The string to evaluate
  * @param  array   $array    The array that holds methods to search
  * @return array
  */
	protected function stitch($string, $array)
	{
		$arr = array();
		foreach ($array as $val) {
			if (strtolower(substr($string, 0, strlen($val))) == $val) {
				$arr['method'] = $val;
				$arr['string'] = substr($string, strlen($val));
				break;
			}
		}
		return $arr;
	}
	
	/**
  * Turns the XML returned from Viddler API into an array.
  * This method uses PHP's SimpleXML
  *
  * @param  string  $string     The string to evaluate
  * @param  bool    $load       Whether or not to call SimpleXML
  * @return string
  */
	protected function unserialize_xml($string, $load=true)
  {
    $data = ($load) ? simplexml_load_string($string) : $string;
    if ($data instanceof SimpleXMLElement) $data = (array) $data;
    if (is_array($data)) {
      foreach ($data as &$item) {
        $item = $this->unserialize_xml($item, false);
      }
    }
    return $data;
  }

}
?>