import { basicDatatable } from '../../utils/datatable.js';
import { getRequest } from '../../utils//ajax.js';
import { HOST_URL } from '../../utils/host.js';

const productSelect = document.getElementById('product');
const currentStockInput = document.getElementById('current_stock');
const transactionDateInput = document.getElementById('transfer_date');
const currentStoreInput = document.getElementById('current_store');
const alertBox = document.querySelector('.alert');
const qtyInput = document.getElementById('qty');
const addBtn = document.getElementById('add');
const table = document.getElementById('items_table');
const tbody = table.getElementsByTagName('tbody')[0];

const items = [];

basicDatatable('transfersDatatable', [{ width: '10%', targets: 5 }]);

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

  items.push({
    productId: productSelect.value,
    productName: productSelect.options[productSelect.selectedIndex].text,
    qty: qtyInput.value,
  });
  updateTable();
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
            <td class="hidden"><input class="table-input type="text" name="product_id[]" value="${item.productId}"/></td>
            <td class="hover:[&>*]:bg-transparent"><input type="text" name="product[]" class="w-full" value="${item.productName}"/></td>
            <td><input type="number" name="qty[]" value="${item.qty}" /></td>
            <td><button type="button" class="outline-none border-none text-rose-400 focus:outline-0">Remove</button></td>
        </tr>
        `;
    })
    .join('');
  tbody.innerHTML = itemsMarkup;
}
