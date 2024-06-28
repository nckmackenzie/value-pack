import { basicDatatable, deleteButtonClick } from '../../utils/datatable.js';
import { numberWithCommas } from '../../utils/formatters.js';

basicDatatable('wastagesDatatable', [{ width: '10%', targets: 4 }]);
deleteButtonClick('wastagesDatatable', 'deleteModal', 'id');

const qtyInput = document.getElementById('qty_wasted');
const rateInput = document.getElementById('cost');
const valueInput = document.getElementById('wastage_value');

qtyInput?.addEventListener('blur', getValue);
rateInput?.addEventListener('blur', getValue);

function getValue() {
  const qty = qtyInput.value;
  const rate = rateInput.value;

  if (!qty || !rate) return;

  valueInput.value = numberWithCommas(parseFloat(qty) * parseFloat(rate));
}
