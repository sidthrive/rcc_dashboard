
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

          <h2 class="sub-header">Detail <?php if(!empty($data['confirm_form'])){ ?><span class="label label-success">Approved</span><?php } ?></h2>
          <div class="table-responsive">
            <table class="table">
              <tbody>
                <?php 
                $no = 1;
                if(!empty($data)){ 
                    $survey = $this->c->getFormDetail();
                    foreach ($data as $form=>$d) { ?>
                <tr>
                    <td>
                        <h3><?=ucwords(str_replace("_", " ", $form))?></h3>
                        <table class="table">
                            <tbody>
                                <?php foreach($d as $s){ 
                                    if(isset($survey['survey'][$form][$s->name])){
                                        $type = explode(' ', $survey['survey'][$form][$s->name]['type']);
                                    ?>
                                <tr>
                                    <td style="width: 50%"><?=isset($survey['survey'][$form][$s->name])?$survey['survey'][$form][$s->name]['name']:$s->name?></td>
                                    <?php if($form=='gps'){ ?>
                                    <td>
                                        <?php 
                                        if(isset($s->value)){
                                            if($s->value!=""){
                                                $this->c->getMaps($s->value);
                                            }else{
                                                echo '<span>Tidak ada data gps</span>';
                                            }
                                        }
                                        ?>
                                    </td>
                                    <?php }else{ ?>
                                    <td><?=isset($s->value)?($type[0]=='select_one'&&$s->value!=''?$survey['choices'][$form][$type[1]][$s->value]:$s->value):""?></td>
                                    <?php } ?>
                                </tr>
                                <?php }} ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <?php }}else{ ?>
                <tr>
                    <td colspan="9"><center>-- belum ada data --</center></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
              <?php if(empty($data['confirm_form'])&&($this->session->userdata('level')=="supervisor"||$this->session->userdata('username')=="sid")){ ?>
              <center><a href="<?=base_url()?>supervise/edit/<?=$user?>/<?=$id?>" type="button" class="btn btn-lg btn-warning">Edit</a>&nbsp;&nbsp;<a href="<?=base_url()?>supervise/approve/<?=$user?>/<?=$id?>" type="button" class="btn btn-lg btn-success">Approve</a></center>
              <?php }elseif($this->session->userdata('level')=="supervisor"||$this->session->userdata('username')=="sid"){?>
              <center><a href="<?=base_url()?>supervise/edit/<?=$user?>/<?=$id?>" type="button" class="btn btn-lg btn-warning">Edit</a></center>
              <?php }?>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?=base_url()?>asset/js/jquery.min.js"></script>
    <script src="<?=base_url()?>asset/js/bootstrap.min.js"></script>
</html>
