
	<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</button>
			<a style="font-size: 20px" class="navbar-brand" href="index.php"><b>Php Login</b> System</a>
		</div>
		
		<div id="navbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="index.php">Home</a></li>
				<li><a href="about.php">About</a></li>
				<li><a href="contact.php">Contact</a></li>
				

				<?php if(!logged_in()): ?>
				<li><a href="login.php">Login & Signup</a></li>
					<?php endif; ?>

		

			<?php if(logged_in()): ?>
				<li><a href="answer.php">Answer</a></li>
				<li><a href="addQuestion.php">Add Question</a></li>
				<li><a href="logout.php">Logout</a></li>
			<?php endif; ?>
			</ul>
			 <ul class="nav navbar-nav navbar-right">
<form class="navbar-form navbar-left">
	<div class="form-group">
		<input type="text" class="form-control" placeholder="Search">
	</div>
	<button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
</form>
			
			</ul>
			</div><!--/.nav-collapse -->
	</nav>
