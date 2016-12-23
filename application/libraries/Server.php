<?php
/**
* @package     oauth2-server
* @author      Thawat Varachai
* @link        http://www.eng.psu.ac.th
* @copyright   Copyright(c) 2016
* @version     1.2
**/

class Server{
	function __construct($config=array()){
		require_once(APPPATH.'config/database.php');
		require_once(APPPATH.'third_party/OAuth2/Autoloader.php');
		$config = $db['default'];

		OAuth2\Autoloader::register();
		$this->storage = new OAuth2\Storage\Pdo(array('dsn' => $config["dsn"], 'username' => $config["username"], 'password' => $config["password"]));
		$this->server = new OAuth2\Server($this->storage, array('allow_implicit' => true));
		$this->request = OAuth2\Request::createFromGlobals();
		$this->response = new OAuth2\Response();
	}


	public function client_credentials(){
		$this->server->addGrantType(new OAuth2\GrantType\ClientCredentials($this->storage, array(
    		"allow_credentials_in_request_body" => true
		)));
		$this->server->handleTokenRequest($this->request)->send();
	}


	public function password_credentials(){

		$users = array("user" => array("password" => 'wunca34', 'first_name' => 'wunca34', 'last_name' => 'uninet'));
		$storage = new OAuth2\Storage\Memory(array('user_credentials' => $users));
		$this->server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
		$this->server->handleTokenRequest($this->request)->send();
	}


	public function refresh_token(){
		$this->server->addGrantType(new OAuth2\GrantType\RefreshToken($this->storage, array(
			"always_issue_new_refresh_token" => true,
			"unset_refresh_token_after_use" => true,
			"refresh_token_lifetime" => 2419200,
		)));
		$this->server->handleTokenRequest($this->request)->send();
	}


	public function require_scope($scope=""){
		if (!$this->server->verifyResourceRequest($this->request, $this->response, $scope)) {
    		$this->server->getResponse()->send();
		}
	}

	public function check_client_id(){
		if (!$this->server->validateAuthorizeRequest($this->request, $this->response)) {
    		$this->response->send();

		}
		
	}

	public function authorize($is_authorized,$user_id){
		$this->server->addGrantType(new OAuth2\GrantType\AuthorizationCode($this->storage));
		$this->server->handleAuthorizeRequest($this->request, $this->response, $is_authorized,$user_id);
		if ($is_authorized) {
	  		$code = substr($this->response->getHttpHeader('Location'), strpos($this->response->getHttpHeader('Location'), 'code=')+5, 40);
	  		header("Location: ".$this->response->getHttpHeader('Location'));
	  	}

		$this->response->send();

	}

	public function authorization_code(){
		
		$this->server->addGrantType(new OAuth2\GrantType\AuthorizationCode($this->storage));
		$this->server->handleTokenRequest($this->request)->send();
	}

	public function get_status_code()
	{
		return $this->response->getStatusCode();
	}
	public function set_status_code($status)
	{
		$this->response->setStatusCode($status);
	}
}