<div id="tabs-5">
  <div>
    <a href="<?=base_url()?>generate_report/excel/immunization_coverage" type="button" class="btn btn-lg btn-primary" target="_blank">Download Excel</a>
    <a href="<?=base_url()?>pdf/save/immunization_coverage" type="button" class="btn btn-lg btn-primary" target="_blank">Download PDF</a>
  </div>
                    <div id="have_mch_book" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('have_mch_book','<?=base_url()?>generate_report/get/immunization_coverage/have_mch_book','have_mch_book');
                      </script>
                      
                      <div id="hepb_0" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('hepb_0','<?=base_url()?>generate_report/get/immunization_coverage/hepb_0','hepb_0');
                      </script>
                      
                      <div id="hepb_1" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('hepb_1','<?=base_url()?>generate_report/get/immunization_coverage/hepb_1','hepb_1');
                      </script>
                      
                      <div id="hepb_2" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('hepb_2','<?=base_url()?>generate_report/get/immunization_coverage/hepb_2','hepb_2');
                      </script>
                      
                      <div id="hepb_4" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('hepb_4','<?=base_url()?>generate_report/get/immunization_coverage/hepb_4','hepb_4');
                      </script>
                  </div>