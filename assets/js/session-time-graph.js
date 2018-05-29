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
  const formatTime = d3.timeFormat(xFormat);

  const valueline = d3.line()
    .x(d => x(d.date))
    .y(d => y(d.timeInMinutes));

  const svg = d3.select(id).append('svg')
    .attr('width', width + margin.left + margin.right)
    .attr('height', height + margin.top + margin.bottom)
    .append('g')
    .attr(
      'transform',
      `translate(${margin.left},${margin.top})`,
    );

  data.forEach((d) => {
    d.date = parseTime(d.date);
    d.timeInMinutes = +d.timeInMinutes;
  });

  x.domain(d3.extent(data, d => d.date));
  y.domain([0, d3.max(data, d => d.timeInMinutes)]);

  svg.append('path')
    .data([data])
    .attr('class', 'line')
    .attr('d', valueline);

  const ticks = data.length < 20 ? data.length : 20;

  const xAxis = d3.axisBottom(x)
    .ticks(ticks);

  svg.append('g')
    .attr('transform', `translate(0,${height})`)
    .call(xAxis);

  svg.append('g')
    .call(d3.axisLeft(y));

  const tooltip = d3.select('body').append('div').attr('class', 'toolTip');

  svg.selectAll('dot')
    .data(data)
    .enter().append('circle')
    .attr('r', 5)
    .attr('cx', d => x(d.date))
    .attr('cy', d => y(d.timeInMinutes))
    .on('mouseover', (d) => {
      tooltip
        .style('left', `${d3.event.pageX - 50}px`)
        .style('top', `${d3.event.pageY - 70}px`)
        .style('display', 'inline-block')
        .html(`${formatTime(d.date)}: <b>${d.timeInMinutes}</b>`);
    })
    .on('mouseout', () => { tooltip.style('display', 'none'); });
};
