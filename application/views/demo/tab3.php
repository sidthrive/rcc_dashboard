<div id="tabs-3">
  <div>
    <a href="<?=base_url()?>generate_report/excel/health_seeking_behaviour" type="button" class="btn btn-lg btn-primary" target="_blank">Download Excel</a>
    <a href="<?=base_url()?>pdf/save/health_seeking_behaviour" type="button" class="btn btn-lg btn-primary" target="_blank">Download PDF</a>
  </div>
                    <div id="anc_visit_num" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('anc_visit_num','<?=base_url()?>generate_report/get/health_seeking_behaviour/anc_visit_num','anc_visit_num');
                      </script>
                      
                      <div id="place_of_birth" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('place_of_birth','<?=base_url()?>generate_report/get/health_seeking_behaviour/place_of_birth','place_of_birth');
                      </script>
                      
                      <div id="attendance_at_posyandu" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('attendance_at_posyandu','<?=base_url()?>generate_report/get/health_seeking_behaviour/attendance_at_posyandu','attendance_at_posyandu');
                      </script>
                      
                      <div id="attendance_at_puskesmas" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('attendance_at_puskesmas','<?=base_url()?>generate_report/get/health_seeking_behaviour/attendance_at_puskesmas','attendance_at_puskesmas');
                      </script>
                      
                      <div id="action_taken" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('action_taken','<?=base_url()?>generate_report/get/health_seeking_behaviour/action_taken','action_taken');
                      </script>
                  </div>