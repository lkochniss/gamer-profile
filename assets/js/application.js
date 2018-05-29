import 'bootstrap';
import 'select2';
import $ from 'jquery';
import dashboard from './dashboard';
import datatables from './datatables';

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
        dashboard(id, data, '%d-%m-%y');
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
        dashboard(id, data, '%m-%y');
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
        dashboard(id, data, '%d-%m-%y');
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
  datatables('#blog-post-list', 3, 'DESC');
  datatables('#blog-post-for-game-list', 1, 'DESC');
  datatables('#blog-post-frontend-list', 1, 'DESC');
  datatables('#game-list-backend', 3, 'DESC');
  datatables('#game-session-list');
  datatables('#game-session-for-game-list', 1, 'DESC');
  datatables('#game-list');
  datatables('#purchase-list', 4, 'DESC');
}

$(document).ready(() => {
  enableSelect2();
  setWeekyDashboard();
  setMonthlyDashboard();
  setPlaytimeGame();
  addImgClass();
  addDataTables();
});
