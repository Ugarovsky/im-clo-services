<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="icon" type="image/png" href="/favicon.png" />
	<link rel="apple-touch-icon" href="/favicon.png"/>
	<link rel="shortcut icon" href="/favicon.png" type="image/x-icon" />

	<!-- Bootstrap core CSS -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">

	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<!-- Latest compiled and minified JavaScript -->
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

	<!-- Custom styles for this template -->
	<link href="/assets/css/signin.css" rel="stylesheet">

	<!-- Just for debugging purposes. Don't actually copy this line! -->
	<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body>

	<div class="container">

		<?php echo form_open('/login', ['class' => 'form-signin']); ?>
		<input type="text" class="form-control" placeholder="Username"  name="username" id="username" required autofocus>
		<input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
		<button class="btn btn-lg btn-primary btn-block" type="submit"  value="login">Вход</button>

		<?php if($error): ?>
			<div class="alert alert-danger">
				<?php echo $error; ?>
			</div>	
		<?php endif; ?>
	</form>
</div>



</body>
</html>
