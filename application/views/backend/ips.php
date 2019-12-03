            <div class="row">
              <div class="col-lg-12">
                <h1 class="page-header"><i class="fa fa-ban" aria-hidden="true"></i> Черный список IP</h1>
              </div>
              <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <a href="/delete_all_black" class="btn btn-danger"><i class="fa fa-trash"></i> Удалить все</a>
                  </div>
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                    <div class="dataTable_wrapper">
                      <table class="table table-striped table-bordered table-hover " id="ips_list_table">
                        <thead >
                          <tr>
                            <th>#</th>
                            <th><i class="fa fa-eye" aria-hidden="true"></i> посещений</th>
                            <th><i class="fa fa-link" aria-hidden="true"></i> доменов</th>
                            <th><i class="fa fa-user-secret" aria-hidden="true"></i> юзер агентов</th>
                            <th><i class="fa fa-globe" aria-hidden="true"></i></th>
                            <th>IP</th>
                            <th><i class="fa fa-ban" aria-hidden="true"></i> причина</th>
                            <th><i class="fa fa-comment" aria-hidden="true"></i></th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
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

  $('#ips_list_table').DataTable({
         "bProcessing": false,
         "deferRender": true,
         "order": [],
         "bSort": false,
         "bFilter": false,
         "pageLength": 25,
         "serverSide": true,
         "ajax":{
            url :"/ajax_ips",
            type: "post",
            error: function(){
              $("#user_list_table_processing").css("display","none");
            }
          },
          "language": {
                "lengthMenu": "Показывать по _MENU_ ип",
                "zeroRecords": "Нет ни одной записи.",
                "info": "Показана _PAGE_ страница из _PAGES_",
                "infoEmpty": "Нет ни одной записи.",
                "infoFiltered": "(из _MAX_ записей)"
          }
        });
  });
        </script>
    </body>

    </html>