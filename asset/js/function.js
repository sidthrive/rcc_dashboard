function setLocation(url,p,d,sd,v,sv){
    $.get(url+"data/getLocation/District/Jakarta", function(data, status){
        district = jQuery.parseJSON(data);
        var value = d;
        $.each(district,function(index,name){
            $("#District").append('<option value="'+name+'" '+(value==name?'selected':'')+' >'+name+'</option>');
        });
        $.get(url+"data/getLocation/Sub-district/"+value, function(data, status){
            sub_district = jQuery.parseJSON(data);
            var value = sd;
            $.each(sub_district,function(index,name){
                $("#Sub-district").append('<option value="'+name+'" '+(value==name?'selected':'')+' >'+name+'</option>');
            });
            $.get(url+"data/getLocation/Village/"+value, function(data, status){
                village = jQuery.parseJSON(data);
                var value = v;
                $.each(village,function(index,name){
                    $("#Village").append('<option value="'+name+'" '+(value==name?'selected':'')+' >'+name+'</option>');
                });
                $.get(url+"data/getLocation/Sub-village/"+value, function(data, status){
                    subvillage = jQuery.parseJSON(data);
                    var value = sv;
                    $.each(subvillage,function(index,name){
                        $("#Sub-village").append('<option value="'+name+'" '+(value==name?'selected':'')+' >'+name+'</option>');
                    });
                });
            });
        });
    });
}

function updateLocation(url, level, loc){
    if(level=='District'){
        $('#Sub-district').children('option:not(:first)').remove();
        $('#Village').children('option:not(:first)').remove();
        $('#Sub-village').children('option:not(:first)').remove();
        $.get(url+"data/getLocation/Sub-district/"+loc, function(data, status){
            sub_district = jQuery.parseJSON(data);
            $.each(sub_district,function(index,name){
                $("#Sub-district").append('<option value="'+name+'" >'+name+'</option>');
            });

        });
    }else if(level=='Sub-district'){
        $('#Village').children('option:not(:first)').remove();
        $('#Sub-village').children('option:not(:first)').remove();
        $.get(url+"data/getLocation/Village/"+loc, function(data, status){
            village = jQuery.parseJSON(data);
            $.each(village,function(index,name){
                $("#Village").append('<option value="'+name+'" >'+name+'</option>');
            });

        });
    }else if(level=='Village'){
        $('#Sub-village').children('option:not(:first)').remove();
        $.get(url+"data/getLocation/Sub-village/"+loc, function(data, status){
            subvillage = jQuery.parseJSON(data);
            $.each(subvillage,function(index,name){
                $("#Sub-village").append('<option value="'+name+'" >'+name+'</option>');
            });
        });
    }
    
}

$.fn.pieChart = function(id, url, title){
    var chart = new Highcharts.chart(id, {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: title
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.y}</b><br>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        }
    });
    chart.showLoading('Loading data ...');
    $.getJSON(url, function (csv) {
        chart.addSeries({
            name: 'Jumlah',
            colorByPoint: true,
            data: csv
        },true);
        chart.hideLoading();
    });
}