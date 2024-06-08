export function basicDatatable(table, columnDefs, ordering = false) {
  $(document).ready(function () {
    'use strict';
    $(`#${table}`).DataTable({
      ordering: ordering,
      columnDefs,
    });
  });
}
