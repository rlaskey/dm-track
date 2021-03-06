'use strict';
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

(function() {
	var storedData, mainWidth, timer;

	var glucoseColor = function(data) {
		var color = 0;
		if (data.glucose < 180 && data.glucose > 80) {
			var percentage = (data.glucose - 80) / 100;
			color = 240 - parseInt(240 * percentage);
		}
		else if (data.glucose <= 80) color = 240;
		return 'hsl(' + color + ',83%,42%)';
	};

	var drawPlot = function(error, data) {
		storedData = data;
		var main = document.querySelector('main');
		mainWidth = main.offsetWidth;
		var margin = {top:20, bottom:20, right:10, left:40};
		var width = mainWidth - margin.left - margin.right,
			height = mainWidth - margin.top - margin.bottom;

		var svg = d3.select(main).append('svg')
			.attr('height', mainWidth).attr('width', mainWidth)
			.append('g')
			.attr(
				'transform',
				'translate(' + margin.left + ',' + margin.top + ')'
			);
		var x = d3.scale.linear().range([0, width]).domain([0, 24]),
			y = d3.scale.linear().range([height, 0]);
		y.domain(d3.extent(data, function(d) {return parseInt(d.glucose);}))
			.nice();

		var xAxis = d3.svg.axis().scale(x).orient('bottom'),
			yAxis = d3.svg.axis().scale(y).orient('left');

		svg.append('g')
			.attr('transform', 'translate(0, ' + height + ')')
			.call(xAxis)
			.append('text')
				.attr('x', width)
				.attr('dy', '-0.42em')
				.text('hour');
		svg.append('g')
			.call(yAxis)
			.append('text')
				.attr('transform', 'rotate(-90)')
				.attr('dy', '1.2em')
				.text('mg/dL');

		svg.selectAll('circle').data(data).enter().append('circle')
			.attr('r', 5)
			.attr('cx', function(d) {return x(d.hour);})
			.attr('cy', function(d) {return y(d.glucose);})
			.style('fill', glucoseColor);
	};

	var loadPlot = function() {
		window.removeEventListener('load', loadPlot);
		d3.tsv('graphs/glucose_data', drawPlot);
	};
	window.addEventListener('load', loadPlot);

	var reloadPlot = function() {
		var main = document.querySelector('main');
		if (mainWidth === main.offsetWidth) return;

		var child = main.querySelector('svg');
		if (child) main.removeChild(child);

		clearTimeout(timer);
		timer = setTimeout(function() {drawPlot(null, storedData);}, 330);
	};
	window.addEventListener('resize', reloadPlot);
})();
