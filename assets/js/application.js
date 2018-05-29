import 'bootstrap';
import 'select2';
import $ from 'jquery';
import moneyGraph from './money-graph';
import dataTables from './data-tables';

const enableSelect2 = () => {
  $('#blog_post_game').select2();
  $('#purchase_game').select2();

  $('.select2-container').addClass('col-form-label');
};

const setWeekyDashboard = () => {
  const id = '#playtime-last-week';
  if ($(id).length) {
    $.getJSON({
      url: '/admin/sessions/recently',
      success: (data) => {
        moneyGraph(id, data, '%d-%m-%y');
      },
    });
  }
};

const setMonthlyDashboard = () => {
  const id = '#playtime-per-month';
  if ($(id).length) {
    $.getJSON({
      url: '/admin/sessions/per-month',
      success: (data) => {
        moneyGraph(id, data, '%m-%y');
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
        moneyGraph(id, data, '%d-%m-%y');
      },
    });
  }
};

const addImgClass = () => {
  const image = $('img');
  image.addClass('img-fluid');
  image.removeAttr('style');
};

const addDataTables = () => {
  dataTables('#blog-post-list', 3, 'DESC');
  dataTables('#blog-post-for-game-list', 1, 'DESC');
  dataTables('#blog-post-frontend-list', 1, 'DESC');
  dataTables('#game-list-backend', 3, 'DESC');
  dataTables('#game-session-list');
  dataTables('#game-session-for-game-list', 1, 'DESC');
  dataTables('#game-list');
  dataTables('#purchase-list', 4, 'DESC');
}

$(document).ready(() => {
  enableSelect2();
  setWeekyDashboard();
  setMonthlyDashboard();
  setPlaytimeGame();
  addImgClass();
  addDataTables();
});
