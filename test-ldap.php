<?php
error_reporting(-1);
		ini_set('display_errors', 1);
$ldaphost="ldap.forumsys.com";
//$ldapport = 389;
$ldapconn=ldap_connect($ldaphost) or die('connect fail.');
ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
 if(ldap_bind($ldapconn,'uid=riemann,dc=example,dc=com','password'))
	   {
	    	 	//$this->ldap_connect_status=true;
	    	 	//ldap_close($ldap);
	    	 	//return $this->ldap_connect_status;
	   	 	//echo 'ok';
	   	 }
	   	 else 'no';

	   	 $attribute=array('uid','mail','cn');
	   	 $basedn='dc=example,dc=com';
	   	 $fillter='(uid=riemann)';
	   	 $sr=ldap_search($ldapconn,$basedn,$fillter,$attribute);
         $info = ldap_get_entries($ldapconn, $sr);
         //print_r($info);
        print explode(' ',$info[0]['cn'][0])[1];

//ldap_close($ldapconn);