
<!DOCTYPE html>
<html lang="en">
    <?php $this->load->view("demo/header"); ?>
  <body>

    <?php $this->load->view("navbar"); ?>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <?php $this->load->view("sidebar"); ?>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Generate Report Demo</h1>

          <div class="report">
              <?php $this->load->view("demo/navbar"); ?>
              <div id="tabs">
                  <ul>
                    <li><a href="#tabs-1">Registered Household</a></li>
                    <li><a href="#tabs-2">Household Character</a></li>
                    <?php if($this->session->userdata('demo_level')>1){ ?>
                    <li><a href="#tabs-3">Health Seeking Behavior</a></li>
                    <li><a href="#tabs-4">Knowledge and Attitude</a></li>
                    <?php }if($this->session->userdata('demo_level')>2){ ?>
                    <li><a href="#tabs-5">Immunization Coverage</a></li>
                    <?php } ?>
                  </ul>
                  <?php $this->load->view("demo/tab1"); ?>
                  <?php $this->load->view("demo/tab2"); ?>
                  <?php if($this->session->userdata('demo_level')>1){ ?>
                  <?php $this->load->view("demo/tab3"); ?>
                  <?php $this->load->view("demo/tab4"); ?>
                  <?php }if($this->session->userdata('demo_level')>2){ ?>
                  <?php $this->load->view("demo/tab5"); ?>
                  <?php } ?>
                </div>
          </div>
        </div>
      </div>
    </div>
    <script>
  $( function() {
    $( "#tabs" ).tabs();
  } );
  </script>
</html>
