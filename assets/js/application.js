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
    url: '/admin/last-week',
    success: (data) => {
      dashboard('#playtime-last-week', data, '%d-%m-%y');
    },
  });
};

const setMonthlyDashboard = () => {
  $.getJSON({
    url: '/admin/sessions',
    success: (data) => {
      dashboard('#playtime-per-month', data, '%m-%y');
    },
  });
};

$(document).ready(() => {
  enableSelect2();
  setWeekyDashboard();
  setMonthlyDashboard();
});
