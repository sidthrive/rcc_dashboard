
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
          <h1 class="page-header">Generate Report Demo</h1>

          <div class="report">
              <div class="container">
                  <form class="form-signin" method="post" action="<?=site_url('generate_report/login')?>">
                      <center><h2 class="form-signin-heading">Login Report Demo</h2></center>
                      <hr>
                    <input type="hidden" name="url" value="<?=$this->session->flashdata('url')?>"/>
                    <label for="inputEmail" class="sr-only">Username</label>
                    <input name="username" type="text" id="inputEmail" class="form-control" placeholder="Username" required autofocus>
                    <label for="inputPassword" class="sr-only">Password</label>
                    <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
                    <span><?=$this->session->flashdata('error')?></span>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
                  </form>
                  <hr>
                      <div>
                          <p>Different level of user will have different view of the report</p>
                          <p>User with level 1 only can see report about Registered Household and Household Character</p>
                          <p>User with level 2 can see report level 1 can see + Health Seeking Behavior and Knowledge-Attitude reports</p>
                          <p>User with level 3 can see report level 2 can see + Immunization coverage report</p>
                          <p>Use user:user1, pass:user1 for user with level 1</p>
                          <p>Use user:user2, pass:user2 for user with level 2</p>
                          <p>Use user:user3, pass:user3 for user with level 3</p>
                      </div>
            </div>
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
