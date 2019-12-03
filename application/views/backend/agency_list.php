            <div class="row">
              <div class="col-lg-12">
                <h1 class="page-header"><i class="fa fa-users"></i> Агентства</h1>
              </div>
              <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    Все агентства
                  </div>
                  <!-- /.panel-heading -->
                  <div class="panel-body">
                    <div class="dataTable_wrapper">
                      <table class="table table-striped table-bordered table-hover " id="user_list_table">
                        <thead >
                          <tr>
                            <th>#</th>
                            <th>Агентство</th>
                            <th>Описание</th>
                            <th>Ссылка на сайт</th>
                            <th>дата регистрации</th>
                            <th>Администратор</th>
                            <th>Инвайты</th>
                            <th>Агенты</th>
                            <th>Статус</th>
                            <th>управление</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?foreach ($agencys as $agency) { ?>
                            <tr class="odd gradeX">
                              <td><?=$agency['id']?></td>
                              <td><img class="img-circle" width="40" src="/download/logos/<?=$agency['logo']?>" alt=""> <?=$agency['name']?> </td>
                              <td class="center" style="font-size:10px; width:100px;"><?=$agency['description']?></td>
                              <td class="center"><a href="<?=$agency['link']?>"><?=$agency['link']?></a></td>
                              <td class="center"><?=$agency['created']?></td>

                              <td><a href="/admin/user/<?=$agency['user']['id']?>"><img class="img-circle" width="40" src="<?=$agency['user']['photo_max']?>" alt=""> <?=$agency['user']['last_name']?> <?=$agency['user']['first_name']?></a></td>
                              <td  style="font-size:11px;" class="center">
                                <? foreach ($agency['invitation'] as $key => $invitati) { ?>
                                    <?=$invitati['email']?><br>
                                    <?=$invitati['created']?><hr>
                                <? } ?>
                              </td>
                              <td class="center">
                                <? foreach ($agency['agents'] as $key => $agent) { ?>
                                  <a href="/admin/user/<?=$agent['id']?>"><img class="img-circle" width="40" src="<?=$agent['photo_max']?>" alt=""> <?=$agent['last_name']?> <?=$agent['first_name']?></a><hr>
                                <?}?>

                              </td>
                              <td class="center">
                                <? if($agency['moderate']==0){?>
                                  не подтверждено
                                  <?}else{?>
                                    подтверждено
                                    <?}?>
                                  </td>

                                  <td>
                                   <? if($agency['moderate']==0){?>
                                    <a href="/admin/upd/agency/<?=$agency['id']?>/id/moderate/1" class="btn btn-success"><i class="fa fa-check"></i></a>
                                    <?}else{?>
                                      <a href="/admin/upd/agency/<?=$agency['id']?>/id/moderate/0" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                      <?}?>

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
                        "lengthMenu": "Показывать по _MENU_ агентств",
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