<div id="tabs-1">
  <div>
    <a href="<?=base_url()?>generate_report/excel/unique_identifier" type="button" class="btn btn-lg btn-primary" target="_blank">Download Excel</a>
    <a href="<?=base_url()?>pdf/save/unique_identifier" type="button" class="btn btn-lg btn-primary" target="_blank">Download PDF</a>
  </div>
  <div id="child_age" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
  <script>
      $.fn.pieChart('child_age','<?=base_url()?>generate_report/get/unique_identifier/child_age','Child Ages');
  </script>

  <div id="respondent_age" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
  <script>
      $.fn.pieChart('respondent_age','<?=base_url()?>generate_report/get/unique_identifier/respondent_age','Respondent Age');
  </script>

  <div id="respondent_education" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
  <script>
      $.fn.pieChart('respondent_education','<?=base_url()?>generate_report/get/unique_identifier/respondent_education','Respondent Education');
  </script>

  <div id="relation_to_child" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
  <script>
      $.fn.pieChart('relation_to_child','<?=base_url()?>generate_report/get/unique_identifier/relation_to_child','Respondent Relation To Child');
  </script>

  <div id="village" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
  <script>
      $.fn.pieChart('village','<?=base_url()?>generate_report/get/unique_identifier/village','Respondent Village');
  </script>
</div>