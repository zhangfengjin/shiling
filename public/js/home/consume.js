ConsumeUtil = function (me) {
    return me = {
        _initOwn: function () {
        },
        init: function (requestData) {
            me.createConsume(requestData);
        },
        createConsume: function (requestData) {
            var reportUrl = "/bord/consume/detail?dspId=" + $("#dsp_id").val();
            CommonUtil.requestService(reportUrl, requestData, true, "get",
                function (response) {
                    if (response.code == 200) {
                        var data = response.data.detail,
                            xAxisData = response.data.xAxisData,
                            series = [],
                            legends = {
                                selected: {},
                                data: []
                            };
                        for (var idx in data) {
                            var select = false;
                            if (idx == "消耗") {
                                select = true;
                            }
                            var selected = legends.selected;
                            selected[idx] = select;
                            legends.selected = selected;
                            var legend = legends.data;
                            legend.push(idx);
                            legends.data = legend;
                            var seriesData = [];
                            for (var idj in data[idx]) {
                                seriesData.push(data[idx][idj]);
                            }
                            series.push({
                                name: idx,
                                type: 'line',
                                data: seriesData
                            });
                        }
                        var domConsume = document.getElementById('consume');
                        domConsume.style.height = 400 + "px";
                        var ech = echarts.init(domConsume);
                        var option = {
                            tooltip: {
                                trigger: 'axis'
                            },
                            legend: legends,
                            toolbox: { //工具栏
                                show: true,
                                feature: {
                                    magicType: {show: true, type: ['line', 'bar']},
                                    saveAsImage: {show: false}
                                }
                            },
                            calculable: false,
                            grid: {
                                x: 70
                            },
                            xAxis: [
                                {
                                    type: 'category',
                                    boundaryGap: false,
                                    data: xAxisData
                                }
                            ],
                            yAxis: [
                                {
                                    type: 'value',
                                    axisLabel: {
                                        formatter: '{value}'
                                    }
                                }
                            ],
                            series: series
                        };
                        // 为echarts对象加载数据
                        ech.setOption(option);
                    }
                });
        }
    };
}();