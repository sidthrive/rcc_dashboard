
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
          <h1 class="page-header">Peta Sebaran Responden</h1>

          <div class="maps">
              <?php $this->c->showAllMaps($this->session->userdata('username')); ?>
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
