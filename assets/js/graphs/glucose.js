'use strict';
(function(){
	var glucoseColor = function(data){
		var color = 0;
		if(data.glucose < 180 && data.glucose > 80){
			var percentage = (data.glucose - 80) / 100;
			color = 240 - parseInt(240 * percentage);
		}
		else if(data.glucose <= 80) color = 240;
		return 'hsl(' + color + ',83%,42%)';
	};
	var drawPlot = function(error, data){
		var main = document.querySelector('main');
		var containerSize = main.offsetWidth;
		var margin = {top:20, bottom:20, right:10, left:40};
		var width = containerSize - margin.left - margin.right,
			height = containerSize - margin.top - margin.bottom;

		var svg = d3.select(main).append('svg')
			.attr('height', containerSize).attr('width', containerSize)
			.append('g')
			.attr(
				'transform',
				'translate(' + margin.left + ',' + margin.top + ')'
			);
		var x = d3.scale.linear().range([0, width]).domain([0,24]),
			y = d3.scale.linear().range([height, 0]);
		y.domain(d3.extent(data,function(d){return parseInt(d.glucose);}))
			.nice();

		var xAxis = d3.svg.axis().scale(x).orient('bottom'),
			yAxis = d3.svg.axis().scale(y).orient('left');

		svg.append('g')
			.attr('transform','translate(0,' + height + ')')
			.call(xAxis)
			.append('text')
				.attr('x',width)
				.attr('dy','-0.42em')
				.text('hour');
		svg.append('g')
			.call(yAxis)
			.append('text')
				.attr('transform','rotate(-90)')
				.attr('dy','1.2em')
				.text('mg/dL');

		svg.selectAll('circle').data(data).enter().append('circle')
			.attr('r',5)
			.attr('cx',function(d){return x(d.hour);})
			.attr('cy',function(d){return y(d.glucose);})
			.style('fill',glucoseColor);
	};
	var loadPlot = function(){
		window.removeEventListener('load',loadPlot);
		d3.tsv(CI.base + 'graphs/glucose_data', drawPlot);
	};
	window.addEventListener('load',loadPlot);
})();
