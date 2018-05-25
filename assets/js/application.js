import 'select2';
import 'bootstrap';
import $ from 'jquery';
import * as d3 from 'd3';

const enableSelect2 = () => {
  $('#blog_post_game').select2();
  $('#purchase_game').select2();
};

const dashboard = (id, chartData) => {
  const maxWidth = $(id).width();
  const maxHeight = $(id).height();
  const totalPerMonth = chartData.map(data => data.total);
  const maxTotal = Math.max(...totalPerMonth);
  const heightPerCount = maxHeight / maxTotal;
  const widthPerBar = maxWidth / chartData.length;
  const playtimeChart = d3.select(id).append('svg')
    .attr('width', maxWidth)
    .attr('height', maxHeight);
  const bars = playtimeChart.selectAll('.bar').data(chartData).enter().append('g')
    .attr('class', 'bar');
  bars.append('rect')
    .attr('x', (data, key) => key * widthPerBar)
    .attr('y', data => maxHeight - (data.total * heightPerCount))
    .attr('width', widthPerBar)
    .attr('height', data => data.total * heightPerCount)
    .attr('fill', 'steelblue');
};

$(document).ready(() => {
  enableSelect2();
  $.getJSON({
    url: '/admin/sessions',
    success: (data) => {
      dashboard('#playtime-per-month', data);
    },
  });
});
