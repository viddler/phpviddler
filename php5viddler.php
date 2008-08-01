<?php
include_once('phpviddler.php');

class Php5viddler extends Phpviddler {  
  function sendRequest($method=null,$args=null,$postmethod='get',$tryagain=true) {    
    $result = parent::sendRequest($method, $args, $postmethod);
    
    if($tryagain && is_null($result)) {
      $result = parent::sendRequest($method, $args, $postmethod, false);
    } elseif(is_null($result)) {
      throw new ViddlerException("No response", $method, 8888, 'n/a');
    }
    
    if(is_array($result) && $result['error']) {
      throw new ViddlerException($result['error']['description'], $method, $result['error']['code'], $result['error']['details']);
    }

		return $result;
  }

}

class ViddlerException extends Exception {
  var $details;
  var $method;
  
  public function __construct($message, $method, $code=0, $details='') {
    $this->details = $details;
    $this->method = $method;
    parent::__construct($message, $code);
  }
  
  public function getDetails() {
    return $this->details;
  }
  
  public function __toString() {
    return "{$this->method} exception [{$this->code}]: {$this->getMessage()} ({$this->details})\n";
  }
}


?>