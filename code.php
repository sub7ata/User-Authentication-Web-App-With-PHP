<?php include("includes/header.php") ?>
<div class="row">
	<div class="col-lg-6 col-lg-offset-3">
	
		<?php display_message(); ?>
		<?php validate_code(); ?>
		
	</div>
</div>
<div class="row">
	<div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3">
		<div> <!-- time count down -->
			<center>
				<h2 style=""><span id="timer"></span></h2>
			</center>
		</div>
		 <div id="countdown"></div>
		<div class="alert-placeholder">
		
		</div>
		<div class="panel panel-success">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<div class="text-center"><h2><b> Enter Code</b></h2></div>
						<form id="register-form"  method="post" role="form" autocomplete="off">
							<div class="form-group">
								<input type="text" name="code" id="code" tabindex="1" class="form-control" placeholder="##########" value="" autocomplete="off" required/>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-sm-3 col-sm-offset-2  col-xs-6">
										<input type="submit" name="code-cancel" id="code-cancel" tabindex="2" class="form-control btn btn-danger" value="Cancel" />
										
									</div>
									<div class="col-lg-3 col-lg-offset-2 col-md-3 col-md-offset-2 col-sm-3 col-sm-offset-2 col-xs-6">
										<input type="submit" name="code-submit" id="recover-submit" tabindex="2" class="form-control btn btn-success" value="Continue" />
										
									</div>
								</div>
							</div>
							<input type="hidden" class="hide" name="token" id="token" value="">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

    let timerOn = true;

    function timer(remaining) {
      var m = Math.floor(remaining / 60);
      var s = remaining % 60;
      
      m = m < 10 ? '0' + m : m;
      s = s < 10 ? '0' + s : s;
      document.getElementById('timer').innerHTML = m + ':' + s;
      remaining -= 1;
      
      if(remaining >= 0 && timerOn) {
        setTimeout(function() {
            timer(remaining);
        }, 1000);
        return;
      }

      if(!timerOn) {
        // Do validate stuff here
        return;
      }
      
      // Do timeout stuff here
     /* alert('Timeout for otp');*/
    }

    timer(60);
</script>

<?php include("includes/footer.php") ?>

<!-- 

<script type="text/javascript">
	
    var timeleft = 60;
    var downloadTimer = setInterval(function(){
      document.getElementById("countdown").innerHTML = timeleft + " seconds remaining";
      timeleft -= 1;
      if(timeleft <= 0){
        clearInterval(downloadTimer);
        document.getElementById("countdown").innerHTML = "Finished"
      }
    }, 1000);

</script> -->