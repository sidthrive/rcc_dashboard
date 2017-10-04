<div id="tabs-2">
  <div>
    <a href="<?=base_url()?>generate_report/excel/household_character" type="button" class="btn btn-lg btn-primary" target="_blank">Download Excel</a>
    <a href="<?=base_url()?>pdf/save/household_character" type="button" class="btn btn-lg btn-primary" target="_blank">Download PDF</a>
  </div>
                      <div id="household_size" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('household_size','<?=base_url()?>generate_report/get/household_character/household_size','Household Size');
                      </script>
                      
                      <div id="dairy_product" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('dairy_product','<?=base_url()?>generate_report/get/household_character/dairy_product','Daily Product');
                      </script>
                      
                      <div id="number_of_rooms" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('number_of_rooms','<?=base_url()?>generate_report/get/household_character/number_of_rooms','Number of Rooms');
                      </script>
                      
                      <div id="informal_income" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('informal_income','<?=base_url()?>generate_report/get/household_character/informal_income','Informal Income');
                      </script>
                      
                      <div id="spent_on_food" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                      <script>
                          $.fn.pieChart('spent_on_food','<?=base_url()?>generate_report/get/household_character/spent_on_food','Spent on Food');
                      </script>
                  </div>