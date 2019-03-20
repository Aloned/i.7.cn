layui.define(['element', 'layer', 'form','base'], function(exports) {
	var layer = layui.layer,
		element = layui.element,
		form = layui.form,
		base = layui.base,
		$ = layui.jquery;
	
	$(function() {
		//pv数据
		var pvdata = $('#pvdata').data('visit');
		
		//时间
		var visittime = $('#visittime').data('time');
		
		//折线图
		var visitSummary = echarts.init(document.getElementById('visitSummary'));
		visitoptions = {
			tooltip: {
				trigger: 'axis'
			},
			legend: {
				data: ['报名人数']
			},
			toolbox: {
				show: true,
				right: '2%',
				feature: {
					magicType: {
						type: ['line', 'bar']
					}
				}
			},
			grid: {
				//show:true,
				left: '1%',
				right: '4%',
				bottom: '3%',
				containLabel: true,
				backgroundColor: 'rgba(238,238,238,0.4)'
			},
			xAxis: {
				type: 'category',
				//设置时间旋转角度
				/*axisLabel:{
					rotate:30,
					interval:0
				},*/
				boundaryGap: false,
				splitLine: {
					show: true,
					lineStyle: {
						color: '#f3f3f3'
					}
				},
				data:visittime
			},
			yAxis: {
				type: 'value',
				splitLine: {
					show: true,
					lineStyle: {
						color: '#f3f3f3'
					}
				}
			},
			series: [{
					name: '报名人数',
					type: 'line',
					//stack: '总量',
					symbolSize: 6,
					itemStyle: {
						normal: {
							color: '#cc0033',
							//label : {show: true}
						}
					},
					lineStyle: {
						normal: {
							color: '#cc0033'
						}
					},
					data: pvdata,
					markPoint: {
						data: [{
								type: 'max',
								name: '最大值'
							},
							{
								type: 'min',
								name: '最小值'
							}
						]
					}
				}
			]
		};
		visitSummary.setOption(visitoptions);
	});

	exports('main', {}); //注意，这里是模块输出的核心，模块名必须和use时的模块名一致
});
