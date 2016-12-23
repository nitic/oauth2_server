<?php
$action = base_url("authorize?response_type=".$response_type."&client_id=".$client_id."&redirect_uri=".$redirect_uri."&state=".$state."&scope=".$scope);
?>
<div class="container"> 
  <div class="row" id="pwd-container">
    <div class="col-md-6 col-md-offset-3">
      <section class="login-form">
        <form method="post" action="<?=$action?>" role="login">
        	<img src="<?=base_url('images/mascot.png')?>" class="col-md-6 img-responsive" alt="" />
          <div>
    <div class="notice notice-success">
        <h3 class="text-danger"><?=$app_name?></h3>
     <blockquote>
 			 <p>would like permission to access your account</p>
	</blockquote>
    </div>
		  </div>
          <input type="submit" name="authorized" class="btn btn-lg btn-success btn-block" value="Allow">
          <input type="submit" name="authorized" class="btn btn-lg btn-warning btn-block" value="Deny">
        </form>
      </section>  
      </div>
      </div> 
</div>