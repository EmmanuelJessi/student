<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
  ob_start();
  // if(!isset($_SESSION['system'])){

    $system = $conn->query("SELECT * FROM system_settings")->fetch_array();
    foreach($system as $k => $v){
      $_SESSION['system'][$k] = $v;
    }
  // }
  ob_end_flush();
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Login | <?php echo $_SESSION['system']['name'] ?></title>
 	

<?php include('./header.php'); ?>
<?php 
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");

?>

</head>
<style>
	body {
    width: 100%;
    height: 100%;
    background-image: url('assets/uploads/color-background-transformed.jpeg');
    background-size: cover;
    background-position: left; /* Align background image to the left */
    position: fixed;
    margin-top: 10px;
    left: 0;
}

main#main {
    width: 100%;
    height: calc(100%);
    display: flex;
    justify-content: flex-start; /* Align content to the left */
    align-items: center;
}


</style>

<body class=" d-flex align-items-center justify-content-center">

  <main id="main">
    <div class="align-self-center w-100">
      <h4 class="text-white text-center"><b><?php echo $_SESSION['system']['name'] ?> - Admin</b></h4>
      <div id="login-center" class=" row justify-content-center">
        <div class="card col-md-6">
          <div class="card-body">
            <form id="login-form">
              <div class="form-group">
                <label for="username" class="control-label text-dark">Username</label>
                <input type="text" id="username" name="username" class="form-control form-control-sm">
              </div>
              <div class="form-group">
                <label for="password" class="control-label text-dark">Password</label>
                <input type="password" id="password" name="password" class="form-control form-control-sm">
              </div>
              <div class="w-100 d-flex justify-content-center">
              <button class="btn-sm btn-block btn-wave col-md-4 btn-warning m-0 mr-1">Login</button>
              <button class="btn-sm btn-block btn-wave col-md-4 btn-primary m-0" type="button" id="view_result">View Result</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>
  <div class="modal fade" id="view_student_results" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <form id="vsr-frm">
              <div class="form-group">
                <label for="student_code" class="control-label text-dark">Student ID #:</label>
                <input type="text" id="student_code" name="student_code" class="form-control form-control-sm">
              </div>
            </form>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id='submit' onclick="$('#view_student_results form').submit()">View</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

</body>

<?php include 'footer.php' ?>
<script>
  $('#view_result').click(function(){
    $('#view_student_results').modal('show')
  })
	$('#login-form').submit(function(e){
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'ajax.php?action=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
		$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success:function(resp){
				if(resp == 1){
					location.href ='index.php?page=home';
				}else{
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
  $('#vsr-frm').submit(function(e){
    e.preventDefault()
   start_load()
    if($(this).find('.alert-danger').length > 0 )
      $(this).find('.alert-danger').remove();
    $.ajax({
      url:'ajax.php?action=login2',
      method:'POST',
      data:$(this).serialize(),
      error:err=>{
        console.log(err)
        end_load()
      },
      success:function(resp){
        if(resp == 1){
          location.href ='student_results.php';
        }else{
          $('#login-form').prepend('<div class="alert alert-danger">Student ID # is incorrect.</div>')
           end_load()
        }
      }
    })
  })
	$('.number').on('input keyup keypress',function(){
        var val = $(this).val()
        val = val.replace(/[^0-9 \,]/, '');
        val = val.toLocaleString('en-US')
        $(this).val(val)
    })
</script>	
</html>