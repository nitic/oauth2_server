<?php
/**
* @package     oauth2-server
* @author      Thawat Varachai
* @link        http://www.eng.psu.ac.th
* @copyright   Copyright(c) 2016
* @version     1.2
**/
defined('BASEPATH') OR exit('No direct script access allowed');
class Error_404 extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->library("Server", "server");

	}
	function index()
	{
		//log_message('error', '404 page not found');
		$this->server->set_status_code(404);	
		$response=array('error'=>$this->server->response->getStatusText(),'status'=>'404');
		print json_encode($response);
	}
	
}