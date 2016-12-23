<?php
/**
* @package     oauth2-server
* @author      Thawat Varachai
* @link        http://www.eng.psu.ac.th
* @copyright   Copyright(c) 2016
* @version     1.2
**/
defined('BASEPATH') OR exit('No direct script access allowed');
class Error extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->library("Server", "server");

	}
	function index($code=404)
	{
		//log_message('error', '404 page not found');
		$this->server->set_status_code($code);	
		$response=array('error'=>$this->server->response->getStatusText(),'status'=>$code);
		print json_encode($response);
	}
	
}