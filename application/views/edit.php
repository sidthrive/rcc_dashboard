
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

          <h2 class="sub-header">Edit <?php if(!empty($data['confirm_form'])){ ?><span class="label label-success">Approved</span><?php } ?></h2>
          <div class="table-responsive">
              <form class="form" action="<?=base_url()?>supervise/save/<?=$user?>/<?=$id?>" method="post">
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
                            <?php if($form=='unique_identifier'){ ?>
                            <table class="table">
                                <tbody>
                                    <?php foreach ($d as $s){
                                            if(isset($survey['survey'][$form][$s->name])){
                                                if(isset($s->value)) $survey['survey'][$form][$s->name]['value'] = $s->value;
                                                else $survey['survey'][$form][$s->name]['value'] = "";
                                            }
                                        }
                                        foreach($survey['survey'][$form] as $name=>$s){ 
                                            $type = explode(' ', $survey['survey'][$form][$name]['type']);
                                        ?>
                                    <tr>
                                        <td style="width: 50%"><?=$s['name']?></td>
                                        <td>
                                            <?php if($type[0]=='select_one'){ ?>
                                            <select class="form-control" name="<?=$form?>[<?=$name?>]">
                                                <option value="" <?=$s['value']==""?"selected":""?>></option>
                                                <?php foreach ($survey['choices'][$form][$type[1]] as $val=>$choice){ ?>
                                                <option value="<?=$val?>" <?=$s['value']==$val?"selected":""?>><?=$choice?></option>
                                                <?php } ?>
                                            </select>
                                            <?php }elseif($type[0]=='date'){ ?>
                                            <input class="form-control" type="date" name="<?=$form?>[<?=$name?>]" value="<?=isset($s['value'])?$s['value']:""?>"/>
                                            <?php }elseif($type[0]=='integer'){ ?>
                                            <input class="form-control" type="number" name="<?=$form?>[<?=$name?>]" value="<?=isset($s['value'])?$s['value']:""?>"/>
                                            <?php }elseif($type[0]=='hidden'){?>
                                            <select id="<?=$name?>" class="locs form-control" name="<?=$form?>[<?=$name?>]">
                                                <option value="" <?=$s['value']==""?"selected":""?>></option>
                                            </select>
                                            <?php }elseif($type[0]!='note'&&$type[0]!='begin'){?>
                                            <input class="form-control" type="text" name="<?=$form?>[<?=$name?>]" value="<?=isset($s['value'])?$s['value']:""?>"/>
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <?php }else{ ?>
                            <table class="table">
                                <tbody>
                                    <?php if(!empty($d)){ foreach($d as $s){
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
                                                    $this->c->getMapsDraggable($s->value);
                                                }else{
                                                    echo '<span>Tidak ada data gps</span>';
                                                }
                                            
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><input id="gps" class="form-control" type="text" name="<?=$form?>[<?=$s->name?>]" value="<?=isset($s->value)?$s->value:""?>" readonly /></td>
                                        <?php } }else{ ?>
                                        <td>
                                            <?php if($type[0]=='select_one'){ ?>
                                            <select class="form-control" name="<?=$form?>[<?=$s->name?>]">
                                                <option value="" <?=isset($s->value)?($s->value==""?"selected":""):"selected"?>></option>
                                                <?php foreach ($survey['choices'][$form][$type[1]] as $val=>$choice){ ?>
                                                <option value="<?=$val?>" <?=isset($s->value)?($s->value==$val&&$s->value!=''?"selected":""):""?>><?=$choice?></option>
                                                <?php } ?>
                                            </select>
                                            <?php }elseif($type[0]=='date'){ ?>
                                            <input class="form-control" type="date" name="<?=$form?>[<?=$s->name?>]" value="<?=isset($s->value)?$s->value:""?>"/>
                                            <?php }elseif($type[0]=='integer'){ ?>
                                            <input class="form-control" type="number" name="<?=$form?>[<?=$s->name?>]" value="<?=isset($s->value)?$s->value:""?>"/>
                                            <?php }elseif($type[0]!='note'&&$type[0]!='begin'){?>
                                            <input class="form-control" type="text" name="<?=$form?>[<?=$s->name?>]" value="<?=isset($s->value)?$s->value:""?>" <?=$type[0]=='hidden'?"readonly":""?>/>
                                            <?php }?>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                    <?php }}}else{   foreach ($survey['survey'][$form] as $variable=>$q){
                                        if($survey['survey'][$form][$variable]['name']!="Simpan sementara?"){
                                        $type = explode(' ', $survey['survey'][$form][$variable]['type']); ?>
                                    <tr>
                                        <td style="width: 50%"><?=isset($survey['survey'][$form][$variable])?$survey['survey'][$form][$variable]['name']:$variable?></td>
                                        <?php if($form=='gps'){ ?>
                                        <td>
                                            <?php 
                                            
                                                $this->c->getMapsClickAndDraggable();
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><input id="gps" class="form-control" type="text" name="<?=$form?>[<?=$variable?>]" readonly /></td>
                                        <?php }else{ ?>
                                        <td>
                                            <?php if($type[0]=='select_one'){ ?>
                                            <select class="form-control" name="<?=$form?>[<?=$variable?>]">
                                                <option value=""></option>
                                                <?php foreach ($survey['choices'][$form][$type[1]] as $val=>$choice){ ?>
                                                <option value="<?=$val?>"><?=$choice?></option>
                                                <?php } ?>
                                            </select>
                                            <?php }elseif($type[0]=='date'){ ?>
                                            <input class="form-control" type="date" name="<?=$form?>[<?=$variable?>]" />
                                            <?php }elseif($type[0]=='integer'){ ?>
                                            <input class="form-control" type="number" name="<?=$form?>[<?=$variable?>]" />
                                            <?php }elseif($type[0]!='note'&&$type[0]!='begin'){?>
                                            <input class="form-control" type="text" name="<?=$form?>[<?=$variable?>]" <?=$type[0]=='hidden'?"readonly":""?>/>
                                            <?php }?>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                    <?php }}} ?>
                                </tbody>
                            </table>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php }}else{ ?>
                    <tr>
                        <td colspan="9"><center>-- belum ada data --</center></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
                  <?php if($this->session->userdata('level')=="supervisor"||$this->session->userdata('username')=="sid"){ ?>
                  <center><button type="submit" class="btn btn-lg btn-success">Simpan</button></center>
              <?php }?>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?=base_url()?>asset/js/jquery.min.js"></script>
    <script src="<?=base_url()?>asset/js/bootstrap.min.js"></script>
    <script src="<?=base_url()?>asset/js/function.js"></script>
    <script>
        $("#Province").append('<option value="Jakarta" selected>Jakarta</option>');
        setLocation("<?=base_url()?>","Jakarta","<?=$survey['survey']['unique_identifier']['District']['value']?>","<?=$survey['survey']['unique_identifier']['Sub-district']['value']?>","<?=$survey['survey']['unique_identifier']['Village']['value']?>","<?=$survey['survey']['unique_identifier']['Sub-village']['value']?>");
        
        $(".locs").change(function(){
            updateLocation("<?=base_url()?>",$(this).attr('id'),$("option:selected", this).text());
        });
    </script>
    <script type="text/javascript">
            function updatePosition(newLat, newLng)
            {
                    $("#gps").val(newLat+" "+newLng+" 0 10");
            }
    </script>
</html>
