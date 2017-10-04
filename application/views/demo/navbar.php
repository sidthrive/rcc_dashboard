<nav class="navbar navbar-inverse navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
      </button>
      <a class="navbar-brand" href="<?=base_url('generate_report')?>">Generate Report</a> 
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">
          <li><a>Welcome <?=$this->session->userdata('user_demo')?>, you're level <?=$this->session->userdata('demo_level')?></a></li>
        <li><a href="<?=base_url()?>generate_report/logout">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>