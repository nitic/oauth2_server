<?php
/**
* @package     oauth2-server
* @author      Thawat Varachai
* @link        http://www.eng.psu.ac.th
* @copyright   Copyright(c) 2016
* @version     1.2
**/

defined('BASEPATH') OR exit('No direct script access allowed');
class Rbac_model extends CI_Model {

        private $user_id;
        public $rbac;

        public function __construct()
        {
                // Call the CI_Model constructor
                parent::__construct();
                $this->load->library('encrypt');
                $this->rbac=$this->load->database('rbac',TRUE);
        }
        
        public function get_app_info_by_client_id($client_id=null)
        {
                	$app_info=array();
                	if(empty($client_id))
                	{
                		$app_info['client_id']='client_id is null';
                		$app_info['app_name_th']='invalid apps';
                		$app_info['app_name_en']='invalid apps';
        			     }
        			else{
        					$this->rbac->where('client_id',$client_id);
        					$result=$this->rbac->get('apps')->row();
        					if(!empty($result))
        					{
        						$app_info['client_id']=$client_id;
        						$app_info['app_name_th']=$result->app_name_th;
        						$app_info['app_name_en']=$result->app_name_en;
                    $app_info['app_server']=$result->app_server;
                    $app_info['app_version']=$result->app_version;
                    $app_info['app_icon']=$result->app_icon;
        					}
        					else {
        						$app_info['client_id']='client_id is null';
                				$app_info['app_name_th']='invalid apps';
                				$app_info['app_name_en']='invalid apps';
                        $app_info['app_server']='';
                        $app_info['app_version']='null';
                        $app_info['app_icon']='invalid-app.png';
        					}
        			}
        			return json_encode($app_info);        	 
        }
   function CreateUser($data,$mode=false)
   {
   	     
          $user_meta=array();
          $user_meta['email']=$data['email'];
          $user_meta['first_name']=$data['first_name'];
          $user_meta['last_name']=$data['last_name'];
          unset($data['email']);
          unset($data['first_name']);
          unset($data['last_name']);

          $local_user=array();
   	      $local_user['username']=$data['username'];	
   	      $local_user['salt'] = $this->generateSalt();
	        $local_user['password'] = $this->encrypt->hash($data['password'].$local_user['salt']); 				
   				$local_user['user_role_id']=6; // set role general user
   				$local_user['verification_status']=1; // set verification_status active
			   	$local_user['status']=1; // set user is active
          if(!$mode&&!$this->getByUsername($data['username']))				
   			  	{
            $this->rbac->insert('system_users',$local_user);
            $this->user_id=$this->rbac->insert_id();
            $this->CreateProfile($user_meta);
          }
          else{
            $this->rbac->where('username',$local_user['username']);
            $this->rbac->update('system_users',$local_user);
          }

	
   }
   function CreateProfile($data,$mode=false)
   {
   		
      if(!$mode) // insert new 
       		{
       			$data=array_merge($data,array('user_id'=>$this->user_id));
            $this->rbac->insert('user_meta',$data);
       		}
    		else  // update 
    		{
          $username=$data['username'];
          unset($data['username']);
          unset($data['password']);
    			$this->rbac->where('user_id',$this->getByUsername($username)->id);
    			$this->rbac->update('user_meta',$data);
    		}
   }
   function getUserProfile($user_id,$mode=false)
   {
      $this->rbac->where('user_id',$user_id);
      if($mode)
        $res=$this->rbac->get('user_meta')->row_array();
      else
        $res=$this->rbac->get('user_meta')->row();
      
      if(empty($res)) return FALSE;
    else return $res;
   }
  function getByUsername($username)
   {
      $this->rbac->where('username',$username);
      $res=$this->rbac->get('system_users')->row();
      if(empty($res)) return FALSE;
    else return $res;
      
   }
        
  protected function generateSalt()
    {
    	
        return uniqid('',true);
    }

    function generate_password($salt) {
        return substr($salt,rand(1,5),6);
    }
   function Authentication($username,$password)
    {

        $user=$this->getByUsername($username);
        if(!empty($user))
        {
        // Does password match hash in database?
            if ($this->encrypt->hash($password.$user->salt) === $user->password) { // valid user credentials
                return TRUE;
            }
            // invalid user credentials
            else return FALSE;
        }
        return FALSE;

    }
		
}
