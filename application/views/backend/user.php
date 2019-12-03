            <div class="row">
              <div class="col-lg-12">
                <h1 class="page-header"><i class="fa fa-users"></i> Пользователь</h1>
              </div>
              <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-lg-12">

                <div class="container">
                  <div class="row">
                    <div class="col-md-5  toppad  pull-right col-md-offset-3 ">
                     <A href="edit.html" >Edit Profile</A>

                     <A href="edit.html" >Logout</A>
                     <br>
                     <p class=" text-info"><?=$user_data['created']?> </p>
                   </div>
                   <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >


                    <div class="panel panel-info">
                      <div class="panel-heading">
                      <h3 class="panel-title"><?=$user_data['last_name']?> <?=$user_data['first_name']?></h3>
                      </div>
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-md-3 col-lg-3 " align="center"> <img width="108" alt="User Pic" src="<?=$user_data['photo_max']?>" class="img-circle img-responsive"> </div>

                <!--<div class="col-xs-10 col-sm-10 hidden-md hidden-lg"> <br>
                  <dl>
                    <dt>DEPARTMENT:</dt>
                    <dd>Administrator</dd>
                    <dt>HIRE DATE</dt>
                    <dd>11/12/2013</dd>
                    <dt>DATE OF BIRTH</dt>
                       <dd>11/12/2013</dd>
                    <dt>GENDER</dt>
                    <dd>Male</dd>
                  </dl>
                </div>-->
                <div class=" col-md-9 col-lg-9 "> 
                  <table class="table table-user-information">
                    <tbody>
                      <tr>
                        <td>Department:</td>
                        <td>Programming</td>
                      </tr>
                      <tr>
                        <td>Hire date:</td>
                        <td>06/23/2013</td>
                      </tr>
                      <tr>
                        <td>Date of Birth</td>
                        <td>01/24/1988</td>
                      </tr>

                      <tr>
                       <tr>
                        <td>Gender</td>
                        <td>Male</td>
                      </tr>
                      <tr>
                        <td>Home Address</td>
                        <td>Metro Manila,Philippines</td>
                      </tr>
                      <tr>
                        <td>Email</td>
                        <td><a href="mailto:info@support.com">info@support.com</a></td>
                      </tr>
                      <td>Phone Number</td>
                      <td>123-4567-890(Landline)<br><br>555-4567-890(Mobile)
                      </td>

                    </tr>

                  </tbody>
                </table>

                <a href="#" class="btn btn-primary">My Sales Performance</a>
                <a href="#" class="btn btn-primary">Team Sales Performance</a>
              </div>
            </div>
          </div>
          <div class="panel-footer">
            <a data-original-title="Broadcast Message" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-envelope"></i></a>
            <span class="pull-right">
              <a href="edit.html" data-original-title="Edit this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
              <a data-original-title="Remove this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a>
            </span>
          </div>

        </div>
      </div>
    </div>
  </div>


</div>
<!-- /.col-lg-12 -->
</div>
<!-- /.row -->

</div>
</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="/assets/js/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="/assets/bootstrap/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="/assets/adm/js/metisMenu.min.js"></script>

<!-- DataTables JavaScript -->
<script src="/assets/adm/js/jquery.dataTables.min.js"></script>
<script src="/assets/adm/js/dataTables.bootstrap.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="/assets/adm/js/sb-admin-2.js"></script>
<!-- Page-Level Demo Scripts - Tables - Use for reference -->
</body>

</html>