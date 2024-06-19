import { basicDatatable } from '../../utils/datatable.js';
import { numberWithCommas } from '../../utils/formatters.js';
import { getSelectedText } from '../../utils/helpers.js';
import { HOST_URL } from '../../utils/host.js';
import { getRequest } from '../../utils/ajax.js';

const table = document.getElementById('items_table');
const tbody = table?.getElementsByTagName('tbody')[0];
const addButton = document.getElementById('add');
const productSelect = document.getElementById('product');
const vatTypeSelect = document.getElementById('vat_type');
const vatSelect = document.getElementById('vat');
const qtyInput = document.getElementById('qty');
const rateInput = document.getElementById('rate');
const valueInput = document.getElementById('value');
const totalInput = document.getElementById('total');
const alertBox = document.querySelector('.alert');

const items = [];

document.addEventListener('DOMContentLoaded', function () {
  function createItemObject(row) {
    const inputs = row.querySelectorAll('input');
    return {
      productId: inputs[0].value,
      productName: inputs[1].value,
      qty: parseInt(inputs[2].value, 10),
      rate: parseFloat(inputs[3].value.replace(',', '')),
      value: parseFloat(inputs[4].value.replace(',', '')),
    };
  }

  tbody?.querySelectorAll('tr').forEach(row => {
    const item = createItemObject(row);
    items.push(item);
  });
});

vatTypeSelect?.addEventListener('change', e => {
  if (e.target.value === 'no-vat') {
    vatSelect.disabled = true;
  } else {
    vatSelect.disabled = false;
  }
  vatSelect.value = '';
});

productSelect?.addEventListener('change', async e => {
  const product = e.target.value;
  if (!product || e.target.value.toString().trim().length === 0) return;
  const res = await getRequest(
    `${HOST_URL}/products/get_selling_rate?product_id=${e.target.value}`,
    alertBox
  );
  if (res.success) {
    rateInput.value = res.data;
  }
});

function updateTable() {
  tbody.innerHTML = '';
  const itemsMarkup = items
    .map(
      item =>
        ` <tr>
              <td class="hidden"><input type="text" name="product_id[]" value="${item.productId}"/></td>
              <td><input type="text" name="product[]" class="w-full" value="${item.productName}"/></td>
              <td><input type="number" name="qty[]" value="${item.qty}" /></td>
              <td><input type="number" name="rate[]" value="${item.rate}" /></td>
              <td><input type="number" name="value[]" value="${item.value}" /></td>
              <td><button type="button" class="outline-none border-none text-rose-400 focus:outline-0">Remove</button></td>
          </tr>
      `
    )
    .join('');
  tbody.innerHTML = itemsMarkup;

  const total = items
    .reduce((acc, item) => acc + item.value, 0)
    .toString()
    .replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  totalInput.value = total;
}

function getTotal(qty, rate) {
  return numberWithCommas(qty * rate);
}

rateInput?.addEventListener('blur', e => {
  if (
    !e.target.value ||
    e.target.value.toString().trim().length === 0 ||
    !qtyInput.value ||
    qtyInput.value.toString().trim().length === 0
  ) {
    return;
  }

  valueInput.value = getTotal(+qtyInput.value, +e.target.value);
});

qtyInput?.addEventListener('blur', e => {
  if (
    !e.target.value ||
    e.target.value.toString().trim().length === 0 ||
    !rateInput.value ||
    rateInput.value.toString().trim().length === 0
  ) {
    return;
  }

  valueInput.value = getTotal(+rateInput.value, +e.target.value);
});

addButton?.addEventListener('click', () => {
  if (!productSelect.value || !qtyInput.value || !rateInput.value) {
    alert('Please fill all the fields');
    return;
  }

  const itemIsSet = items.some(item => item.productId === productSelect.value);
  if (itemIsSet) {
    alert('This item has already been entered.');
    return;
  }

  const newItem = {
    productId: productSelect.value,
    productName: getSelectedText(productSelect),
    qty: qtyInput.value,
    rate: rateInput.value,
    value: +qtyInput.value * +rateInput.value,
  };
  items.push(newItem);
  updateTable();
  qtyInput.value =
    rateInput.value =
    valueInput.value =
    productSelect.value =
      '';
});

table?.addEventListener('click', e => {
  if (e.target.classList.contains('text-rose-400')) {
    e.target.closest('tr').remove();
  }
});

basicDatatable('invoicesDatatable', [{ width: '10%', targets: 4 }]);
