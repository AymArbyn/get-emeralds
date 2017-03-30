$(function() {
    console.log("Loading page... DONE!");

    // chart colors
    var charts = ["dc1", "dc2", "dc3", "dc4", "ac1", "ac2"];
    var chartColors = ["#268bd2", "#d33682", "#859900", "#bb8800", "#269186", "#d83939"];
    var chartRef = [];
    var chartData = [];
    var chartState = [];
    var overviewData;

    // overview graph
    var overviewRef;
    var previousOutletPortLabel = charts[0];
    var currentOutletPortLabel = charts[0];
    var currentOutletPortExact = 0;
    var currentOutletPortPercentage = 0;
    var currentTotalUsage = 0;
    var maxWattageDisplay = 200;

    // iterative initialization of charts
    console.log("Loading usage history... ");
    var jqxhr = $.getJSON('/data/usage_history.php', function (outlet) {
        $.each(charts, function (i, value) {
            // hide other switches except for DC1
            if (i == 0) {
                $('#' + charts[i] + '-switch').removeClass("hidden");
            }

            // determine soft max wattage based on type of current
            var maxWattage = 200;
            var softMaxWattage = 40;
            if (charts[i].indexOf("dc") == -1) {
                maxWattage = 1200;
                softMaxWattage = 550;
            }

            // modify big label
            var lastIndex = outlet.data.usage[charts[i]].length - 1;
            $("#" + [charts[i]] + " .graph-label").html(charts[i].toUpperCase());

            // modify chart labels and values if port is enabled
            if (outlet.data.state[charts[i]] != 1) {
                $("#" + charts[i]).removeClass("disabled");
                $("#" + charts[i] + " .graph-exact").html((outlet.data.usage[charts[i]][lastIndex]).toFixed(1) + " W");
                $("#" + charts[i] + " .graph-percentage").html((outlet.data.usage[charts[i]][lastIndex] / maxWattage).toFixed(1) + "%");

                $('.bootstrap-switch-id-' + charts[i] + '-port-switch').removeClass("bootstrap-switch-off");
                $('.bootstrap-switch-id-' + charts[i] + '-port-switch').addClass("bootstrap-switch-on");
                $('#' + charts[i] + '-port-switch').bootstrapSwitch('disabled', false);

                $("#overview").removeClass("disabled");
                currentOutletPortExact = (outlet.data.usage[charts[0]][lastIndex]);
                currentOutletPortPercentage = currentOutletPortExact / maxWattage;
                currentTotalUsage = outlet.data.total[charts[0]];
                $("#total-usage .digidata-value").html(currentTotalUsage.toFixed(2) + " kWh");
                $("#usage-reading .digidata-value").html(currentOutletPortExact.toFixed(2) + " W");
                $("#utilization .digidata-value").html(currentOutletPortPercentage.toFixed(2) + "%");
                $("#power-limit .digidata-value").html(maxWattageDisplay + " W");
            }

            // modify chart itself
            chartRef[i] = new Highcharts.Chart([charts[i]] + "-graph", {
                chart: {
                    type: 'areaspline',
                    backgroundColor: 'transparent',
                    spacing: [0, 0, 0, 0],
                    margin: [0, 0, 0, 0],
                    connectEnds: false
                },
                title: {
                    text: ''
                },
                xAxis: {
                    labels: {
                        enabled: false
                    },
                    tickLength: 0,
                    maxPadding: 0,
                    minPadding: 0
                },
                yAxis: {
                    title: {
                        text: ''
                    },
                    labelsOptions: {
                        enabled: false
                    },
                    maxPadding: 0,
                    minPadding: 0,
                    softMax: softMaxWattage
                },
                exporting: {
                    enabled: false
                },
                tooltip: {
                    enabled: false
                },
                plotOptions: {
                    areaspline: {
                        marker: {
                            enabled: false,
                            states: {
                                hover: {
                                    enabled: false
                                }
                            }
                        }
                    }
                },
                credits: {
                    enabled: false
                },
                legend: {
                    enabled: false
                },
                series: [{
                    name: charts[i].toUpperCase(),
                    data: (function () {
                        // get usage history first
                        // console.log(outlet.data.usage[charts[i]]);
                        chartData[i] = outlet.data.usage[charts[i]];
                        chartState[i] = outlet.data.state[charts[i]];
                        return outlet.data.usage[charts[i]];
                    }()),
                    color: chartColors[i],
                    fillOpacity: 0.1,
                    animation: false
                }]
            });
        });

        overviewRef = new Highcharts.Chart("overview-graph", {
            chart: {
                type: 'areaspline',
                backgroundColor: 'transparent',
                spacing: [0, 0, 0, 0],
                margin: [0, 0, 0, 0],
                connectEnds: false
            },
            exporting: {
                buttons: {
                    contextButton: {
                        x: -10,
                        y: 8
                    }
                }
            },
            title: {
                text: ''
            },
            xAxis: {
                labels: {
                    enabled: false
                },
                tickLength: 0,
                maxPadding: 0,
                minPadding: 0
            },
            yAxis: {
                gridLineColor: "#ccc",
                maxPadding: 0,
                minPadding: 0,
                softMax: 60,
                title: {
                    text: ''
                }
            },
            navigation: {
                buttonOptions: {
                    theme: {
                        fill: "#ecf1f1"
                    }
                }
            },
            plotOptions: {
                areaspline: {
                    marker: {
                        enabled: false
                    }
                }
            },
            tooltip: {
                crosshairs: true
            },
            credits: {
                enabled: false
            },
            legend: {
                enabled: false
            },
            series: [{
                name: currentOutletPortLabel,
                data: (function () {
                    // get usage history first
                    overviewData= Array.from(chartData[0]);
                    return overviewData;
                }()),
                color: chartColors[0],
                fillOpacity: 0.1,
                animation: false
            }]
        });
    });

    // update data
    jqxhr.done(function () {
        console.log("DONE!");

        setInterval(function () {
            console.log("Loading current usage... ");

            var jqxhs = $.getJSON('/data/current_usage.php', function (outlet) {
                // disable graphs first, before anything else
                for (var i = charts.length - 1; i >= 0; i--) {
                    if (outlet.data.state[charts[i]] != 1) {
                        $("#" + charts[i]).addClass("disabled");
                        $(".overview-link.disabled .graph-exact").html("Turned Off");
                        $("#overview").addClass("disabled");

                        $('.bootstrap-switch-id-' + charts[i] + '-port-switch').addClass("bootstrap-switch-off");
                        $('.bootstrap-switch-id-' + charts[i] + '-port-switch').removeClass("bootstrap-switch-on");
                        $('#' + charts[i] + '-port-switch').bootstrapSwitch('disabled', true);
                    }
                    else {
                        $("#" + charts[i]).removeClass("disabled");
                        $("#overview").removeClass("disabled");
                        $("#total-usage .digidata-value").html(currentTotalUsage.toFixed(2) + " kWh");
                        $("#usage-reading .digidata-value").html(currentOutletPortExact.toFixed(2) + " W");
                        $("#utilization .digidata-value").html(currentOutletPortPercentage.toFixed(2) + " %");
                        $("#power-limit .digidata-value").html(maxWattageDisplay + " W");
                        $('.bootstrap-switch-id-' + charts[i] + '-port-switch').removeClass("bootstrap-switch-off");
                        $('.bootstrap-switch-id-' + charts[i] + '-port-switch').addClass("bootstrap-switch-on");
                        $('#' + charts[i] + '-port-switch').bootstrapSwitch('disabled', false);
                    }
                }

                for (var i = charts.length - 1; i >= 0; i--) {
                    // determine soft max wattage based on type of current
                    var maxWattage = 200;
                    var softMaxWattage = 40;
                    if (charts[i].indexOf("dc") == -1) {
                        maxWattage = 1200;
                        softMaxWattage = 550;
                    }

                    // modify chart labels and values if port is enabled
                    if (outlet.data.state[charts[i]] != 1) {
                        $("#" + [charts[i]] + " .graph-exact").html(outlet.data.usage[charts[i]].y.toFixed(1) + " W");
                        $("#" + [charts[i]] + " .graph-percentage").html((outlet.data.usage[charts[i]].y / maxWattage).toFixed(1) + "%");
                        $("#total-usage .digidata-value").html(currentTotalUsage.toFixed(2) + " kWh");
                        $("#usage-reading .digidata-value").html(currentOutletPortExact.toFixed(2) + " W");
                        $("#utilization .digidata-value").html(currentOutletPortPercentage.toFixed(2) + "%");
                        $("#power-limit .digidata-value").html(maxWattageDisplay + " W");
                    }

                    // var x = (outlet.data.usage[charts[i]]).x;
                    var y = (outlet.data.usage[charts[i]]).y;
                    chartRef[i].series[0].addPoint(y, true, true);
                    chartData[i].push(y);
                    chartData[i].shift();

                    chartState[i] = outlet.data.state[charts[i]];

                    if (charts[i] == currentOutletPortLabel) {
                        overviewRef.series[0].addPoint(y, true, true);
                        currentOutletPortExact = y;
                        currentOutletPortPercentage = y / maxWattage;
                        currentTotalUsage = outlet.data.total[charts[i]];
                    }
                }
            })
            .fail(function () {
                console.log("FAILED!");

                // disable graphs first, before anything else
                for (var i = charts.length - 1; i >= 0; i--) {
                    $("#" + charts[i]).addClass("disabled");
                    $(".overview-link.disabled .graph-exact").html("Turned Off");
                        $("#overview").addClass("disabled");
                }
            })
            .done(function () {
                console.log("DONE!");

                // turn switch off if disabled quick-info is clicked
                $(".overview-link.disabled").on("click", function () {
                    $('.bootstrap-switch-id-port-switch').addClass("bootstrap-switch-off");
                    $('.bootstrap-switch-id-port-switch').removeClass("bootstrap-switch-on");
                });

                // turn switch on if enabled quick-info is clicked
                $(".overview-link").not(".disabled").on("click", function () {
                    $('.bootstrap-switch-id-port-switch').addClass("bootstrap-switch-on");
                    $('.bootstrap-switch-id-port-switch').removeClass("bootstrap-switch-off");
                });

                // change the selected link to the clicked link
                $(".overview-link").on("click", function () {
                    previousOutletPortLabel = currentOutletPortLabel;
                    currentOutletPortLabel = $(this).attr('id');
                    $("#current-port-label").html(currentOutletPortLabel.toUpperCase() + " Outlet Port");
                    $(".overview-link.selected").removeClass("selected");
                    $(this).addClass("selected");

                    var index = parseInt(currentOutletPortLabel.charAt(2)) - 1;
                    if (currentOutletPortLabel[0] == "a") {
                        index = index + 4;
                        maxWattage = 1200;
                    }
                    else {
                        maxWattage = 200;
                    }
                    maxWattageDisplay = maxWattage;

                    // update the whole chart in the overview
                    $("#overview").removeClass(previousOutletPortLabel + "-overview").addClass(charts[index] + "-overview");

                    overviewData = Array.from(chartData[index]);
                    overviewRef.update({
                        series: [{
                            data: overviewData,
                            color: chartColors[index]
                        }]
                    });
                    $(".digidata").removeClass(previousOutletPortLabel + "-data").addClass(charts[index] + "-data");

                    // update digital data shown
                    $("#total-usage .digidata-value").html(currentTotalUsage.toFixed(2) + " kWh");
                    $("#usage-reading .digidata-value").html(currentOutletPortExact.toFixed(2) + " W");
                    $("#utilization .digidata-value").html(currentOutletPortPercentage.toFixed(2) + "%");
                    $("#power-limit .digidata-value").html(maxWattageDisplay + " W");
                });
            });
        }, 4000);
    })
    .fail(function () {
        console.log("FAILED!");

        // disable graphs first, before anything else
        for (var i = charts.length - 1; i >= 0; i--) {
            $("#" + charts[i]).addClass("disabled");
            $(".overview-link.disabled .graph-exact").html("Turned Off");
        }
        $("#overview").addClass("disabled");
    });
});
