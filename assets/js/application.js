import 'select2';
import 'bootstrap';
import $ from 'jquery';
import dashboard from './dashboard';

const enableSelect2 = () => {
  $('#blog_post_game').select2();
  $('#purchase_game').select2();
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

$(document).ready(() => {
  enableSelect2();
  setWeekyDashboard();
  setMonthlyDashboard();
  setPlaytimeGame();
  $('.select2-container').addClass('col-form-label');
});
