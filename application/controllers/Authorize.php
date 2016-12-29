<?php
/**
* @package     oauth2-server
* @author      Thawat Varachai
* @link        http://www.eng.psu.ac.th
* @copyright   Copyright(c) 2016
* @version     1.2
**/

defined('BASEPATH') OR exit('No direct script access allowed');

class Authorize extends CI_Controller {
		

    
	var $user_profiles=array();
	// oauth2 variable
	var $scope;
    var $state;
    var $client_id;
    var $redirect_uri;
    var $response_type;
        
    function __construct(){
        parent::__construct();
        $this->load->library('encryption');
        $this->load->library("Server", "server");
        $this->load->model('oauth2_model','oauth2');
        $this->load->model('rbac_model','rbac');
        $this->load->model('ldap_model','ldap');
		//set app info
		$app_info=json_decode($this->rbac->get_app_info_by_client_id($this->input->get("client_id")));
		$data['app_info']=$app_info;
		$this->template->write('title',$app_info->app_name_th,TRUE);
		$this->template->write_view('band_name','apps_info',$data,TRUE);
			
    }

    function index(){
    	$this->scope = $this->input->get("scope");
     	$this->state = $this->input->get("state");
      	$this->client_id = $this->input->get("client_id");
       	$this->redirect_uri = $this->input->get("redirect_uri");
        $this->response_type = $this->input->get("response_type");
        $this->server->check_client_id();

      	// Main Check Server status  200  
  		if($this->server->get_status_code()!=200) exit;

		         	
			// get app info on RBAC
			
		         
		          $app_info=json_decode($this->rbac->get_app_info_by_client_id($this->client_id));
		          $app_name=$app_info->app_name_en;
		    // prepare data for views     
		          $data = array(
		                "scope" => $this->scope,
		                "state" => $this->state,
		                "client_id" => $this->client_id,
		                "redirect_uri" => $this->redirect_uri,
		                "response_type" => $this->response_type,
		                "app_name"=>$app_name,
		                "app_icon"=>$app_info->app_icon
		            );       
		    if(!empty($this->input->post('go')))
			 {
						
						// mark and set encrypt username and password
						$this->encryption->initialize(array('driver' => 'openssl'));					
						$this->session->mark_as_flash('encrypt_password');
						$this->session->mark_as_flash('encrypt_username');
						$this->session->set_flashdata('encrypt_password', $this->encryption->encrypt($this->input->post('password')));
						$this->session->set_flashdata('encrypt_username', $this->encryption->encrypt($this->input->post('username')));
			 
						
				 
			// User Authentication and authorization
							 
				 if($this->ldap->Authentication($this->encryption->decrypt($this->session->flashdata('encrypt_username')),$this->encryption->decrypt($this->session->flashdata('encrypt_password'))))
						{
							// Check User Authorized then bypass authorization
							if($this->oauth2->isUserAuthorized($this->client_id,$this->encryption->decrypt($this->session->flashdata('encrypt_username'))))
						           		 $this->server->authorize(true,$this->encryption->decrypt($this->session->flashdata('encrypt_username')));
							else // View Authorize Acception
										$this->template->write_view('content','authorization',$data);
						 }

						else
						{
						// Invalid User Credential 
									$this->server->set_status_code(401);
									$this->template->write_view('content','invalid_credential');

						} // end User Authentication
					 

				} 
		        
				elseif(!isset($_POST["authorized"]))	        
		           	// View User Credentail Forms
		           	$this->template->write_view('content','credential',$data);
		
		        else{
		        	// Allow or Deny Authorize
		            $authorized = $this->input->post("authorized");
		            $user_id=$this->input->get('user_id');
		            if($authorized === "Allow")  	
				           $this->server->authorize(true,$this->encryption->decrypt($this->session->flashdata('encrypt_username')));
					else 
						$this->server->authorize(false);
		        
				}
		         $this->template->render();

       
    }

 function token()
   {
       	
       $this->server->authorization_code("yes");
   }





}