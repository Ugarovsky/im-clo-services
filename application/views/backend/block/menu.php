          <!-- Navigation -->
          <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="/">IMKLO 0.25 <?if($user['active']){?><span class="label label-success">active</span></a><?}else{?><span class="label label-danger">inactive</span></a><?}?>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                  <li><a href="#"><i class="fa fa-user"></i> <?=$user['username']?></a></li>
                  <li><a href="/logout"><i class="fa fa-sign-out fa-fw"></i> Выход</a></li>
              <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->