### Description
Lightweight Viddler API Client

### Requirements
PHP 5+, json_encode(), SimpleXML, Viddler API Key

### API Documentation
[http://developers.viddler.com/documentation/api/](http://developers.viddler.com/documentation/api/)

### Brief Rundown
This is a very easy-to-use and lightweight Viddler API client written in PHP. If you want to use the official Viddler client, [http://github.com/viddler/phpviddler](click here). It makes use of the __call method so that you can easily call any valid API method without any of the methods actually being defined.

Let's say you want to call the method viddler.users.auth, you can call it using any of these ways:

    $viddler->viddler_users_auth($params);
    $viddler->users_auth($params);
    $viddler->viddlerUsersAuth($params);
    $viddler->usersAuth($params);
    $viddler->usersauth($params);
    $viddler->viddlerusersauth($params);
    $viddler->viddlerusersAuth($params);

Crazy I know but there is a method that will format the method you called. You DO NOT need to prepend your method call with the namespace of 'viddler' but if you do, it's okay, the client will figure it out. After that there is a breakdown of the next namespace (users, videos, api), the client will find this namespace and set it aside. After that the client will find the actual method name in either camelCase or under_score and format it correctly.

I prefer underscore but that's just me.

### POST, HTTPS and BINARY
The client will figure this out for you. If the method is to be sent using POST, it's set for you. If it has the option to be sent over HTTPS, it sets it. If it is to send a binary file...yep does that for you too.

### Response Type
As an add-on I have enabled 3 types of responses not just the XML to array. You can get XML, a PHP array or JSON. This client does not have to include any json or xml parser files, it uses json_encode and SimpleXML from PHP's core. If you do not have these in your version of PHP, you CANNOT use this client. The default response type set is 'php' which will return an array.

You can set the response type either by setting the class parameter ($viddler->response_type = 'json') or you can set it on the fly for each method call you make. Just set it as 'response_type' param in your argument array.

###API Key
You can also send a new API Key at anytime in any method call. You can set it when you call the client and change it with any method call just by adding the 'api_key' param to your argument array.

### Simple Example
    $viddler = new Viddler('APIKEY');
    $res = $viddler->users_auth(array(
      'user'      =>  'USERNAME',
      'password'  =>  'YOUR PASS'
    ));
    
### Set API Key and Response Type on method call
    $viddler = new Viddler;
    $res = $viddler->videos_getByUser(array(
      'user'          =>  'USERNAME',
      'sessionid'     =>  'SESSIONID',
      'api_key'       =>  'APIKEY',
      'response_type' =>  'json'
    ));