            <div class="row">
              <div class="col-lg-12">
                <h1 class="page-header"><i class="fa fa-cogs" aria-hidden="true"></i> Настройки</h1>
              </div>
              <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-lg-12">

                <?if($succes){?>
                <div class="alert alert-success"><?=$succes?></div>
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

                  </div>
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                  <form role="form" method="post">
                                    <div class="form-group input-group">
                                      <input type="text"  name="api_token" class="form-control" placeholder="Ключ лизцензии" value="<?=$user['api_token']?>">
                                      <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">сохранить</button>
                                      </span>
                                    </div>
                                  </form>
                                  <form role="form" method="post">
                                    <div class="form-group input-group">
                                      <input type="text" name="password" class="form-control" placeholder="Пароль пользователя <?=$user['username']?>">
                                      <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">сохранить</button>
                                      </span>
                                    </div>
                                  </form>
                                </div>
                            </div>
                            <!-- /.row (nested) -->
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
                "lengthMenu": "Показывать по _MENU_ isp",
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