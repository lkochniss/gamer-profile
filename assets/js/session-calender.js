import * as d3 from 'd3';

export default (id, data, xFormat, year) => {
  const width = 960;
  const height = 136;
  const cellSize = 17; // cell size
  const format = d3.timeFormat(xFormat);

  const color = d3.scaleQuantize()
    .domain([0, 720])
    .range(d3.range(5).map(d => `q${d}-11`));

  const currentTime = new Date(year);
  const svg = d3.select(id)
    .selectAll('svg')
    .data(d3.range(currentTime.getFullYear(), currentTime.getFullYear() + 1))
    .enter()
    .append('svg')
    .attr('width', width)
    .attr('height', height)
    .attr('class', 'RdYlGn')
    .append('g')
    .attr('transform', `translate(${(width - cellSize * 53) / 2},${height - cellSize * 7 - 1})`);

  svg.append('text')
    .attr('transform', `translate(-6,${cellSize * 3.5})rotate(-90)`)
    .style('text-anchor', 'middle')
    .text(d => d);

  const tooltip = d3.select('body').append('div').attr('class', 'toolTip');

  const rect = svg.selectAll('.day')
    .data(d => d3.timeDays(new Date(d, 0, 1), new Date(d + 1, 0, 1)))
    .enter().append('rect')
    .attr('class', 'day')
    .attr('width', cellSize)
    .attr('height', cellSize)
    .attr('x', d => d3.timeWeek.count(d3.timeYear(d), d) * cellSize)
    .attr('y', d => d.getDay() * cellSize)
    .datum(format);

  const monthPath = (t0) => {
    const t1 = new Date(t0.getFullYear(), t0.getMonth() + 1, 0);
    const d0 = t0.getDay();
    const w0 = d3.timeWeek.count(d3.timeYear(t0), t0);
    const d1 = t1.getDay();
    const w1 = d3.timeWeek.count(d3.timeYear(t1), t1);
    return `M${(w0 + 1) * cellSize},${d0 * cellSize
    }H${w0 * cellSize}V${7 * cellSize
    }H${w1 * cellSize}V${(d1 + 1) * cellSize
    }H${(w1 + 1) * cellSize}V${0
    }H${(w0 + 1) * cellSize}Z`;
  };

  svg.selectAll('.month')
    .data(d => d3.timeMonths(new Date(d, 0, 1), new Date(d + 1, 0, 1)))
    .enter().append('path')
    .attr('class', 'month')
    .attr('d', monthPath);

  const chartData = d3.nest()
    .key(d => d.date)
    .rollup(d => (d[0].timeInMinutes))
    .map(data);

  const tooltipData = d3.nest()
    .key(d => d.date)
    .rollup(d => (d[0].timeForTooltip))
    .map(data);

  rect.filter(d => chartData.has(d))
    .attr('class', d => `day ${color(chartData.get(d))}`)
    .on('mouseover', (d) => {
      tooltip
        .style('left', `${d3.event.pageX - 50}px`)
        .style('top', `${d3.event.pageY - 70}px`)
        .style('display', 'inline-block')
        .html(`${d}: <b>${(tooltipData.get(d))}</b>`);
    })
    .on('mouseout', () => { tooltip.style('display', 'none'); });
};
