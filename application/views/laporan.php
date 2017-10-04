
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
            <h1 class="page-header">Laporan</h1>

            <h2 class="sub-header">Download</h2>
            <div class="table-responsive">
                <form class="form" action="<?=base_url()?>report/download" method="post">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                    <select class="form-control-static" name="mode">
                                        <option value="approved">Approved Data</option>
                                        <option value="submitted">Submitted Data</option>
                                        <option value="raw">Raw Data</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td><button class="btn btn-primary" type="submit">Download</button></td>
                            </tr>
                        </tbody>
                    </table>
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
</html>
