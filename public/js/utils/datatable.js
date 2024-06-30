const deletes = document.querySelectorAll('.btndel');
export function basicDatatable(table, columnDefs, ordering = false) {
  $(document).ready(function () {
    'use strict';
    $(`#${table}`).DataTable({
      ordering: ordering,
      columnDefs,
      responsive: true,
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

export const reportDatatable = (table, columnDefs) => {
  new DataTable(`#${table}`, {
    layout: {
      topStart: {
        buttons: [
          {
            extend: 'print',
            exportOptions: {
              columns: [0, ':visible'],
            },
          },
          {
            extend: 'excelHtml5',
            exportOptions: {
              columns: ':visible',
            },
          },
          {
            extend: 'pdfHtml5',
            exportOptions: {
              columns: [0, ':visible'],
            },
          },
        ],
      },
    },
    columnDefs,
  });
};
