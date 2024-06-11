import { basicDatatable } from '../../utils/datatable.js';

const table = document.getElementById('items_table');
const tbody = table?.getElementsByTagName('tbody')[0];
const addButton = document.getElementById('add');
const productInput = document.getElementById('product');
const qtyInput = document.getElementById('qty');
const rateInput = document.getElementById('rate');
const valueInput = document.getElementById('value');

const items = [];

function updateTable() {
  tbody.innerHTML = '';
  const itemsMarkup = items
    .map(
      item =>
        ` <tr>
            <td>${item.productName}</td>
            <td>${item.qty}</td>
            <td>${item.rate}</td>
            <td>${item.value}</td>
            <td><button type="button">Remove</button></td>
        </tr>
    `
    )
    .join('');
  console.log(itemsMarkup);
}

addButton?.addEventListener('click', () => {
  const newItem = {
    productName: productInput.value,
    qty: qtyInput.value,
    rate: rateInput.value,
    value: +qtyInput.value * +rateInput.value,
  };
  items.push(newItem);
  updateTable();
});

basicDatatable('purchasesDatatable', [{ width: '10%', targets: 4 }]);
