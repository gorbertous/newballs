import {ajaxPath, environment} from "../Constants";

export default class Dashboard {

    constructor() {
        // do not execute this if we are on the modal 'filter'
        if ($('#form-filter').length === 0) {
            this.meteoLux();
            this.mainTabs();

            let $this = this;
            $.getScript( "https://www.gstatic.com/charts/loader.js", function( data, textStatus, jqxhr ) {
                if (textStatus === 'success') {
                    google.charts.load("current", {packages: ["corechart"]});
                    google.charts.setOnLoadCallback($this.drawWorkunitsChart);
                    google.charts.setOnLoadCallback($this.drawAbsencesChart);
                } else {
                    if (environment === 'dev') {
                        console.log('Error loading JS charts! ', textStatus, jqxhr);
                    }
                }
            });
        }
    }

    mainTabs() {
        let $tabs   = $('.dashboard-todos-tabs').find('.tab-pane');
        let $menuLi = $('.dashboard-todos-li');

        $.each($tabs, function() {
            let lengthBox = $(this).children('#box').length;
            let idBox     = $(this).attr('id');
            let $liMenu   = $menuLi.find('li.' + idBox);

            if (lengthBox > 0) {
                $liMenu.find('.number-items-circle').html(lengthBox);

                let $boxContainsBgClass = $(this).find('.small-box');

                $.each($boxContainsBgClass, function() {
                    let $bgBox = $(this).attr('class');

                    if ($bgBox.indexOf('bg-yellow') >= 0) {
                        $liMenu.find('.number-items-circle').css('background-color', '#ffa64d');
                    } else if ($bgBox.indexOf('bg-red') >= 0) {
                        $liMenu.find('.number-items-circle').css('background-color', '#ff6666');

                        // stop the loop execution if we found a red alert
                        return false;
                    }
                });
            } else {
                $(this).remove();
                $liMenu.remove();
            }
        });
    }

    drawWorkunitsChart() {
        let data = new google.visualization.DataTable();

        $.ajax({
            url      : ajaxPath + '/dashboard/piechart',
            dataType : "json",
            async    : false
        })
        .done(function(jsonData) {
            data.addColumn('string', 'TotalWorkers');
            data.addColumn('number', 'Workunit');

            $.each(jsonData.data, function (i, jsonData) {
                data.addRows([[
                    jsonData.workunit,
                    parseInt(jsonData.totalWorkers)
                ]]);
            });

            if (jsonData.data.length === 0) {
                $('.piechart-container').remove();
            } else {
                const options = {
                    height: 400,
                    sliceVisibilityThreshold: 0.022,
                    is3D: true,
                    datalessRegionColor: '#dedede',
                    defaultColor: '#dedede',
                    legend: {
                        position: 'top',
                        maxLines: 5
                    },
                    chartArea: {
                        top: 120
                    },
                    colorAxis: {
                        colors: ['#54C492', '#cc0000']
                    }
                };

                const pieChart = new google.visualization.PieChart(document.getElementById('piechart_content'));

                pieChart.draw(data, options);
            }
        })
        .fail(function() {
            $('#piechart_content').html('An error occurred while loading the chart.');
        });
    }

    drawAbsencesChart() {
        let data = new google.visualization.DataTable();

        $.ajax({
            url      : ajaxPath + '/dashboard/areachart',
            dataType : "json",
            async    : false
        })
        .done(function(jsonData) {
            data.addColumn('date', jsonData.text_start);
            data.addColumn('number', jsonData.text_sickness);
            data.addColumn('number', jsonData.text_family);
            data.addColumn('number', jsonData.text_accident);
            data.addColumn('number', jsonData.text_parental);

            data.addColumn({type: 'string', role: 'tooltip'});

            $.each(jsonData.data, function (i, jsonData) {
                data.addRows([[
                    new Date(jsonData.start),
                    parseInt(jsonData.sickness),
                    parseInt(jsonData.family),
                    parseInt(jsonData.accident),
                    parseInt(jsonData.parental),
                    jsonData.tooltip
                ]]);
            });

            if (jsonData.data.length === 0) {
                $('.areachart-container').remove();
            } else {
                const options = {
                    height: 400,
                    hAxis: {
                        title: jsonData.text_month,
                        titleTextStyle: {
                            color: '#333'
                        }
                    },
                    vAxis: {
                        title: jsonData.text_hours,
                        minValue: 0
                    },
                    legend: {
                        position: 'top',
                        maxLines: 5
                    }
                };

                const areaChart = new google.visualization.AreaChart(document.getElementById('areachart_content'));

                areaChart.draw(data, options);
            }
        })
        .fail(function() {
            $('#areachart_content').html('An error occurred while loading the chart.');
        });
    }

    meteoLux() {
        $.getScript( "/static/libs/js/meteolux.js", function( data, textStatus, jqxhr ) {
            if (textStatus === 'success') {
                try {
                    new Meteolux({
                        theme: "light",
                        dropShadow: false,
                        maxWidth: "800",
                        displayAlert: true,
                        displayToday: true,
                        displayTodayNext: true,
                        nextDaysNumber: 4,
                        containerID: "meteolux-widget"
                    });
                } catch (e) {
                    return false;
                }
            } else {
                if (environment === 'dev') {
                    console.log('Error loading meteolux JS!', textStatus, jqxhr);
                }
            }
        });
    }

}