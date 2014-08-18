define([
  'app',
  'highcharts',
  'highcharts-ng'
], function(app) {
  app.registerService('StaffQueryCharts', [
    function() {
      var colors = ['#428bca', '#5cb85c', '#f0ad4e', '#910000', '#1aadce', '#2f7ed8', '#8bbc21',
        '#492970', '#f28f43', '#77a1e5', '#c42525', '#a6c96a', '#0d233a'
      ];
      return {
        gradeColumn: {
          //This is not a highcharts object. It just looks a little like one!
          options: {
            //This is the Main Highcharts chart config. Any Highchart options are valid here.
            //will be ovverriden by values specified below.
            chart: {
              type: 'column'
            },
            credits: {
              enabled: null
            },
            colors: colors,
            tooltip: {
              // shared: true,
              // crosshairs: true,
              borderColor: '#ccc',
              style: {
                padding: 10,
                fontWeight: 'normal'
              },
            },
            legend: {
              // verticalAlign: 'top',
              enabled: false
            },

          },

          //The below properties are watched separately for changes.

          //Series object (optional) - a list of series using normal highcharts series options.
          series: [{
            name: 'Things',
            // colorByPoint: true,
            data: [{
              name: 'Animals',
              y: 5,
              drilldown: 'animals'
            }, {
              name: 'Fruits',
              y: 2,
              drilldown: 'fruits'
            }, {
              name: 'Cars',
              y: 4,
              color: colors[3],
              drilldown: 'cars'
            }]
          }],
          //Title configuration (optional)
          title: {
            text: null
          },
          //Boolean to control showng loading status on chart (optional)
          // loading: false,
          //Configuration for the xAxis (optional). Currently only one x axis can be dynamically controlled.
          //properties currentMin and currentMax provied 2-way binding to the chart's maximimum and minimum
          xAxis: {
            type: 'category'
          },
          yAxis: {
            min: 0,
            title: {
              text: null
            },
            labels: {
              formatter: function() {
                return Math.round(this.value);
              }
            },
          },
          //Whether to use HighStocks instead of HighCharts (optional). Defaults to false.
          useHighStocks: false,
          //size (optional) if left out the chart will default to size of the div or something sensible.
          size: {
            height: 400
          },
          //function (optional)
          func: function(chart) {
            //setup some logic for the chart
          }
        },
        eduPie: {
          //This is not a highcharts object. It just looks a little like one!
          options: {
            //This is the Main Highcharts chart config. Any Highchart options are valid here.
            //will be ovverriden by values specified below.
            chart: {
              type: 'pie'
            },
            credits: {
              enabled: null
            },
            colors: colors,
            tooltip: {
              style: {
                padding: 10,
                fontWeight: 'normal'
              },
              pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotBackgroundColor: null,
            plotBorderWidth: 1,//null,
            plotShadow: false,
            legend: {
              // verticalAlign: 'top',
              enabled: false
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
            },
          },

          //The below properties are watched separately for changes.

          //Series object (optional) - a list of series using normal highcharts series options.
          series: [{
            type: 'pie',
            name: 'Browser share',
            data: [
                ['Firefox',   45.0],
                ['IE',       26.8],
                {
                    name: 'Chrome',
                    y: 12.8,
                    sliced: true,
                    selected: true
                },
                ['Safari',    8.5],
                ['Opera',     6.2],
                ['Others',   0.7]
            ]
          }],
          //Title configuration (optional)
          title: {
            text: null
          },
          //Boolean to control showng loading status on chart (optional)
          // loading: false,
          //Configuration for the xAxis (optional). Currently only one x axis can be dynamically controlled.
          //properties currentMin and currentMax provied 2-way binding to the chart's maximimum and minimum
          yAxis: {
            min: 0,
            title: {
              text: null
            },
            labels: {
              formatter: function() {
                return Math.round(this.value);
              }
            },
          },
          //Whether to use HighStocks instead of HighCharts (optional). Defaults to false.
          useHighStocks: false,
          //size (optional) if left out the chart will default to size of the div or something sensible.
          size: {
            height: 400
          },
          //function (optional)
          func: function(chart) {
            //setup some logic for the chart
          }
        }
      };
    }
  ]);
});