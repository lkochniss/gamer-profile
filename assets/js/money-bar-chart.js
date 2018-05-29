import $ from 'jquery';
import * as d3 from 'd3';

export default (id, data) => {
  const margin = {
    top: 20,
    right: 20,
    bottom: 30,
    left: 40,
  };

  const width = $(id).width() - margin.left - margin.right;
  const height = $(id).height() - margin.top - margin.bottom;

  const svg = d3.select(id).append('svg')
    .attr('width', width + margin.left + margin.right)
    .attr('height', height + margin.top + margin.bottom);

  const x = d3.scaleBand().rangeRound([0, width]).padding(0.1);
  const y = d3.scaleLinear().rangeRound([0, height]);

  x.domain(data.map(d => d.date));
  y.domain([d3.max(data, d => d.money), 0]);

  const g = svg.append('g')
    .attr('transform', `translate(${margin.left},${margin.top})`);

  g.append('g')
    .attr('class', 'axis axis--x')
    .attr('transform', `translate(0,${height})`)
    .call(d3.axisBottom(x));

  g.append('g')
    .attr('class', 'axis axis--y')
    .call(d3.axisLeft(y).ticks(10))
    .append('text')
    .attr('transform', 'rotate(-90)')
    .attr('y', 6)
    .attr('dy', '0.71em')
    .attr('text-anchor', 'end')
    .text('Money');

  g.selectAll('.bar')
    .data(data)
    .enter().append('rect')
    .attr('class', 'bar')
    .attr('x', d => x(d.date))
    .attr('y', d => y(d.money))
    .attr('width', x.bandwidth())
    .attr('height', d => height - y(d.money));
};
