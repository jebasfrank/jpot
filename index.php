<!doctype html>
<html lang="en">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/style.css">
<!--font-awesome CSS-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Card Game Login</title>

<!--Check-in And Check-out Date Range Picker CSS-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker3.min.css">
</head>

<body>
<div class="container-fluid login-page-bg">
  <div class="container container-full-wrapper">
    <div class="row align-items-center">
      <div class="col-md-12">
        <div class="login-form">
          <div id="response_id"></div>
          <div class="logo-new-group"><a href="#">Jackpot </a></div>
          <div class="logo-mobile-login-group"><a href="#">Mobile Login</a></div>
          <div class="input-new-group">
            <input class="input-new-group-newclass" type="text" name="login_email" id="login_email" placeholder="ID">
          </div>
          <div class="input-new-group">
            <input class="input-new-group-newclass" type="password" name="login_pass" id="login_pass"  placeholder="Password">
          </div>
          <div class="input-new-groupsubmit">
            <button type="submit-login" form="form1" onclick="login_user()" name="registration_submit1" id="registration_submit1" value="Submit">Login</button>
          </div>
          
          <div class="input-new-groupsubmit">
            <button class="close-bgdt" form="form1" value="close">Close</button>
          </div>
          
          
        </div>
      </div>
    </div>
    
    
    
    
  </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> 
<script src="js/bootstrap.js"></script> 

<!--Check-in And Check-out Date Range Picker--> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script> 
<script>
$(document).ready(function(){
	var date_input = $('input[name="date"]'); //our date input has the name "date"
	var container = "body";
	var options = {
		format: 'dd/mm/yyyy',
		container: container,
		todayHighlight: true,
		autoclose: true,
		language: 'el' 
	};
	date_input.datepicker(options);
})
</script> 
<script type="text/javascript">
        function login_user(){
          var user_id = $("#login_email").val();
          var login_pass = $("#login_pass").val();
          $("#warning_id").show();
          $("#registration_submit1").hide();
          var data = "login_user&user_id="+user_id+"&login_pass="+login_pass;
          $.ajax({
            type:"GET",
            data:data,
            url:"ajax.php",
            dataType:"JSON",
            success: function(e){
              $("#warning_id").hide();
              $("#registration_submit1").show();
              if(e.is_logged_in==0){
                $("#response_id").html('<div class="alert alert-danger">'+e.message+'</div>');

              }else{
                $("#response_id").html('<div class="alert alert-success">'+e.message+' please wait...</div>');
                location.replace("app.php");
              }
            }
          })
        }
    </script>


</body>
</html>
