import { basicDatatable } from '../../utils/datatable.js';

const switchCheck = document.querySelector('#label_stock');
const switchInput = document.querySelector('#is_stock');

function activateMultiSelect() {
  $(function () {
    $('#stores').multiselect({
      includeSelectAllOption: true,
      buttonWidth: '100%',
      maxHeight: 200,
    });
  });
}

switchCheck?.addEventListener('click', function (e) {
  switchInput.value = switchInput.checked.toString();
});

activateMultiSelect();
basicDatatable('productsDatatable', [{ width: '10%', targets: 5 }]);
