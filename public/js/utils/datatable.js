const deletes = document.querySelectorAll('.btndel');
export function basicDatatable(table, columnDefs, ordering = false) {
  $(document).ready(function () {
    'use strict';
    $(`#${table}`).DataTable({
      ordering: ordering,
      columnDefs,
    });
  });
}

export function deleteButtonClick(datatableId, modalId, inputId) {
  $(document).ready(function () {
    $(`#${datatableId}`).on('click', '.btndel', function () {
      $(`#${modalId}`).modal('show');

      $(`#${inputId}`).val(this.dataset.id);
    });
  });
}
