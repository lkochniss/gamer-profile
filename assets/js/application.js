import 'bootstrap';
import 'select2';
import $ from 'jquery';
import dataTables from './data-tables';
import moneyBarChart from './money-bar-chart';
import sessionTimeGraph from './session-time-graph';
import sessionCalendar from './session-calender';

const enableSelect2 = () => {
  $('#blog_post_game').select2();
  $('#purchase_game').select2();

  $('.select2-container').addClass('col-form-label');
};

const setWeeklyDashboard = () => {
  const id = '#playtime-last-week';
  if ($(id).length) {
    $.getJSON({
      url: '/admin/sessions/recently',
      success: (data) => {
        sessionTimeGraph(id, data, '%d %b %Y');
      },
    });
  }
};

const setMonthlyPlaytimeDashboard = () => {
  const id = '#playtime-per-month';
  if ($(id).length) {
    $.getJSON({
      url: '/admin/sessions/per-month',
      success: (data) => {
        sessionTimeGraph(id, data, '%b %Y');
      },
    });
  }
};


const setMonthlyAverageDashboard = () => {
  const id = '#average-per-month';
  if ($(id).length) {
    $.getJSON({
      url: '/admin/average/per-month',
      success: (data) => {
        sessionTimeGraph(id, data, '%b %Y');
      },
    });
  }
};

const setPlaytimeGame = () => {
  const id = '#playtime-game';

  if ($(id).length) {
    const gameId = $(id).data('game-id');
    $.getJSON({
      url: `/admin/sessions/game/${gameId}`,
      success: (data) => {
        sessionCalendar(id, data, '%d %b %Y', $(id)[0].dataset.year);
      },
    });
  }
};

const addPlaytimeGamesYearSelect = () => {
  const id = '#playtime-game';
  const selector = '.playtime-game-year-select';

  const items = $(selector);

  if (items.length) {
    items.toArray().forEach((item) => {
      item.addEventListener('click', () => {
        $(id)[0].dataset.year = item.dataset.year;
        $('#selectedYear').html(item.dataset.year);
        $(`${id} svg`).remove();

        setPlaytimeGame();
      });
    });
  }
};

const setSessionsPerMonthForGame = () => {
  const id = '#sessions-per-month-for-game';
  if ($(id).length) {
    const gameId = $(id).data('game-id');
    $.getJSON({
      url: `/admin/sessions/game/${gameId}/per-month`,
      success: (data) => {
        sessionTimeGraph(id, data, '%b %Y');
      },
    });
  }
};

const addImgClass = () => {
  const image = $('img');
  image.addClass('img-fluid');
  image.removeAttr('style');
};

const setInvestedMoneyPerMonth = () => {
  const id = '#invested-money-per-month';

  if ($(id).length) {
    $.getJSON({
      url: '/admin/money/per-month',
      success: (data) => {
        moneyBarChart(id, data, '%b %Y');
      },
    });
  }
};

const setInvestedMoneyPerYear = () => {
  const id = '#invested-money-per-year';

  if ($(id).length) {
    $.getJSON({
      url: '/admin/money/per-year',
      success: (data) => {
        moneyBarChart(id, data, '%Y');
      },
    });
  }
};

const setSessionsForYear = () => {
  const id = '#sessions-for-year';

  if ($(id).length) {
    $.getJSON({
      url: `/admin/sessions/${$(id)[0].dataset.year}`,
      success: (data) => {
        sessionCalendar(id, data, '%d %b %Y', $(id)[0].dataset.year);
      },
    });
  }
};

const addSessionsForYearSelect = () => {
  const id = '#sessions-for-year';
  const selector = '.sessions-for-year-select';

  const items = $(selector);

  if (items.length) {
    items.toArray().forEach((item) => {
      item.addEventListener('click', () => {
        $(id)[0].dataset.year = item.dataset.year;
        $('#selectedYear').html(item.dataset.year);
        $(`${id} svg`).remove();

        setSessionsForYear();
      });
    });
  }
};

const addDataTables = () => {
  dataTables('#blog-post-list', 3, 'DESC');
  dataTables('#blog-post-for-game-list', 1, 'DESC');
  dataTables('#blog-post-frontend-list', 1, 'DESC');
  dataTables('#game-list-backend', 4, 'DESC');
  dataTables('#game-session-list');
  dataTables('#game-session-for-game-list', 1, 'DESC');
  dataTables('#game-list');
  dataTables('#purchase-list', 4, 'DESC');
};

$(document).ready(() => {
  enableSelect2();
  setWeeklyDashboard();
  setMonthlyPlaytimeDashboard();
  setMonthlyAverageDashboard();
  setPlaytimeGame();
  addPlaytimeGamesYearSelect();
  addImgClass();
  addDataTables();
  setInvestedMoneyPerMonth();
  setInvestedMoneyPerYear();
  setSessionsForYear();
  addSessionsForYearSelect();
  setSessionsPerMonthForGame();
});
