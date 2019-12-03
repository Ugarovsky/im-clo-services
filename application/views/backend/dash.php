            <div class="row">
              <div class="col-lg-12">
                <h1 class="page-header"><i class="fa fa-dashboard" aria-hidden="true"></i> dashboard</h1>
              </div>
              <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-lg-12">

                <div class="panel panel-default">
                  <div class="panel-heading">

                  </div>
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                    <div id="morris-area-chart"></div>
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

          <script src="/assets/adm/js/raphael-min.js"></script>
          <script src="/assets/adm/js/morris.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="/assets/adm/js/sb-admin-2.js"></script>
        <!-- Page-Level Demo Scripts - Tables - Use for reference -->
        <script>

          $(document).ready(function() {

$(function() {

    Morris.Line({
        element: 'morris-area-chart',
        data: <?=$graph?>,
        xkey: 'period',
        xLabels: "day",
        ykeys: ['black', 'white', 'leads'],
        labels: ['Черный', 'Белый', 'Лидов'],
        pointSize: 2,
        hideHover: 'auto',
        resize: true
    });
});
          });
        </script>
      </body>

      </html>