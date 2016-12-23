<?php
/**
* @package     oauth2-server
* @author      Thawat Varachai
* @link        http://www.eng.psu.ac.th
* @copyright   Copyright(c) 2016
* @version     1.2
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class ClientCredentials extends CI_Controller {
    function __construct(){
        parent::__construct();
        header('Content-type: application/json');
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: POST");
        $this->load->library("Server", "server");
    }    

    function index(){

        $this->server->client_credentials();
    }
}
