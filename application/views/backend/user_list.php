            <div class="row">
              <div class="col-lg-12">
                <h1 class="page-header"><i class="fa fa-users"></i> Пользователи</h1>
              </div>
              <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    Все пользователи
                  </div>
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                    <div class="dataTable_wrapper">
                      <table class="table table-striped table-bordered table-hover " id="user_list_table">
                        <thead >
                          <tr>
                            <th>#</th>
                            <th>ФИО</th>
                            <th>email</th>
                            <th>телефон</th>
                            <th>объявлений</th>
                            <th>дата регистрации</th>
                            <th>управление</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?foreach ($users as $user_list) { ?>
                          <tr class="odd gradeX">
                            <td><?=$user_list['id']?></td>
                            <td><a href="/admin/user/<?=$user_list['id']?>"><img class="img-circle" width="40" src="<?=$user_list['photo_max']?>" alt=""> <?=$user_list['last_name']?> <?=$user_list['first_name']?></a></td>
                            <td><?=$user_list['email']?> <?if($user_list['email_validation']!='success'){?> не подтвержден <?}?></td>

                            <td class="center"><?=$user_list['phone']?></td>
                            <td class="center"><?=$user_list['count_adverts']['count']?></td>
                            <td class="center"><?=$user_list['created']?></td>
                            <td>
                                <a href="#" class="btn btn-success"><i class="fa fa-line-chart"></i></a>
                                <a href="#" class="btn btn-warning"><i class="fa fa-pencil-square-o"></i></a>
                                <a href="#" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                            </td>
                          </tr>
                          <?}?>


                        </tbody>
                      </table>
                    </div>
                    <!-- /.table-responsive -->
                  </div>
                  <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
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
        <script>
          $(document).ready(function() {

            $('#user_list_table').DataTable( {
              "language": {
                "lengthMenu": "Показывать по _MENU_ пользователей",
                "zeroRecords": "Нет ни одной записи.",
                "info": "Показана _PAGE_ страница из _PAGES_",
                "infoEmpty": "Нет ни одной записи.",
                "infoFiltered": "(из _MAX_ записей)"
              }
            } );
          });
        </script>
      </body>

      </html>