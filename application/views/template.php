
<!DOCTYPE html>
<html lang="en">
  <?php $this->load->view("header"); ?>

  <body>
      <?php $this->load->view("navbar"); ?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <?php $this->load->view("sidebar"); ?>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">List Data Survey</h1>

          <h2 class="sub-header">Data</h2>
          <div class="ui-widget">
              <label for="filter">Filter Username: </label>
              <input id="filter" class="form-control-static">
              <button id="btnFilter" class="btn btn-primary">Go</button>
              <button id="btnReset" class="btn btn-danger">Reset</button>
          </div>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>No</th>
                  <th>User</th>
                  <th>Responden</th>
                  <th>Unique<br>Identifier</th>
                  <th>Household<br>Character</th>
                  <th>Health<br>Seeking<br>Behaviour</th>
                  <th>Knowledge<br>Regarding<br>Immunization</th>
                  <th>Attitude<br>Regarding<br>Immunization</th>
                  <th>Immunization<br>Coverage</th>
                  <th>GPS</th>
                  <th>#</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $no = 1;
                if(!empty($data)){ foreach ($data as $user=>$d) { foreach($d as $id=>$s){ ?>
                <tr>
                  <td><?=$no++?></td>
                  <td class="user"><?=$user?></td>
                  <td><?=isset($s['unique_identifier']->respondent_name)?$s['unique_identifier']->respondent_name:""?></td>
                  <td><?=empty($s['unique_identifier'])?'<span class="label label-danger">&#x2717;</span>':'<span class="label label-success">&#x2714;</span>'?></td>
                  <td><?=empty($s['household_character'])?'<span class="label label-danger">&#x2717;</span>':'<span class="label label-success">&#x2714;</span>'?></td>
                  <td><?=empty($s['health_seeking_behaviour'])?'<span class="label label-danger">&#x2717;</span>':'<span class="label label-success">&#x2714;</span>'?></td>
                  <td><?=empty($s['knowledge_regarding_immunization'])?'<span class="label label-danger">&#x2717;</span>':'<span class="label label-success">&#x2714;</span>'?></td>
                  <td><?=empty($s['attitude_regarding_immunization'])?'<span class="label label-danger">&#x2717;</span>':'<span class="label label-success">&#x2714;</span>'?></td>
                  <td><?=empty($s['immunization_coverage'])?'<span class="label label-danger">&#x2717;</span>':'<span class="label label-success">&#x2714;</span>'?></td>
                  <td><?=empty($s['gps'])?'<span class="label label-danger">&#x2717;</span>':'<span class="label label-success">&#x2714;</span>'?></td>
                  <td><a href="<?=base_url()?>supervise/detail/<?=$user?>/<?=$id?>" type="button" class="btn btn-info">View</a></td>
                  <td><?=empty($s['confirm_form'])?'<span class="label label-danger">&#x2717;</span>':'<span class="label label-success">Approved</span>'?></td>
                </tr>
                <?php }}}else{ ?>
                <tr>
                    <td colspan="9"><center>-- belum ada data --</center></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?=base_url()?>asset/js/jquery.min.js"></script>
    <script src="<?=base_url()?>asset/js/jquery-ui.min.js"></script>
    <script src="<?=base_url()?>asset/js/bootstrap.min.js"></script>
    <script>
      $( function() {
        var availableTags = [];
        console.log(availableTags);
        $.get("<?=base_url()?>data/get_username", function(data, status){
            availableTags = jQuery.parseJSON(data);
            $( "#filter" ).autocomplete({
              source: availableTags
            });
        });
        $('#btnFilter').click(function() {
            var user = $('#filter').val();
            $('tr').show();
            if(user != ''){
                $('tr td.user').each(function() {
                    if ($(this).text() != user)
                    {
                        $(this).parent().hide();
                    }
                });
            }
        });
        $('#btnReset').click(function() {
            $('#filter').val("");
            $('tr').show();
        });

      } );
  </script>
</html>
