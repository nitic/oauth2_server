<?php
/**
* @package     oauth2-server
* @author      Thawat Varachai
* @link        http://www.eng.psu.ac.th
* @copyright   Copyright(c) 2016
* @version     1.2
**/

defined('BASEPATH') OR exit('No direct script access allowed');
class Oauth2_model extends CI_Model {
	var $oauth;
			
	 public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
                //$this->rbac=$this->load->database('rbac',TRUE);
                $this->oauth=$this->load->database('default',TRUE);
        }
        
        

     private function getUserIdAccessToken($access_token)
        {

                $this->oauth->where('access_token',$access_token);
        	return $this->oauth->get('oauth_access_tokens')->row()->user_id;
        }
        function getUserInfoAccessToken($access_token)
        {
        		$this->load->model('rbac_model','rbac');
                        $user_id=$this->rbac->getByUsername($this->getUserIdAccessToken($access_token))->id;
                        $result=$this->rbac->getUserProfile($user_id);
        		if(empty($result)) return FALSE;
				else return $result;
        }
        function isUserAuthorized($client_id,$user_id)
        {
                $this->oauth->where('client_id',$client_id);
                $this->oauth->where('user_id',$user_id);
                $result=$this->oauth->get('oauth_refresh_tokens')->result();
                if(!empty($result)) return TRUE;
                else return FALSE;
        }


	
}	