<div id="tabs-4">
  <div>
    <a href="<?=base_url()?>generate_report/excel/attitude_regarding_immunization" type="button" class="btn btn-lg btn-primary" target="_blank">Download Excel</a>
    <a href="<?=base_url()?>pdf/save/attitude_regarding_immunization" type="button" class="btn btn-lg btn-primary" target="_blank">Download PDF</a>
  </div>
                    <div id="heard_about_immu" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('heard_about_immu','<?=base_url()?>generate_report/get/knowledge_regarding_immunization/heard_about_immu','heard_about_immu');
                      </script>
                      
                      <div id="attitude_1" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('attitude_1','<?=base_url()?>generate_report/get/attitude_regarding_immunization/attitude_1','attitude_1');
                      </script>
                      
                      <div id="attitude_2" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('attitude_2','<?=base_url()?>generate_report/get/attitude_regarding_immunization/attitude_2','attitude_2');
                      </script>
                      
                      <div id="attitude_3" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('attitude_3','<?=base_url()?>generate_report/get/attitude_regarding_immunization/attitude_3','attitude_3');
                      </script>
                      
                      <div id="attitude_4" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('attitude_4','<?=base_url()?>generate_report/get/attitude_regarding_immunization/attitude_4','attitude_4');
                      </script>
                  </div>