import $ from 'jquery';
import 'datatables';

export default (id, sortedColumn = 1, order = 'ASC') => {
  if ($(id).length) {
    $(id).DataTable({
      order: [[sortedColumn, order]],
    });
  }
};
