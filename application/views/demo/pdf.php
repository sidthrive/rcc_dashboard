
<!DOCTYPE html>
<html lang="en">
  <head>
    <link href="<?=base_url()?>asset/highchart/css/highcharts.css" rel="stylesheet">
    <style type="text/css">
      #load{
          width:100%;
          height:100%;
          position:fixed;
          z-index:9999;
          background:url("<?=base_url()?>asset/images/loading.gif") no-repeat center center rgba(0,0,0,0.25)
      }
    </style>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?=base_url()?>asset/js/jquery.min.js"></script>    
    <script src="https://code.highcharts.com/highcharts.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/canvg/1.4/rgbcolor.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/canvg/1.4/canvg.js"></script>
    
  </head>
  <body>
    <div class="ch" id="child_age" style="min-width: 800px; height: 600px; max-width: 800px; margin: 0 auto"></div>
    <div class="ch" id="respondent_age" style="min-width: 800px; height: 600px; max-width: 800px; margin: 0 auto"></div>
    <div class="ch" id="respondent_education" style="min-width: 800px; height: 600px; max-width: 800px; margin: 0 auto"></div>
    <div class="ch" id="relation_to_child" style="min-width: 800px; height: 600px; max-width: 800px; margin: 0 auto"></div>
    <div class="ch" id="village" style="min-width: 800px; height: 600px; max-width: 800px; margin: 0 auto"></div>
    <canvas id="canvas" style="display:none;"></canvas>
    <div id="load"></div>
  </body>
</html>

<script>
      function save_img(name){
        var svg = document.getElementById(name).children[0].innerHTML;
        canvg(document.getElementById('canvas'),svg);
        var img = canvas.toDataURL("image/png"); //img is data:image/png;base64
        img = img.replace('data:image/png;base64,', '');
        var data = "bin_data=" + img;
        $.ajax({
          type: "POST",
          url: "<?=base_url()?>pdf/savecharts/"+name,
          data: data
        });
      }

      function save_svg(name){
        var svg = document.getElementById(name).children[0].innerHTML;
        var svg64 = btoa(svg);
        var data = "svg_data=" + svg64;
        $.ajax({
          type: "POST",
          url: "<?=base_url()?>pdf/savechartssvg/"+name,
          data: data
        });
      }

      $.fn.pieChart = function(id,title,data,type){
        var jdata = JSON.parse(data);
        var chart = new Highcharts.chart(id, {
            chart: {
                type: type
            },
            title: {
                text: title
            },
            plotOptions: {
                series: {
                    animation: false,
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
              name: 'Jumlah',
              colorByPoint: true,
              data: jdata
          }]
        });
        save_img(id);
        $(".ch").hide();
    }
        $.fn.pieChart('child_age','Child Ages',<?=json_encode(file_get_contents(base_url().'generate_report/get/unique_identifier/child_age'))?>,'pie');
        $.fn.pieChart('respondent_age','Respondent Ages',<?=json_encode(file_get_contents(base_url().'generate_report/get/unique_identifier/respondent_age'))?>,'column');
        $.fn.pieChart('respondent_education','Respondent Education',<?=json_encode(file_get_contents(base_url().'generate_report/get/unique_identifier/respondent_education'))?>,'pie');
        $.fn.pieChart('relation_to_child','Respondent Relation To Child',<?=json_encode(file_get_contents(base_url().'generate_report/get/unique_identifier/relation_to_child'))?>,'pie');
        $.fn.pieChart('village','Respondent Village',<?=json_encode(file_get_contents(base_url().'generate_report/get/unique_identifier/village'))?>,'pie');
    </script>
