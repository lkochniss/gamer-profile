import 'select2';
import 'bootstrap';
import * as d3 from 'd3';

const square = d3.selectAll("rect");
square.style("fill", "orange");

$(document).ready(function () {
  $('#blog_post_game').select2();
  $('#purchase_game').select2();
});
