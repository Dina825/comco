
<html xmlns="http://www.w3.org/1999/xhtml">
<title>Comco Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">


<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<link href="<?php echo URL::to('assets')?>/css/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo URL::to('assets')?>/js/jquery.min-1-9-1.js"></script>

<link href="<?php echo URL::to('assets')?>/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo URL::to('assets')?>/css/style-responsive.css" rel="stylesheet" type="text/css" />
<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
<head>

</head>
<body style="background: #3e3e3e; height: 100%; float: left;width: 100%;">

<div class="login_bg">
  <div class="login_center">
    <div class="logo img-responsive text-center"><img src="<?php echo URL::to('assets')?>/images/logo.png"></div>
     <form action="<?php echo URL::to('admin/login'); ?>" method="post">       
      <div class="main_title" style="font-weight: 300; text-align: center; margin:20px 0px 0px 0px; ">Welcome <span style="font-weight: 500;">Admin</span></div>
      <div class="login_form">
        <?php
          if(Session::has('message')) { ?>
              <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
          <?php }
          ?> 
        <div class="form-group">
          <span class="login_user_icon"><img src="<?php echo URL::to('assets')?>/images/user_icon.png"></span>        
          <input type="text" class="form-control uname" name="userid" placeholder="Enter Username" name="email" required>
          </div>
          <div class="form-group">
            <span class="login_user_icon"><img src="<?php echo URL::to('assets')?>/images/password_icon.png"></span>        
            <input id="password-field" type="password" name="password" class="form-control pword" placeholder="Enter Password" required>
             <span toggle="#password-field" name="password" class="fas fa-fw fa-eye login-eye-icon toggle-password"></span>
          </div>
          <div class="form-group">          
            <input type="submit" class="blogin" value="LOGIN">
          </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
$(".toggle-password").click(function() {

  $(this).toggleClass("fa-eye fa-eye-slash");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
</script>
<script type="text/javascript" src="<?php echo URL::to('assets')?>/js/bootstrap.min.js"></script> 
</body>
</html>