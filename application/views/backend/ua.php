            <div class="row">
              <div class="col-lg-12">
                <h1 class="page-header"><i class="fa fa-user-secret" aria-hidden="true"></i> UA</h1>
              </div>
              <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-lg-12">

                <?if($succes){?>
                <div class="alert alert-success">UA <?=$succes?> успешно добавлен в черный список.</div>
                <?}?>

                <?if(@$error){?>
                <?foreach ($error['errors'] as $erro){?>
                <div class="alert alert-danger">
                  <?=$erro?>
                </div>
                <?}?>
                <?}?>

                <div class="panel panel-default">
                  <div class="panel-heading">
                    <form method="post" class="form-inline">
                      <input name="ua" type="text" class="form-control" placeholder="ua">
                      <button name="new" value="true" type="submit" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </form>
                  </div>
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                    <div class="dataTable_wrapper">
                      <table class="table table-striped table-bordered table-hover " id="user_list_table">
                        <thead>
                          <tr>
                            <th>ua</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?foreach ($black_ua as $black) { ?>
                          <tr class="odd gradeX">
                            <td><?=$black['user_agent']?></td>
                            <td>
                              <!-- <a href="#" class="btn btn-warning"><i class="fa fa-pencil-square-o"></i></a> -->
                              <a href="/delete_ua/<?=$black['id']?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
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
              "pageLength": 100,
              "language": {
                "lengthMenu": "Показывать по _MENU_ ua",
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