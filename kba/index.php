<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
	<meta charset="UTF-8">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="format-detection" content="telephone=no, email=no" />
	<meta name="HandheldFriendly" content="true">
	<meta name="MobileOptimized" content="320">
	<meta name="screen-orientation" content="portrait">
	<meta name="x5-orientation" content="portrait">
	<meta name="full-screen" content="yes">
	<meta name="x5-fullscreen" content="true">
	<meta name="x5-fullscreen" content="true">
	<meta name="browsermode" content="application">
	<meta name="x5-page-mode" content="app">
	<meta name="msapplication-tap-highlight" content="no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<meta name="renderer" content="webkit" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link rel="shortcut icon" type="image/ico" href="./resource/favicon.ico">
	<title>KBA考拉大联盟-琅琊榜</title>
	<link rel="stylesheet" href="./resource/js/daterangepicker.css">
	<link rel="stylesheet" href="./resource/css/style.css?v=21">
	<link rel="stylesheet" href="./resource/css/ranking.css?v=21">
	<link rel="stylesheet" href="./resource/css/common.css?v=21">
	<link rel="stylesheet" media="screen and (max-width:800px)" href="./resource/css/mobile-common.css?v=21">
	<style type="text/css">
		.bar-chart-right li, .partner .name, .employee .name, .partner-champion .name, .employee-champion .name {cursor:pointer;}
		#datePickerPanel {width:auto;}
	</style>
</head>
<body class="mobile" data-auto_refresh="yes">
    <img id="js-mobile-nav" class="ico-mobile-nav" src="./resource/img/ico-mobile-nav-3.png">
    <ul id="js-nav-list" class="mb-nav-list">
        <li class="active">
            <a>联盟热度榜</a>
        </li>
        <li>
            <a href="index.php?controller=team&action=all_partners">联盟战队</a>
        </li>
		<li>
            <a href="index.php?controller=heat&action=technical_analysis">技术统计</a>
        </li>
		<li>
            <a href="index.php?controller=index&action=records">联盟记录</a>
        </li>
		<li>
			<a href="index.php?controller=index&action=stores">商家分布</a>
		</li>
        <li>
            <a href="index.php?controller=index&action=about">关于联盟</a>
        </li>
    </ul>
    <div id="js-muck" class="muck"></div>
	<div class="showBoom">
        <div id="canvas-container" class="fireworks"></div>
        <div class="boom-title center">  
            <h3 title=""></h3>  
            <h4 class="center"></h4>
            <canvas class="center waves" id="waves"></canvas>
            <div class="boom-bg center">
                <img class="img1" src="./resource/img/boom-bg.png" alt="">
                <img class="img2" src="./resource/img/boom-shadow.png" alt="">
                <img class="boom-star boom-star1" src="./resource/img/boom-star.png" alt="">
                <img class="boom-star boom-star2" src="./resource/img/boom-star.png" alt="">
                <img class="boom-star boom-star3" src="./resource/img/boom-star.png" alt="">
                <img class="boom-star boom-star4" src="./resource/img/boom-star.png" alt="">
                <img class="boom-star boom-star5" src="./resource/img/boom-star.png" alt="">
                <img class="boom-star boom-star6" src="./resource/img/boom-star.png" alt="">
            </div>
            <div class="boom-text-bg center"></div>
        </div> 
        <div id="js-close-showBoom" class="close-showBoom"></div>
    </div>
	<div style="display:none;"><img src="./resource/img/wx_logo.png" /></div>
	<div class="warpper section-1">
		<div class="side-bar scroll-1 w85">
			<img class="kba-logo" src="./resource/img/kba-logo.png" alt="logo">
			<ul class="nav">
				<li class="active">
                    <a>联盟热度榜</a>
                </li>
                <li>
                    <a href="index.php?controller=team&action=all_partners">联盟战队</a>
                </li>
				<li>
					<a href="index.php?controller=heat&action=technical_analysis">技术统计</a>
				</li>
				<li>
					<a href="index.php?controller=index&action=records">联盟记录</a>
				</li>
				<li>
					<a href="index.php?controller=index&action=stores">商家分布</a>
				</li>
                <li>
                    <a href="index.php?controller=index&action=about">关于联盟</a>
                </li>
            </ul>
		</div>
		<div class="main">
			<h1 class="m-title">
				<span style="position:relative;">
					<span id="date-range0" class="s-title prev-title ico-dropdown" style="position:relative;">
						<span id="js-date-range" style="position:absolute; width:100%; height:100%;"></span>
						<span class="js-date-range">今日</span>
					</span>
					<div id="j-dropdown0" class="j-dropdown">
                        <ul>
                            <li id="js-today" shortcut="day,0">今天</li>
							<li id="js-prev-7" shortcut="day,-7">过去7天</li>
                            <li id="js-prev-30" shortcut="day,-30">过去30天</li>
                            <li id="js-date-select">自选</li>
                        </ul>
                    </div>
				</span>
                <span style="position:relative;">
                    <span id="js-title1" class="s-title ico-dropdown prev-title">全国</span>
                    <div id="j-dropdown1" class="j-dropdown">
                        <ul>
                            <li data-zone_id="0">全国</li>
							                            <li data-zone_id="1">华东地区</li>
							                            <li data-zone_id="2">华南地区</li>
							                            <li data-zone_id="4">华北地区</li>
							                            <li data-zone_id="6">西南地区</li>
							                        </ul>
                    </div>
                </span>  
			实时排行榜</h1>
            <h2 class="m-sub-title">loading...</h2><i class="m-sub-title-pre"></i>
            <div class='marquee'></div>
			<div class="chart" id="main"></div>
			<div class="m-bottom"><span class="s">注：</span>总热度 = 新粉丝订单*1°C + 新粉丝有记账订单*2°C + 老粉丝复购订单*3°C (其中新店铺前三十单的热度*2)</div>
		</div>
		<div class="side-bar scroll-1 w85">
			<div class="side_top">
				<div class="s-title"><span class="icon-dot"></span><i class="s-title js-s-title">全国</i>战队排行榜
                </div>
				<div class="s-chart-bar">
					<span class="ico-x-ax"></span>
					<span class="x-ax">热度</span>
					<div id="side_chart_1" class="side_chart"></div>
                    <ul class="bar-chart-right partner">
						<li>loading...</li>
						<li>loading...</li>
						<li>loading...</li>
						<li>loading...</li>
						<li>loading...</li>
                    </ul>
					<span class="ico-y-ax"></span>
					<span class="y-ax">名次</span>
				</div>
				<!--<p id="js-btn-more" class="btn-more"><span id="js-more">查看全部</span><span id="js-ico-more" class="ico-more"></span></p>-->
				<p class="btn-more"><a href="index.php?controller=heat&action=partners_ranking" style="color:#666;">查看全部</a></p>
				<table id="js-lists" class="lists lists-s lists-s-right hide partner">
					<!--<tr><td colspan="4">loading...</td></tr>-->
				</table>
			</div>
			<div class="side_top">
				<div class="s-title"><span class="icon-dot"></span><i class="s-title js-s-title">全国</i>战神排行榜
                </div>
				<div class="s-chart-bar">
					<span class="ico-x-ax"></span>
					<span class="x-ax">热度</span>
					<div id="side_chart_2" class="side_chart"></div>
                    <ul class="bar-chart-right employee">
                        <li>loading...</li>
						<li>loading...</li>
						<li>loading...</li>
						<li>loading...</li>
						<li>loading...</li>
                    </ul>
					<span class="ico-y-ax"></span>
					<span class="y-ax">名次</span>
				</div>
				<!--<p id="js-btn-more2" class="btn-more"><span id="js-more">查看全部</span><span id="js-ico-more" class="ico-more"></span></p>-->
				<p class="btn-more"><a href="index.php?controller=heat&action=employees_ranking" style="color:#666;">查看全部</a></p>
				<table id="js-lists" class="lists lists-s lists-s-right hide employee">
                    <!--<tr><td colspan="4">loading...</td></tr>-->
                </table>
			</div>
		</div>
	</div>	
    <div id="muck" class="muck"></div>
	<!--微信分享接口start-->
	<div class="wxapi_container" style="display:none;">
		<div class="lbox_close wxapi_form">
			<h3 id="menu-basic">基础接口</h3>
			<span class="desc">判断当前客户端是否支持指定JS接口</span>
			<button class="btn btn_primary" id="checkJsApi">checkJsApi</button>

			<h3 id="menu-share">分享接口</h3>
			<span class="desc">获取“分享到朋友圈”按钮点击状态及自定义分享内容接口</span>
			<button class="btn btn_primary" id="onMenuShareTimeline">onMenuShareTimeline</button>
			<span class="desc">获取“分享给朋友”按钮点击状态及自定义分享内容接口</span>
			<button class="btn btn_primary" id="onMenuShareAppMessage">onMenuShareAppMessage</button>
		</div>
	</div>
	<script src="./resource/bower_components/jquery/dist/jquery.min.js"></script>
	<script src="./resource/bower_components/echarts/dist/echarts.min.js"></script>
	<script src="./resource/bower_components/echarts/map/js/china.js"></script>
	<script src="./resource/bower_components/highcharts/highcharts.js"></script>
    <script src="./resource/js/jquery.marquee.min.js"></script>
    <script src="./resource/js/common.js?v=21"></script>
    <script src="./resource/js/fireworks.js?v=21"></script>
    <script src="./resource/js/waves.js"></script>
	<script type="text/javascript">
		var marOption = {
             //speed in milliseconds of the marquee
            duration: 8000,
            //gap in pixels between the tickers
            gap: 50,
            //time in milliseconds before the marquee will start animating
            delayBeforeStart: 0,
            //'left' or 'right'
            direction: 'left',
            //true or false - should the marquee be duplicated to show an effect of continues flow
            // duplicated: true
        };

        var $mq = $('.marquee');
        $mq.marquee(marOption);
		
        function changeWord(word){
             $mq.marquee('destroy').text(word).marquee(marOption);
        }

		// 基于准备好的dom，初始化echarts实例
         var myChart = echarts.init(document.getElementById('main'));
		
        // 指定图表的配置项和数据
        var data = [
             {name: '上海', value: 4344},
             {name: '广州', value: 4122},
             {name: '深圳', value: 3841},
             {name: '北京', value: 3456}
        ];
        var geoCoordMap = {
            
            '上海':[121.48,31.22],
            '广州':[113.327,23.168],
            '深圳':[115.07,22.12],
            '北京':[116.46,39.92]
        };

        var convertData = function (data) {
            var res = [];
            for (var i = 0; i < data.length; i++) {
                var geoCoord = geoCoordMap[data[i].name];
                if (geoCoord) {
                    res.push({
                        name: data[i].name,
                        value: geoCoord.concat(data[i].value)
                    });
                }
            }
            return res;
        };

        option = {
            title: {
                left: 'center',
                textStyle: {
                    color: '#fff'
                }
            },
            tooltip : {
                trigger: 'item',
                backgroundColor: 'rgba(85,180,0,.5)',
                borderRadius: 6,
                formatter: function (params) {
                    // return params.name + ' : 当面付订单 x ' + params.value[2];
                },
                hideDelay : 5000,
                position: 'top',
                extraCssText: '',
            },
            geo: {
                map: 'china',
                label: {
                    // normal: {
                    //     show: true,
                    //     textStyle: {
                    //         color: '#fff'
                    //     }
                    // },
                    emphasis: {
                        show: false
                    }
                },
                roam: false,
                itemStyle: {
                    normal: {
                        areaColor: '#323c48',
                        borderColor: '#111'
                    },
                    emphasis: {
                        areaColor: '#2a333d'
                    }
                }
            },
            series : [
            {
                name: 'all',
                type: 'scatter',
                coordinateSystem: 'geo',
                data: convertData(data),
                symbolSize: function (val) {
                    return val[2] / 200;
                },
                label: {
                    normal: {
                        formatter: '{b}',
                        position: 'right',
                        show: false
                    },
                    emphasis: {
                        show: true
                    }
                },
                itemStyle: {
                    normal: {
                        color: '#F15B5C'
                    }
                }
            },
                {
                    name: 'Top 5',
                    type: 'effectScatter',
                    coordinateSystem: 'geo',
                    data: [
						//这里是城市数据
                    ],
                    symbolSize: function (val) {
                        return val[2] / 100;
                    },
                    showEffectOn: 'render',
                    rippleEffect: {
                        brushType: 'stroke'
                    },
                    hoverAnimation: true,
                    label: {
                        normal: {
                            formatter: function(p){
                                return '';
                            },
                            textStyle: {
                                color: '#fff'
                            },
                            position: 'top',
                            show: true,
                        }
                    },
                    itemStyle: {
                        normal: {
                            color: '#f35a58',
                            shadowBlur: 10,
                            shadowColor: '#333'
                        }
                    },
                    zlevel: 1
                }
            ]
        };

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);

		var _side_chart_1 = $('#side_chart_1');
		
        var option_side1 = {
            chart: {
                type: 'bar',
                margin: [1,20,0,20], //边距是指图表的外边与图形区域之间的距离
                backgroundColor: 'rgba(0,0,0,0)',
				renderTo: _side_chart_1[0]
            },
            title: {
                text: null
            },
            credits: {
                enabled: false
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                enabled: true
            },
            xAxis: {
                categories: [1, 2, 3, 4, 5],
                lineWidth: 1, //轴线的宽度
                tickLength: 0, //刻度线的长度
                lineColor: '#666666',
                labels: {
                    x: -8,
                    style: {
                        color: '#666666',
                        fontSize: '12px'
                    },
                },
            },
            yAxis: {
                min: 0,
                max: 30000,
                title: {
                    text: '',
                    align: 'high',
                    offset: 10,
                    style: {
                        color: '#666666',
                        fontSize: '12px',
                        fontFamily:'Microsoft Yahei',
                        fontWeight: 'normal',
                        lineHeight: '12px',
                    }
                },
                gridLineWidth: 0,
                lineWidth: 1, //轴线的宽度
                gridLineColor: '#D9D9D9', //网格线的颜色
                gridLineDashStyle: 'longdash', //网格线的线条风格样式
                lineColor: '#666666',
                labels: {
                    enabled: false,
                },
                opposite: true

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                bar: {  
                    grouping: false,
                    borderRadius: 5,
                    color: '#F35A58',
                    dataLabels: {
                    	align: 'right',
                        enabled: true,
                        color: '#fff',
                        connectorWidth: 0,
                        padding: 4,
                        style: {
                            fontWeight: 'normal',
                            textShadow: '0 0',
                            fontSize: '12px',
                            lineHeight: '12px',
                            fontFamily: 'Microsoft Yahei'
                        },
                        formatter: function () {
	                        //return this.y + '°C';
	                    }
                    },
                    lineColor: '#F97C60',
                    enableMouseTracking: false,
                    //showInLegend: false,
                    borderWidth: 0,
                    marker: {
                        fillColor: '#F97C60'
                    },
                    pointWidth: 6,
                    cursor: 'pointer',
                    events: {
                        click: function (e) {
                            
                        }
                    }
                }
            },
            series: [
                    {
                        color: '#666666',
                        name: 'background filler',
                        data: [30000,30000,30000,30000,30000],
                        animation: false,
                        dataLabels: {
                            enabled: false
                        }                
                    },
                    {
                        name: '',
                        data: [0,0,0,0,0],
                    },
            ],
            labels:{
                style:{
                    fontFamily:'Microsoft Yahei',
                    fontSize:'12px',
                    fontWeight:'normal',
                    color: '#999',
                },
                items:[
                    {
                    	html: '',
                        style: { 
                            left: '10px',
                            top: '35px',
                        }
                    },
                    {
                        html: '',
                        style: { 
                            left: '10px',
                            top: '83px',
                        }
                    },
                    {
                        html: '',
                        style: { 
                            left: '10px',
                            top: '130px',
                        }
                    },
                    {
                        html: '',
                        style: { 
                            left: '10px',
                            top: '178px',
                        }
                    },
                    {
                        html: '',
                        style: { 
                            left: '10px',
                            top: '225px',
                        }
                    }
                ]
            },
        }
      	
		//$('#side_chart_1').highcharts(option_side1);
		var _zonemates_rank = new Highcharts.Chart(option_side1);

		var _side_chart_2 = $('#side_chart_2');
		
        var option_side2 = {
            chart: {
                type: 'bar',
                margin: [1,20,0,20], //边距是指图表的外边与图形区域之间的距离
                backgroundColor: 'rgba(0,0,0,0)',
				renderTo: _side_chart_2[0]
            },
            title: {
                text: null
            },
            credits: {
                enabled: false
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>',
                enabled: true
            },
            xAxis: {
                categories: [1, 2, 3, 4, 5],
                lineWidth: 1, //轴线的宽度
                tickLength: 0, //刻度线的长度
                lineColor: '#666666',
                labels: {
                    x: -8,
                    style: {
                        color: '#666666',
                        fontSize: '12px'
                    },
                },
            },
            yAxis: {
                min: 0,
                max: 3000,
                title: {
                    text: '',
                    align: 'high',
                    offset: 10,
                    style: {
                        color: '#666666',
                        fontSize: '12px',
                        fontFamily:'Microsoft Yahei',
                        fontWeight: 'normal',
                        lineHeight: '12px',
                    }
                },
                gridLineWidth: 0,
                lineWidth: 1, //轴线的宽度
                gridLineColor: '#D9D9D9', //网格线的颜色
                gridLineDashStyle: 'longdash', //网格线的线条风格样式
                lineColor: '#666666',
                labels: {
                    enabled: false,
                },
                opposite: true

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                bar: {  
                    grouping: false,
                    borderRadius: 5,
                    color: '#F35A58',
                    dataLabels: {
                        align: 'right',
                        enabled: true,
                        color: '#fff',
                        connectorWidth: 0,
                        padding: 4,
                        style: {
                            fontWeight: 'normal',
                            textShadow: '0 0',
                            fontSize: '12px',
                            lineHeight: '12px',
                            fontFamily:'Microsoft Yahei'
                        },
                        formatter: function () {
                            // return this.y + '°C';
                        }
                    },
                    lineColor: '#F97C60',
                    enableMouseTracking: false,
                    showInLegend: false,
                    borderWidth: 0,
                    marker: {
                        fillColor: '#F97C60'
                    },
                    pointWidth: 6,
                    cursor: 'pointer',
                    events: {
                        click: function (event) {
                             
                        }
                    }

                }
            },
            series: [
                    {
                        color: '#666666',
                        name: 'background filler',
                        data: [3000,3000,3000,3000,3000],
                        animation: false,
                        dataLabels: {
                             enabled: false
                        }                
                    },
                    {
                        name: '',
                        data: [0,0,0,0,0]
                    }
            ],
            labels:{
                style:{
                    fontFamily:'Microsoft Yahei',
                    fontSize:'12px',
                    fontWeight:'normal',
                    color: '#999',
                },
                items:[
                    {
                        html: '',
                        style: { 
                            left: '10px',
                            top: '35px',
                        }
                    },
                    {
                        html: '',
                        style: { 
                            left: '10px',
                            top: '83px',
                        }
                    },
                    {
                        html: '',
                        style: { 
                            left: '10px',
                            top: '130px',
                        }
                    },
                    {
                        html: '',
                        style: { 
                            left: '10px',
                            top: '178px',
                        }
                    },
                    {
                        html: '',
                        style: { 
                            left: '10px',
                            top: '225px',
                        }
                    }
                ]
            },
        }

		//$('#side_chart_2').highcharts(option_side2);
		var _teammates_rank = new Highcharts.Chart(option_side2);
	</script>
	<!-- 日期 -->
	<script src="./resource/js/moment.min.js"></script>
	<script src="./resource/js/jquery.daterangepicker.js"></script>
    <script type="text/javascript">		
		$('#date-range0').dateRangePicker(
		{
			startDate: '2016-1-1',
			endDate: moment(),
			startOfWeek: 'monday',
			showShortcuts: false,
			showTopbar: true,
		})
		.bind('datepicker-first-date-selected', function(event, obj)
		{
			/* This event will be triggered when first date is selected */
			//console.log('first-date-selected',obj);
			// obj will be something like this:
			// {
			// 		date1: (Date object of the earlier date)
			// }
		})
		.bind('datepicker-change',function(event, obj)
		{
			/* This event will be triggered when second date is selected */
			//console.log('change',obj);
			// obj will be something like this:
			// {
			// 		date1: (Date object of the earlier date),
			// 		date2: (Date object of the later date),
			//	 	value: "2013-06-05 to 2013-06-07"
			// }
		})
		.bind('datepicker-apply',function(event, obj)
		{
			/* This event will be triggered when user clicks on the apply button */
			var _args_str = '';
			QueryString['starttime'] = moment(obj.date1).format('YYYY-MM-DD');
			QueryString['endtime'] = moment(obj.date2).format('YYYY-MM-DD');
			for (var _key in QueryString) {
				_args_str += _key + '=' + QueryString[_key] + '&';
			}
			_args_str = _args_str.substring(0, _args_str.length - 1);
			window.location.href = 'index.php?' + _args_str;
			//console.log('apply',obj);
		})
		.bind('datepicker-close',function()
		{
			/* This event will be triggered before date range picker close animation */
			//console.log('before close');
		})
		.bind('datepicker-closed',function()
		{
			/* This event will be triggered after date range picker close animation */
			//console.log('after close');
			$('#js-date-range').click(function(e){
	 			e.stopPropagation();
	 			showDownList($('#j-dropdown0'));
	 		});
		})
		.bind('datepicker-open',function()
		{
			/* This event will be triggered before date range picker open animation */
			//console.log('before open');
			$('#js-date-range').unbind();
		})
		.bind('datepicker-opened',function()
		{
			/* This event will be triggered after date range picker open animation */
			//console.log('after open');
		});
		
		function showDownList(downlist){
    		downlist.show();
    	}

		$('#js-date-range').click(function(e){
 			e.stopPropagation();
 			showDownList($('#j-dropdown0'));
 		});

 		$("#js-date-select").click(function(e){
 			e.stopPropagation();
 			$('#j-dropdown0').hide();
 			$('#date-range0').data('dateRangePicker').open();
 		});
		
    	//下拉切换 右上
 		$('#js-title1').click(function(e){
 			e.stopPropagation();
 			showDownList($('#j-dropdown1'));
 		});

 		$('#j-dropdown1 li').click(function(e){
 			e.stopPropagation();
 			$('#js-title1').text($(this).text());
 			$('#j-dropdown1').hide();
            $('.js-s-title').text($(this).text());
			
			var _zone_id = $(this).data('zone_id');
			
			var _args_str = '';
			QueryString['zone_id'] = _zone_id;
			for (var _key in QueryString) {
				_args_str += _key + '=' + QueryString[_key] + '&';
			}
			_args_str = _args_str.substring(0, _args_str.length - 1);
			window.location.href = 'index.php?' + _args_str;
 		});

 		$(document).click(function(e){
 			e.stopPropagation();
 			$('.j-dropdown').hide();
 		});

 		//查看全部
 		/*var clickFlag = true,
	    	ico_more = $('#js-ico-more'),
	    	lists = $('#js-lists')

    	$('#js-btn-more').click(showMore);
    	$('#js-btn-more2').click(showMore);
    	function showMore() {
			if ($(this).next('.lists').css('display') == 'none') {
    			$(this).children('#js-more').text('收起全部');
    			$(this).children('.ico-more').addClass('down');
        		$(this).next('.lists').fadeIn();
				clickFlag = false;
			} else {
				$(this).children('#js-more').text('查看全部');
    			$(this).children('.ico-more').removeClass('down');
        		$(this).next('.lists').fadeOut();
				clickFlag = true;
			}
    	}*/

        initCount = 3;
        $('.grid-btn-more').click(function(){
            var lists_more = $(this).siblings('.lists-more'),
                $btn_more = $(this).children('span:first-child'),
                $ico_down = $(this).children('span:last-child');
            if(lists_more.css('display') == 'none'){
                lists_more.show();
                $btn_more.text('收起全部');
                $ico_down.addClass('down');
            }else{
                lists_more.hide();
                $btn_more.text('查看全部');
                $ico_down.removeClass('down');
            }
        });
		
		var request_time = 0;
        $(document).ready(function() {
			reloadMapData();
			load_total_heat_sum();
			load_zonemates();
			load_teammates();
			get_notice();
			
			setInterval(function() {
				reloadMapData();
			}, 5000);
			
			if ($('body').data('auto_refresh') === 'yes') {
				setInterval(function() {
					load_total_heat_sum();
					load_zonemates();
					load_teammates();
				}, 90000);
			}
			
			if (QueryString['starttime'] != undefined || QueryString['endtime'] != undefined) {
				$('.js-date-range').html(QueryString['starttime'] + ' - ' + QueryString['endtime']).css({'font-size':'14px'});
			}
			
			if (QueryString['zone_id'] != undefined) {	
				$('#js-title1').text($('#j-dropdown1 li[data-zone_id=' + QueryString['zone_id'] + ']').text());
				$('.js-s-title').text($('#j-dropdown1 li[data-zone_id=' + QueryString['zone_id'] + ']').text());
			}
        });
		
		function load_total_heat_sum() {
			var _starttime = QueryString['starttime'],
				_endtime = QueryString['endtime'],
				_zone_id = QueryString['zone_id'],
				_refresh_cache = QueryString['refresh_cache'];
			
			var _arguments = {starttime:_starttime, endtime:_endtime, zone_id:_zone_id};
			if (_refresh_cache === 'yes') {
				_arguments['refresh_cache'] = _refresh_cache;
			}
			
			$.ajax({
				'url': 'index.php?controller=heat&action=get_total_heat',
				'data': _arguments,
				'type': 'GET',
				'dataType': 'json',
				'success': function(_json) {
					if (_json.status === 1) {
						$('.m-sub-title').addClass(_json.up_or_down).text('共' + _json.heat_sum + '°C');
						if (_json.ratio !== '') {
							$('.m-sub-title-pre').text(_json.ratio + '%');
						}
					}
				},
				'error': function() {
					console.log('Network Error');
				}
			});
		}
		
		function load_zonemates() {
			var _starttime = QueryString['starttime'],
				_endtime = QueryString['endtime'],
				_zone_id = QueryString['zone_id'],
				_limit = 5,
				_refresh_cache = QueryString['refresh_cache'];
			
			var _arguments = {starttime:_starttime, endtime:_endtime, zone_id:_zone_id, limit:_limit};
			if (_refresh_cache === 'yes') {
				_arguments['refresh_cache'] = _refresh_cache;
			}
			
			$.ajax({
				'url': 'index.php?controller=heat&action=zonemates',
				'data': _arguments,
				'type': 'GET',
				'dataType': 'json',
				'success': function(_json) {
					if (_json.status === 1) {
						var _rank_len = _json.partner_rank.length;
						var _top_five = (_rank_len >= 5 ? 5 : _rank_len);
						var _top_five_html = _top_left_html = '';
						for (var _i = 0; _i < _top_five; _i ++) {
							_top_five_html += 	'<li class="' + _json.partner_rank[_i]['up_or_down'] + '" data-partner_id="' + _json.partner_rank[_i]['partner_id'] + '"><i>' + _json.partner_rank[_i]['total_heat'] + ' °C</i> <span>' + _json.partner_rank[_i]['ratio'] + '<span></li>';
						}
                        $('.bar-chart-right.partner').empty().append(_top_five_html);
						
						/*for (_i; _i < _rank_len; _i ++) {
							_top_left_html += 	'<tr>' +
													'<td class="no">' + (_i + 1) + '</td>' +
													'<td class="name" data-partner_id="' + _json.partner_rank[_i]['partner_id'] + '">' + _json.partner_rank[_i]['short_name'] + '</td>' +
													'<td class="number ' + _json.partner_rank[_i]['up_or_down'] + '">' + _json.partner_rank[_i]['total_heat'] + ' °C<i></i></td>' +
													'<td class="pre">' + _json.partner_rank[_i]['ratio'] + '</td>' +
												'</tr>';
						}
						$('.hide.partner').empty().append(_top_left_html);*/
						
						_zonemates_rank.series[0].setData(_json.partner_top_five.max_partner_heat_arr);
						_zonemates_rank.series[1].setData(_json.partner_top_five.partner_heat_rank_arr);
						
						_zonemates_rank.redraw();
						
						$('#side_chart_1').find('text').eq(0).text(_json.partner_top_five.partner_store_name_arr[0]);
						$('#side_chart_1').find('text').eq(1).text(_json.partner_top_five.partner_store_name_arr[1]);
						$('#side_chart_1').find('text').eq(2).text(_json.partner_top_five.partner_store_name_arr[2]);
						$('#side_chart_1').find('text').eq(3).text(_json.partner_top_five.partner_store_name_arr[3]);
						$('#side_chart_1').find('text').eq(4).text(_json.partner_top_five.partner_store_name_arr[4]);
					}
				},
				'error': function() {
					console.log('Network Error');
				}
			});
		}
		
		function load_teammates() {
			var _starttime = QueryString['starttime'],
				_endtime = QueryString['endtime'],
				_zone_id = QueryString['zone_id'],
				_limit = 5,
				_refresh_cache = QueryString['refresh_cache'];
			
			var _arguments = {starttime:_starttime, endtime:_endtime, zone_id:_zone_id, limit:_limit};
			if (_refresh_cache === 'yes') {
				_arguments['refresh_cache'] = _refresh_cache;
			}
			
			$.ajax({
				'url': 'index.php?controller=heat&action=teammates',
				'data': _arguments,
				'type': 'GET',
				'dataType': 'json',
				'success': function(_json) {
					if (_json.status === 1) {
						var _rank_len = _json.employee_rank.length;
						var _top_five = (_rank_len >= 5 ? 5 : _rank_len);
						var _top_five_html = _top_left_html = '';
						for (var _i = 0; _i < _top_five; _i ++) {
							_top_five_html += 	'<li class="' + _json.employee_rank[_i]['up_or_down'] + '" data-partner_id="' + _json.employee_rank[_i]['partner_id'] + '" data-employee_sn="' + _json.employee_rank[_i]['employee_sn'] + '"><i>' + _json.employee_rank[_i]['total_heat'] + ' °C</i> <span>' + _json.employee_rank[_i]['ratio'] + '</span></li>';
						}
                        $('.bar-chart-right.employee').empty().append(_top_five_html);
						
						/*for (_i; _i < _rank_len; _i ++) {
							_top_left_html += 	'<tr>' +
													'<td class="no">' + (_i + 1) + '</td>' +
													'<td class="name" data-partner_id="' + _json.employee_rank[_i]['partner_id'] + '" data-employee_sn="' + _json.employee_rank[_i]['employee_sn'] + '">' + _json.employee_rank[_i]['short_name'] + ' ' + _json.employee_rank[_i]['employee_sn'] + '&#12288;' + _json.employee_rank[_i]['star_num'] + '星</td>' +
													'<td class="number ' + _json.employee_rank[_i]['up_or_down'] + '">' + _json.employee_rank[_i]['total_heat'] + ' °C<i></i></td>' +
													'<td class="pre">' + _json.employee_rank[_i]['ratio'] + '</td>' +
												'</tr>';
						}
						$('.hide.employee').empty().append(_top_left_html);*/
						
						_teammates_rank.series[0].setData(_json.employee_top_five.max_employee_heat_arr);
						_teammates_rank.series[1].setData(_json.employee_top_five.employee_heat_rank_arr);
						
						_teammates_rank.redraw();
						
						$('#side_chart_2').find('text').eq(0).text(_json.employee_top_five.employee_store_name_arr[0]);
						$('#side_chart_2').find('text').eq(1).text(_json.employee_top_five.employee_store_name_arr[1]);
						$('#side_chart_2').find('text').eq(2).text(_json.employee_top_five.employee_store_name_arr[2]);
						$('#side_chart_2').find('text').eq(3).text(_json.employee_top_five.employee_store_name_arr[3]);
						$('#side_chart_2').find('text').eq(4).text(_json.employee_top_five.employee_store_name_arr[4]);
					}
				},
				'error': function() {
					console.log('Network Error');
				}
			});
		}
	   
        function reloadMapData(){
			option.series[1].data = [];
			myChart.setOption(option);
			$.getJSON('index.php?controller=heat&action=get_realtime_heat&request_time=' + request_time, function(_json) {
				//console.log(_json);
				if (_json.status === 1) {
					var _len = _json.increment.length;
					for (var i = 0; i < _len; i ++) {
						var citydata = {
										name: _json.increment[i].city_name, 
										value: [_json.increment[i].lng,_json.increment[i].lat, 2000],
										label: {
											normal: {
												formatter: '{b}' + ' + ' + _json.increment[i].total_heat + '°C',
												textStyle: {
													color: '#ddd'
												},
												position: 'top',
												show:true
											}
										}
									};
						option.series[1].data[i] = citydata;
					}
					myChart.setOption(option);
				}
			},'json');
		}
		
		var fworks = null;
		function disarmBomb() {
			$("#canvas-container").remove();
			$(".showBoom").removeClass("active");
            $(".showBoom").prepend('<div id="canvas-container" class="fireworks"></div>');

		    fworks.stop();	

			fworks = null;
		}
		function setoffBomb() {
			if (!$(".showBoom").hasClass("active")) {
				$(".showBoom").addClass("active");
			}
			fworks = new Fireworks();
			
			var maintxt = $(".boom-title h3").height()/2 + 'px';
			$(".boom-text-bg, #waves").css({"marginTop": maintxt})

		}
		
		$('#js-close-showBoom').click(disarmBomb);
		
		function get_notice() {
			$.ajax({
				'url': 'index.php?controller=index&action=get_kba_notice',
				'data': {},
				'type': 'GET',
				'dataType': 'json',
				'success': function(_json) {
					if (_json.status === 1) {
						var _notice = '';
						
						if (_json.notice.type == 2) {
							_notice = '[ ' + _json.notice.description + ' ] ' + _json.notice.notice;
						} else {
							_notice = _json.notice.notice;
						}
						
						$mq.bind("finished", changeWord(_notice));
						
						if (_json.notice.need_boom === 'yes') {
							$('.boom-title h3').attr('title', _json.notice.description);
							$('.boom-title h3').html(_json.notice.description);
							$('.boom-title h4').html(_json.notice.notice);
							
							setTimeout(setoffBomb, 2000);
							setTimeout(disarmBomb, 32000);
						}
						
						setTimeout(function() {
							get_notice();
						}, _json.notice.interval * 1000);
					}
					
					
				},
				'error': function() {
					console.log('Network Error');
				}
			});
		}
		
		$(document).on('click', '.partner li, .partner .name, .partner-champion .name', function(){
			window.location.href = 'index.php?controller=team&action=partner&partner_id=' + $(this).data('partner_id');
		});
		
		$(document).on('click', '.employee li, .employee .name, .employee-champion .name', function(){
			window.location.href = 'index.php?controller=team&action=employee&partner_id=' + $(this).data('partner_id') + '&employee_sn=' + $(this).attr('data-employee_sn');
		});
    </script>
	<!--引用网络的微信接口↓↓↓-->
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="./resource/js/wx_share.js?v=21"></script>
</body>
</html>
