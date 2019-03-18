import 'bootstrap';
import 'select2';
import $ from 'jquery';
import dataTables from './data-tables';
import sessionTimeGraph from './session-time-graph';
import sessionCalendar from './session-calender';

const setWeeklyDashboard = () => {
  const id = '#playtime-last-week';
  if ($(id).length) {
    $.getJSON({
      url: '/sessions/recently',
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
      url: '/sessions/per-month',
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
      url: '/average/per-month',
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
      url: `/sessions/game/${gameId}`,
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
      url: `/sessions/game/${gameId}/per-month`,
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

const setSessionsForYear = () => {
  const id = '#sessions-for-year';

  if ($(id).length) {
    $.getJSON({
      url: `/sessions/${$(id)[0].dataset.year}`,
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
  dataTables('#game-list-backend', 4, 'DESC');
  dataTables('#game-session-list');
  dataTables('#game-session-for-game-list', 1, 'DESC');
  dataTables('#game-list');
  dataTables('#user-list');
};

$(document).ready(() => {
  setWeeklyDashboard();
  setMonthlyPlaytimeDashboard();
  setMonthlyAverageDashboard();
  setPlaytimeGame();
  addPlaytimeGamesYearSelect();
  addImgClass();
  addDataTables();
  setSessionsForYear();
  addSessionsForYearSelect();
  setSessionsPerMonthForGame();
});
