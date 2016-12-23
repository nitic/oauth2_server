<?php
/**
* @package     oauth2-server
* @author      Thawat Varachai
* @link        http://www.eng.psu.ac.th
* @copyright   Copyright(c) 2016
* @version     1.2
**/

defined('BASEPATH') OR exit('No direct script access allowed');
class Ldap_model extends CI_Model {
		// ldap connect profile setting  		
	var	$ldapserver = array('ldap.forumsys.com');
    var $basedn = "dc=example,dc=com";
    var $port=389;	
    var $ldap_connect_status=false;

	function check_ldap_connection()
	{
		
	     foreach($this->ldapserver as $item)
	     {
	    	 $ldap = ldap_connect($item,$this->port) or $this->ldap_connect_status = false;
	    	 ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
             ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
	    	 if(@ldap_bind($ldap))
	    	 {
	    	 	$this->ldap_connect_status=true;
	    	 	ldap_close($ldap);
	    	 	return $this->ldap_connect_status;
	    	 }
	    	 else $this->ldap_connect_status=false;
	     }
	     return $this->ldap_connect_status;
	}
  function Authentication($username=null,$password=null)
	{
		if(!$this->check_ldap_connection()) return false;
		
		$auth_status = false;
		$i=0;
		while(($i<count($this->ldapserver))&&($auth_status==false))
		{
              $ldap = ldap_connect($this->ldapserver[$i]) or $this->ldap_connect_status = false; 
                if($ldap)
                {
                	// ldap binding
                	$this->ldap_connect_status=true;
                	ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
               		ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
               		$ldapbind = @ldap_bind($ldap,'uid='.$username.','.$this->basedn,$password);
                	if($ldapbind){
                		$auth_status=true;
                		// ldap searching for get information
                		 $attribute=array('uid','mail','cn');
					   	 $fillter='(uid='.$username.')';
					   	 $sr=ldap_search($ldap,$this->basedn,$fillter,$attribute);
				         $info = ldap_get_entries($ldap, $sr);
				        // Sync to RBAC insert or update user profiles
				         $user_profile=array();
				         $user_profile['username']=$username;
				         $user_profile['password']=$password;
				         $user_profile['email']=$info[0]['mail'][0];
				         $user_profile['first_name']=explode(' ',$info[0]['cn'][0])[0];
				         if(!empty(explode(' ',$info[0]['cn'][0])[1]))
				         $user_profile['last_name']=explode(' ',$info[0]['cn'][0])[1];

				         if(!$this->rbac->getByUsername($username))
				         	$this->rbac->CreateUser($user_profile);
				         else
				         {
				         	$this->rbac->CreateUser($user_profile,true);
				         	$this->rbac->CreateProfile($user_profile,true);
				         }
					}
                }
              ldap_close($ldap);
              $i++;
		}
		return $auth_status;
	}

}