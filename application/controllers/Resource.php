<?php
/**
* @package     oauth2-server
* @author      Thawat Varachai
* @link        http://www.eng.psu.ac.th
* @copyright   Copyright(c) 2016
* @version     1.2
**/
defined('BASEPATH') OR exit('No direct script access allowed');

class Resource extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->library("Server", "server");
    	$this->server->require_scope("access,userinfo");//you can require scope here 
    	if(!$this->server->response->isSuccessful()) exit;
    		
    }

    public function index(){

        //resource api controller
        echo json_encode(array('success' => true, 'message' => 'You accessed my APIs!'));

    }
    public function userinfo()
    {
    	 $userinfo=array();
    	 $this->load->model('Oauth2_model','oauth2');
    	 $userinfo=$this->oauth2->getUserInfoAccessToken($this->input->get('access_token'));
    	echo json_encode($userinfo);

    }
    public function isUserAuthorized()
    {
         $this->load->model('Oauth2_model','oauth2');
         $isauthorized=$this->oauth2->isUserAuthorized($this->input->get('client_id'),$this->input->get('user_id'));
         if($isauthorized) echo json_encode(array('isauthorized' =>TRUE));
         else echo json_encode(array('isauthorized' => FALSE));
    }
    public function destroy()
    {
       if($this->server->storage->unsetAccessToken($this->input->get('access_token'))) 
        echo json_encode(array('isdestroyed' =>TRUE));
        else json_encode(array('isdestroyed' =>FALSE));
    }

}
