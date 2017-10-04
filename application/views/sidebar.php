<ul class="nav nav-sidebar">
    <li <?=$this->uri->segment(1)=='supervise'&&($this->uri->segment(2)==''||$this->uri->segment(2)=='detail')?'class="active"':''?>><a href="<?=base_url()?>supervise">List Survey</a></li>
    <li <?=$this->uri->segment(2)=='maps'?'class="active"':''?>><a href="<?=base_url()?>supervise/maps">Peta Sebaran</a></li>
    <li <?=$this->uri->segment(1)=='report'?'class="active"':''?>><a href="<?=base_url()?>report">Laporan</a></li>
    <li <?=$this->uri->segment(1)=='generate_report'?'class="active"':''?>><a href="<?=base_url()?>generate_report">Generate Report</a></li>
</ul>