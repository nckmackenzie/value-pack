import { basicDatatable, deleteButtonClick } from '../../utils/datatable.js';
import { getRequest } from '../../utils/ajax.js';
import { HOST_URL } from '../../utils/host.js';

const productSelect = document.getElementById('product');
const currentStockInput = document.getElementById('current_stock');
const transactionDateInput = document.getElementById('transfer_date');
const currentStoreInput = document.getElementById('current_store');
const alertBox = document.querySelector('.alert');
const qtyInput = document.getElementById('qty');
const addBtn = document.getElementById('add');
const table = document.getElementById('items_table');
const tbody = table?.getElementsByTagName('tbody')[0];

const items = [];

basicDatatable('transfersDatatable', [{ width: '10%', targets: 4 }]);
deleteButtonClick('transfersDatatable', 'deleteModal', 'id');

document.addEventListener('DOMContentLoaded', function () {
  function createItemObject(row) {
    const inputs = row.querySelectorAll('input');
    return {
      productId: inputs[0].value,
      productName: inputs[1].value,
      qty: parseInt(inputs[2].value, 10),
    };
  }

  tbody?.querySelectorAll('tr').forEach(row => {
    const item = createItemObject(row);
    items.push(item);
  });
});

productSelect?.addEventListener('change', async e => {
  const product = e.target.value;
  if (!product || e.target.value.toString().trim().length === 0) return;
  await getStock();
});

addBtn?.addEventListener('click', () => {
  if (!productSelect.value || !qtyInput.value || !currentStockInput.value) {
    alert('Please fill all the fields');
    return;
  }

  if (+qtyInput.value > +currentStockInput.value) {
    alert('Not enough stock');
    return;
  }

  const itemEntered = items.some(
    item => item.productId === productSelect.value
  );
  if (itemEntered) {
    alert('This item has already been entered.');
    return;
  }

  items.push({
    productId: productSelect.value,
    productName: productSelect.options[productSelect.selectedIndex].text,
    qty: qtyInput.value,
  });
  updateTable();
  qtyInput.value = productSelect.value = currentStockInput.value = '';
});

table?.addEventListener('click', function (e) {
  if (e.target.classList.contains('text-rose-400')) {
    const productId = e.target.closest('tr').querySelector('input').value;
    const index = items.findIndex(item => item.productId === productId);
    items.splice(index, 1);
    updateTable();
  }
});

async function getStock() {
  if (
    !productSelect.value ||
    !transactionDateInput.value ||
    !currentStoreInput.value
  ) {
    return;
  }
  const product = productSelect.value;
  const store = currentStoreInput.value;
  const date = transactionDateInput.value;
  const res = await getRequest(
    `${HOST_URL}/products/get_stock?product=${product}&store=${store}&date=${date}`,
    alertBox
  );
  if (res.success) {
    currentStockInput.value = res.data;
  }
}

function updateTable() {
  tbody.innerHTML = '';
  const itemsMarkup = items
    .map(item => {
      return `
        <tr class="hover:[&>*]:bg-transparent">
            <td class="hidden"><input class="table-input" type="text" name="product_id[]" value="${item.productId}"/></td>
            <td class="hover:[&>*]:bg-transparent"><input type="text" name="product[]" class="table-input w-full" value="${item.productName}" readonly/></td>
            <td><input type="number" name="qty[]" class="table-input w-full" value="${item.qty}" readonly /></td>
            <td><button type="button" class="outline-none border-none text-rose-400 focus:outline-0">Remove</button></td>
        </tr>
        `;
    })
    .join('');
  tbody.innerHTML = itemsMarkup;
}
