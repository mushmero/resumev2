$(document).ready(function() {

    $.ajax({
        url: "/auth/check",
        datatype: 'json',
        success: function(data){
            if(data == 1){
                disabledLink();
                hideMenu();
                experiences();
                loadmap();
            }
        }
    });
    // remove aler on 3000ms
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
});

function disabledLink()
{
    $('*').each(function() {
        if ($(this).hasClass('disabled')) {
            $(this).on('click', function(e){
                e.preventDefault();
            });
        }
    });
}

function hideMenu(){
    $('*').each(function() {
        let hideCount = 0;
        if ($(this).hasClass('has-treeview')) {
            var submenuCount = $(this).children().next('.nav-treeview').children().length;
            var submenu = $(this).children().next('.nav-treeview').children();
            for(let i = 0; i < submenuCount; i++){
                if($(submenu[i]).hasClass('hide')){
                    hideCount++;
                }
            }
            if(submenuCount == hideCount){
                $(this).find('.nav-link').addClass('hide');
            }
        }
    });
}

function experiences()
{
    $('#startDate').on('blur', function(){
        $('#startDate').datetimepicker('hide');
    });
    $('#endDate').on('blur', function(){
        $('#endDate').datetimepicker('hide');
    });

    $('#current').on('change', function(e){
        if($('#current:checked').length > 0){
            $('#endDate').parent().hide();
        }else{
            $('#endDate').parent().show();
        }
    });
    
    if($('#current:checked').length > 0){
        $('#endDate').parent().hide();
    }else{
        $('#endDate').parent().show();
    }
}

function loadmap(){

    $.ajax({
        url: "/getAllCountries",
        async: false,
        datatype: 'json',
        success: function(data){
            countries = JSON.parse(data);
        }
    });
    
    $.ajax({
        url: "/getVisitorByCountry",
        async: false,
        datatype: 'json',
        success: function(data){
            if(data){
                visitordata = JSON.parse(data);
                var plotData = plotMap(visitordata);

                $(".visitor_map").mapael({
                    map: {
                        // Set the name of the map to display
                        name: "world_countries",
                        zoom: {
                            enabled: true,
                            maxLevel: 10
                        },
                    },
                    areas: plotData.areas,
                    legend: {
                        area : plotData.legend
                    },
                });
            }else{
                $(".visitor_map").mapael({
                    map: {
                        // Set the name of the map to display
                        name: "world_countries",
                        zoom: {
                            enabled: true,
                            maxLevel: 10
                        },
                    },
                });            
            }
        }
    });
}

function plotMap(visitordata){

    var areas = {};  
    var plots = {};
    var plotdata;
    var info;
    var areaslices = {};
    var countries;
    var visitordata;

    plotdata = JSON.parse(visitordata.data);
    info = JSON.parse(visitordata.info);
    $.each(plotdata, function(id,elem){
        var plot = {};
        plot.latitude = elem.latitude;
        plot.longitude = elem.longitude;
        plot.value = elem.visitor;
        plot.tooltip = {
            content: elem.country+"<br>Visitor: "+elem.visitor
        }
        plots[elem.countryCode] = plot;
    });

    var nullareaslice = {};
    var minareaslice = {};
    var betweenareaslice = {};
    var maxareaslice = {};
        nullareaslice.max = 0;
        nullareaslice.attrs = {fill: "rgba(0,0,0,0.5)"};
        nullareaslice.label = "0";

        minareaslice.mix = 1;
        minareaslice.max = 500;
        minareaslice.attrs = {fill: "#00E5FF"};
        minareaslice.label = "< 500";

        betweenareaslice.min = 500;
        betweenareaslice.max = 1500;
        betweenareaslice.attrs = {fill: "#FFC400"};
        betweenareaslice.label = "> 500 and < 1500";

        maxareaslice.min = 1500;
        maxareaslice.attrs = {fill: "#64DD17"};
        maxareaslice.label = "> 1500";

        areaslices = [nullareaslice, minareaslice, betweenareaslice, maxareaslice];

    var arealegend = {
        display: true,
        mode: "horizontal",
        title: "Visitors",
        labelAttrs: {
            "font-size": 10
        },
        marginLeft: 5,
        marginLeftLabel: 5,
        slices: areaslices,
    };
    var pdata = {};
    $.each(plotdata, function(key,data){
        var pd = {};
        pd.country = data.country;
        pd.value = data.visitor;
        pdata[data.countryCode] = pd;
    });
    $.each(countries, function(id, elem){
        if(id in pdata){
            var v = pdata[id].value;
            var c = pdata[id].country;
            areas[id] = {
                value: v,
                tooltip : {
                    content: c+"<br>Visitor: "+v,
                },
            }; 
        }else{
            areas[id] = {
                value: 0,
                tooltip : {
                    content: elem+"<br>Visitor: 0",
                },
            }
        }
    });

    return {'areas' : areas, 'legend' : arealegend};

}