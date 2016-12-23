<?php
/**
* @package     oauth2-server
* @author      Thawat Varachai
* @link        http://www.eng.psu.ac.th
* @copyright   Copyright(c) 2016
* @version     1.2
**/
defined('BASEPATH') OR exit('No direct script access allowed');

class RefreshToken extends CI_Controller {
    function __construct(){
        parent::__construct();
        $this->load->library("Server", "server");
    }    

    function index(){
        $this->server->refresh_token();
    }
}
