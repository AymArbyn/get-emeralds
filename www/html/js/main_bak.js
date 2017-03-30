$(function() {
    /* SIDEBAR ========================================================= */

    // disable all readers at start
    // $("#quick-info .overview-link").addClass("disabled");

    // change text of disabled reading in sidebar
    $(".overview-link.disabled .graph-exact").html("Turned Off");

    // change the selected link to the clicked link
    $(".overview-link").not(".disabled").on("click", function () {
        $(".overview-link.selected").removeClass("selected");
        $(this).addClass("selected");
    });

    $.getJSON('/data/usage_history.php', function (usage) {
        var globalData = [];

        // Overview chart
        Highcharts.chart('overview-graph', {
            chart: {
                type: 'spline',
                backgroundColor: 'transparent',
                spacing: [0, 0, 0, 0],
                margin: [0, 0, 0, 0],
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0];
                        setInterval(function () {
                            $.getJSON('/data/current_usage.php', function (activity) {
                                globalData = activity.data;
                            });
                        }, 2000);
                    }
                }
            },
            title: titleOptions,
            xAxis: xAxisOptions,
            yAxis: overviewyAxisOptions,
            exporting: exportingOptions,
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                        Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                        Highcharts.numberFormat(this.y, 2);
                }
            },
            plotOptions: plotOptions,
            credits: creditsOptions,
            legend: legendOptions,
            series: seriesOptions
        });

        // DC1 chart
        var dc1LastIndex = usage.data.dc1.length - 1;
        $("#dc1 .graph-exact").html(usage.data.dc1[dc1LastIndex].y + " W");
        $("#dc1 .graph-percentage").html(Math.round(usage.data.dc1[dc1LastIndex].y / 200) + "%");
        Highcharts.chart('dc1-graph', {
            chart: {
                type: 'area',
                animation: Highcharts.svg, // don't animate in old IE
                backgroundColor: 'transparent',
                spacing: [0, 0, 0, 0],
                margin: [0, 0, 0, 0],
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0];
                        setInterval(function () {
                            series.addPoint([
                                globalData.usage.dc1.x,
                                globalData.usage.dc1.y
                            ])
                        }, 2000);
                    }
                }
            },
            title: titleOptions,
            xAxis: xAxisOptions,
            yAxis: DCyAxisOptions,
            exporting: exportingOptions,
            tooltip: tooltipOptions,
            plotOptions: plotOptions,
            credits: creditsOptions,
            legend: legendOptions,
            series: [{
                name: "DC1",
                data: (function () {
                    // get usage history first
                    return usage.data.dc1;
                }()),
                color: chartColors["blue"],
                fillOpacity: 0.1
            }]
        });

        // DC2 chart
        var dc2LastIndex = usage.data.dc2.length - 1;
        $("#dc2 .graph-exact").html(usage.data.dc2[dc2LastIndex].y + " W");
        $("#dc2 .graph-percentage").html(Math.round(usage.data.dc2[dc2LastIndex].y / 200) + "%");
        Highcharts.chart('dc2-graph', {
            chart: {
                type: 'area',
                animation: Highcharts.svg, // don't animate in old IE
                backgroundColor: 'transparent',
                spacing: [0, 0, 0, 0],
                margin: [0, 0, 0, 0],
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0];
                        setInterval(function () {
                            series.addPoint([
                                globalData.usage.dc2.x,
                                globalData.usage.dc2.y
                            ])
                        }, 2000);
                    }
                }
            },
            title: titleOptions,
            xAxis: xAxisOptions,
            yAxis: DCyAxisOptions,
            exporting: exportingOptions,
            tooltip: tooltipOptions,
            plotOptions: plotOptions,
            credits: creditsOptions,
            legend: legendOptions,
            series: [{
                name: "DC2",
                data: (function () {
                    // get usage history first
                    return usage.data.dc2;
                }()),
                color: chartColors["purple"],
                fillOpacity: 0.1
            }]
        });

        // DC3 chart
        var dc3LastIndex = usage.data.dc3.length - 1;
        $("#dc3 .graph-exact").html(usage.data.dc3[dc3LastIndex].y + " W");
        $("#dc3 .graph-percentage").html(Math.round(usage.data.dc3[dc3LastIndex].y / 200) + "%");
        Highcharts.chart('dc3-graph', {
            chart: {
                type: 'area',
                animation: Highcharts.svg, // don't animate in old IE
                backgroundColor: 'transparent',
                spacing: [0, 0, 0, 0],
                margin: [0, 0, 0, 0],
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0];
                        setInterval(function () {
                            series.addPoint([
                                globalData.usage.dc3.x,
                                globalData.usage.dc3.y
                            ])
                        }, 2000);
                    }
                }
            },
            title: titleOptions,
            xAxis: xAxisOptions,
            yAxis: DCyAxisOptions,
            exporting: exportingOptions,
            tooltip: tooltipOptions,
            plotOptions: plotOptions,
            credits: creditsOptions,
            legend: legendOptions,
            series: [{
                name: "DC3",
                data: (function () {
                    // get usage history first
                    return usage.data.dc3;
                }()),
                color: chartColors["green"],
                fillOpacity: 0.1
            }]
        });

        // DC4 chart
        var dc4LastIndex = usage.data.dc4.length - 1;
        $("#dc4 .graph-exact").html(usage.data.dc4[dc4LastIndex].y + " W");
        $("#dc4 .graph-percentage").html(Math.round(usage.data.dc4[dc4LastIndex].y / 200) + "%");
        Highcharts.chart('dc4-graph', {
            chart: {
                type: 'area',
                animation: Highcharts.svg, // don't animate in old IE
                backgroundColor: 'transparent',
                spacing: [0, 0, 0, 0],
                margin: [0, 0, 0, 0],
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0];
                        setInterval(function () {
                            series.addPoint([
                                globalData.usage.dc4.x,
                                globalData.usage.dc4.y
                            ])
                        }, 2000);
                    }
                }
            },
            title: titleOptions,
            xAxis: xAxisOptions,
            yAxis: DCyAxisOptions,
            exporting: exportingOptions,
            tooltip: tooltipOptions,
            plotOptions: plotOptions,
            credits: creditsOptions,
            legend: legendOptions,
            series: [{
                name: "DC4",
                data: (function () {
                    // get usage history first
                    return usage.data.dc4;
                }()),
                color: chartColors["yellow"],
                fillOpacity: 0.1
            }]
        });

        // AC1 chart
        var ac1LastIndex = usage.data.ac1.length - 1;
        $("#ac1 .graph-exact").html(usage.data.ac1[ac1LastIndex].y + " W");
        $("#ac1 .graph-percentage").html(Math.round(usage.data.ac1[ac1LastIndex].y / 1200) + "%");
        Highcharts.chart('ac1-graph', {
            chart: {
                type: 'area',
                animation: Highcharts.svg, // don't animate in old IE
                backgroundColor: 'transparent',
                spacing: [0, 0, 0, 0],
                margin: [0, 0, 0, 0],
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0];
                        setInterval(function () {
                            series.addPoint([
                            globalData.usage.ac1.x,
                            globalData.usage.ac1.y
                        ])
                        }, 2000);
                    }
                }
            },
            title: titleOptions,
            xAxis: xAxisOptions,
            yAxis: DCyAxisOptions,
            exporting: exportingOptions,
            tooltip: tooltipOptions,
            plotOptions: plotOptions,
            credits: creditsOptions,
            legend: legendOptions,
            series: [{
                name: "AC1",
                data: (function () {
                    // get usage history first
                    return usage.data.ac1;
                }()),
                color: chartColors["cyan"],
                fillOpacity: 0.1
            }]
        });

        // AC2 chart
        var ac2LastIndex = usage.data.ac2.length - 1;
        $("#ac2 .graph-exact").html(usage.data.ac2[ac2LastIndex].y + " W");
        $("#ac2 .graph-percentage").html(Math.round(usage.data.ac2[ac2LastIndex].y / 1200) + "%");
        Highcharts.chart('ac2-graph', {
            chart: {
                type: 'area',
                animation: Highcharts.svg, // don't animate in old IE
                backgroundColor: 'transparent',
                spacing: [0, 0, 0, 0],
                margin: [0, 0, 0, 0],
                events: {
                    load: function () {
                        // set up the updating of the chart each second
                        var series = this.series[0];
                        setInterval(function () {
                            series.addPoint([
                            globalData.usage.ac2.x,
                            globalData.usage.ac2.y
                        ])
                        }, 2000);
                    }
                }
            },
            title: titleOptions,
            xAxis: xAxisOptions,
            yAxis: DCyAxisOptions,
            exporting: exportingOptions,
            tooltip: tooltipOptions,
            plotOptions: plotOptions,
            credits: creditsOptions,
            legend: legendOptions,
            series: [{
                name: "AC2",
                data: (function () {
                    // get usage history first
                    return usage.data.ac2;
                }()),
                color: chartColors["red"],
                fillOpacity: 0.1
            }]
        });
    });
});

/* VARIABLES =========================================================== */

// chart options
var chartColors = {
    "blue": "#268bd2",
    "purple": "#d33682",
    "green": "#859900",
    "yellow": "#bb8800",
    "cyan": "#269186",
    "red": "#d83939"
},
titleOptions = {
    text: ''
},
xAxisOptions = {
    labels: {
        enabled: false
    },
    tickLength: 0,
    maxPadding: 0,
    minPadding: 0
},
overviewyAxisOptions = {
    title: {
        text: ''
    },
    maxPadding: 0,
    minPadding: 0,
    softMax: 500
},
DCyAxisOptions = {
    title: {
        text: ''
    },
    labelsOptions: {
        enabled: false
    },
    maxPadding: 0,
    minPadding: 0,
    softMax: 200
},
ACyAxisOptions = {
    title: {
        text: ''
    },
    labelsOptions: {
        enabled: false
    },
    maxPadding: 0,
    minPadding: 0,
    softMax: 1200
},
exportingOptions = {
    enabled: false
},
tooltipOptions = {
    enabled: false
},
plotOptions = {
    area: {
        marker: {
            enabled: false,
            states: {
                hover: {
                    enabled: false
                }
            }
        }
    },
    spline: {
        marker: {
            enabled: false
        }
    }
},
creditsOptions = {
    enabled: false
},
legendOptions = {
    enabled: false
},
seriesOptions = [{
    name: 'Random data',
    data: (function () {
        // generate an array of random data
        var data = [],
            time = (new Date()).getTime(),
            i;

        for (i = -100; i <= 0; i += 1) {
            data.push({
                x: time + i * 1000,
                y: 1 + Math.random() / 10
            });
        }
        return data;
    }())
}];