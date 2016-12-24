<?php
$action = base_url("authorize?response_type=".$response_type."&client_id=".$client_id."&redirect_uri=".$redirect_uri."&state=".$state."&scope=".$scope);
?>
<div class="container"> 
  <div class="row" id="pwd-container">
    <div class="col-md-6 col-md-offset-3">
      <section class="login-form">
        <form method="post" action="<?=$action?>" role="login">
          <img src="<?=base_url('images/mascot.png')?>" class="col-md-6 img-responsive" alt="" />
          <div class="input-group">
          	<span class="input-group-addon input-group-lg"><i class="fa fa-user"></i></span>
          	<input type="text" name="username" placeholder="Username" required class="form-control" autocomplete="off">
          </div>
          <div class="input-group">
          	<span class="input-group-addon input-group-lg"><i class="fa fa-key"></i></span>
          <input type="password" name="password" class="form-control input-lg" id="password" placeholder="Password" autocomplete="off" required/>
          </div>         
          <button type="submit" name="go" class="btn btn-lg btn-danger btn-block" value="user_credentials"><i class="fa fa-sign-in fa-fw"></i>Sign In</button>
          <div><a class="forgot" style="padding:4px;" href="#" class="">Forgotten account?</a></div>
        </form>
      </section>  
      </div>
      </div> 
</div>