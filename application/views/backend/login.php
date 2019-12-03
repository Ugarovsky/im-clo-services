<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="/favicon.png" />
  <link rel="apple-touch-icon" href="/apple-touch-favicon.png"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="404">
  <meta name="author" content="">

  <title>вход в панель управления сайтом</title>

  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap-theme.min.css">
  <link rel="stylesheet" type="text/css" href="/assets/css/sweetalert.css">
  <link rel="stylesheet" href="/assets/fontawesome/css/font-awesome.min.css">
  <script src="/assets/js/jquery.min.js"></script>
  <script src="/assets/js/sweetalert.min.js"></script>
  <link href="/assets/css/stylish-portfolio.css" rel="stylesheet">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
      </head>
      <body>
        <header id="top" class="header">
          <div class="text-vertical-center">
            <div class="container">
              <div class="row">
                <div class="col-md-4 col-md-offset-4">
                  <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                      <h3 class="panel-title"><i class="fa fa-lock" aria-hidden="true"></i> вход в панель управления сайтом</h3>
                    </div>
                    <div class="panel-body">
                      <form role="form" method="POST">
                        <fieldset>
                          <div class="form-group">
                            <input name="login" type="text" class="form-control" placeholder="Email" required autofocus>
                          </div>
                          <div class="form-group">
                            <input name="password" type="password" class="form-control" placeholder="Пароль" required>
                          </div>
                          <?if(@$error){?>
                          <div class="alert alert-danger"><?=$error?></div>
                          <?}?>
                          <button name="enter" class="btn btn-primary btn-block" type="submit"><i class="fa fa-sign-in" aria-hidden="true"></i></button>
                        </fieldset>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </header>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
      </body>
      </html>