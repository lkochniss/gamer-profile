import 'bootstrap';
import 'jquery-datatables-checkboxes/js/dataTables.checkboxes.min';

import $ from 'jquery';
import dataTables from './data-tables';
import sessionTimeGraph from './session-time-graph';
import sessionCalendar from './session-calender';

const setWeeklyDashboard = () => {
  const id = '#playtime-last-week';
  if ($(id).length) {
    $.getJSON({
      url: '/api/sessions/recently',
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
      url: '/api/sessions/per-month',
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
      url: '/api/average/per-month',
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
      url: `/api/sessions/game/${gameId}`,
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
      url: `/api/sessions/game/${gameId}/per-month`,
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
      url: `/api/sessions/${$(id)[0].dataset.year}`,
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
  $('#game-list-backend').DataTable({
    columnDefs: [
      {
        targets: 0,
        checkboxes: {
          selectAllPages: false,
        },
      },
    ],
  });

  const changeStatus = (status) => {
    document.getElementById('overlay').style.display = 'flex';

    setTimeout(() => {
      const numberOfItems = document.querySelectorAll('input.dt-checkboxes:checked').length;
      let processedItems = 0;

      document.querySelectorAll('input.dt-checkboxes:checked').forEach((item) => {
        $.ajax({
          url: `/game/${item.parentElement.dataset.gameId}/status/${status}`,
          success: () => {
            processedItems += 1;
            if (processedItems === numberOfItems) {
              window.location.reload();
            }
          },
        });
      });
    }, 1);
  };

  $('#selected_open').on('click', () => {
    changeStatus('open');
  });

  $('#selected_paused').on('click', () => {
    changeStatus('paused');
  });

  $('#selected_playing').on('click', () => {
    changeStatus('playing');
  });

  $('#selected_finished').on('click', () => {
    changeStatus('finished');
  });

  $('#selected_given-up').on('click', () => {
    changeStatus('given-up');
  });

  dataTables('#game-session-list');
  dataTables('#game-session-for-game-list', 1, 'DESC');
  dataTables('#game-list');
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
