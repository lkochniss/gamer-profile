import $ from 'jquery';
import * as d3 from 'd3';

export default (id, data, xFormat) => {
  const margin = {
    top: 20,
    right: 20,
    bottom: 30,
    left: 50,
  };

  const width = $(id).width() - margin.left - margin.right;
  const height = $(id).height() - margin.top - margin.bottom;

  const x = d3.scaleTime().range([0, width]);
  const y = d3.scaleLinear().range([height, 0]);
  const parseTime = d3.timeParse(xFormat);

  const valueline = d3.line()
    .x(d => x(d.month))
    .y(d => y(d.total));

  const svg = d3.select(id).append('svg')
    .attr('width', width + margin.left + margin.right)
    .attr('height', height + margin.top + margin.bottom)
    .append('g')
    .attr(
      'transform',
      `translate(${margin.left},${margin.top})`,
    );

  data.forEach((d) => {
    d.month = parseTime(d.month);
    d.total = +d.total;
  });

  x.domain(d3.extent(data, d => d.month));
  y.domain([0, d3.max(data, d => d.total)]);

  svg.append('path')
    .data([data])
    .attr('class', 'line')
    .attr('d', valueline);

  const xAxis = d3.axisBottom(x)
    .ticks(data.length);

  svg.append('g')
    .attr('transform', `translate(0,${height})`)
    .call(xAxis);

  svg.append('g')
    .call(d3.axisLeft(y));
};
