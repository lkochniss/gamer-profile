import 'select2';
import 'bootstrap';
import $ from 'jquery';
import dashboard from './dashboard';

const enableSelect2 = () => {
  $('#blog_post_game').select2();
  $('#purchase_game').select2();
};

const setWeekyDashboard = () => {
  $.getJSON({
    url: '/admin/sessions/recently',
    success: (data) => {
      dashboard('#playtime-last-week', data, '%d-%m-%y');
    },
  });
};

const setMonthlyDashboard = () => {
  $.getJSON({
    url: '/admin/sessions/per-month',
    success: (data) => {
      dashboard('#playtime-per-month', data, '%m-%y');
    },
  });
};

const setPlaytimeGame = () => {
  const gameId = $('#playtime-game').data('game-id');
  $.getJSON({
    url: `/admin/sessions/game/${gameId}`,
    success: (data) => {
      dashboard('#playtime-game', data, '%d-%m-%y');
    },
  });
};

$(document).ready(() => {
  enableSelect2();
  setWeekyDashboard();
  setMonthlyDashboard();
  setPlaytimeGame();
});
