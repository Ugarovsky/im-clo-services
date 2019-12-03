            <div class="row">
              <div class="col-lg-12">
                <h1 class="page-header"><i class="fa fa-link" aria-hidden="true"></i> Домены</h1>
              </div>
              <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-lg-12">

                <?if(@$succes){?>
                <?foreach ($succes['succes'] as $succes){?>
                <div class="alert alert-success">
                  <?=$succes?>
                </div>
                <?}?>
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
                      <input name="domen" type="text" class="form-control" placeholder="домен" value="<?=$domains?>">
                      <input name="comment" type="text" class="form-control" placeholder="комментарий">
                      <input name="link" type="text" class="form-control" placeholder="ссылка на черный">
                      <input name="white_link" type="text" class="form-control" placeholder="ссылка на белый">
                      <select name="country[]" class="form-control js-example-basic-multiple" multiple="multiple">
                        <?foreach ($countries as $countrie){?>
                        <option value="<?=$countrie['alpha2']?>"><?=$countrie['LangRU']?> (<?=$countrie['alpha2']?>)</option>
                        <?}?>
                      </select>

<!--                       <select name="traf" class="form-control">
                        <option value="1">любой трафик</option>
                        <option value="2">моб трафик</option>
                        <option value="3">десктоп трафик</option>
                      </select> -->
                      <button name="new" value="true" type="submit" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i></button>
                    </form>

                    <div style="padding-top: 5px;"><a class="btn btn-danger" href="/delete_logs"><i class="fa fa-trash" aria-hidden="true"></i> очистить логи</a></div>

                  </div>
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                    <div class="dataTable_wrapper" style="overflow: scroll;">
                        <table id="user_list_table" class="table table-striped table-bordered table-hover "  style="width:100%">
                        <thead >
                          <tr>
                            <th><i class="fa fa-link" aria-hidden="true"></i></th>
                            <th title="Комментарий"><i class="fa fa-comment" aria-hidden="true"></i></th>
                            <th title="Ссылка на черный"></th>
                            <th title="Ссылка на белый"></th>

                            <th title="Всего переходов"><i class="fa fa-eye" aria-hidden="true"></i></th>
                            <th title="Переходов на проклу"><i class="fa fa-check" aria-hidden="true"></i></th>
                            <th title="Переходов на ленд"><i class="fa fa-bullseye" aria-hidden="true"></i></th>

                            <th title="Переходов на белый"><i class="fa fa-ban" aria-hidden="true"></i></th>

                            <th title="Лидов"><i class="fa fa-shopping-basket" aria-hidden="true"></i></th>
                            <th title="Лидов - аппрув"><i class="fa fa-thumbs-up" aria-hidden="true"></i></th>
                            <th title="Лидов - отклон"><i class="fa fa-thumbs-down" aria-hidden="true"></i></th>

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
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
        <script>
          $(document).ready(function() {

  function formatState (state) {
    if (!state.id) {
      return state.text;
    }
  var baseUrl = "/assets/flags/24";
  var $state = $(
    '<span><img src="' + baseUrl + '/' + state.element.value + '.png" class="img-flag" /> ' + state.text + '</span>'
  );
    return $state;
  };

  $('.js-example-basic-multiple').select2({
    templateResult: formatState,
    placeholder: "страна",
    allowClear: true
  });

  $('#user_list_table').DataTable({
         "bProcessing": false,
         "deferRender": true,
         "rowId": "id",
         "order": [],
         "pageLength": 25,
         "serverSide": true,
         "ajax":{
            url :"/ajax_domains",
            type: "post",
            error: function(){
              $("#user_list_table_processing").css("display","none");
            }
          },
          "language": {
                "lengthMenu": "Показывать по _MENU_ доменов",
                "zeroRecords": "Нет ни одной записи.",
                "info": "Показана _PAGE_ страница из _PAGES_",
                "infoEmpty": "Нет ни одной записи.",
                "infoFiltered": "(из _MAX_ записей)"
          },
          "columnDefs": [
                {"targets": [11], "orderable": false},
                {"targets": [1], "className" : "comment"},
                {"targets": [1], createdCell: function (td, cellData, rowData, row, col) {
                        $(td).attr('contentEditable', true);
                    }},
                {"targets": [2], "className" : "link"},
                {"targets": [2], createdCell: function (td, cellData, rowData, row, col) {
                        $(td).attr('contentEditable', true);
                    }},
                {"targets": [3], "className" : "white_link"},
                {"targets": [3], createdCell: function (td, cellData, rowData, row, col) {
                        $(td).attr('contentEditable', true);
                }},
            ]
        });

                  $('table').on('focusout', '.link,.white_link,.comment', function(){
                    if($(this).closest("tr").attr("id")){
                        var id= $(this).closest("tr").attr("id");
                        //var name = "link";
                        var text = $(this).text();
                        var name = $(this).attr("class");
                        $.post('/pixel_red', {id:id,name:name,text:text},
                            function(data){
                                datas = JSON.parse(data);
                                if(datas.result === true){
                                    console.log('ok '+id);
                                }
                            });
                    }
                });
  });
        </script>
      </body>

      </html>
