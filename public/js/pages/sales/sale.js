import { HOST_URL } from '../../utils/host.js';
import { getRequest } from '../../utils/ajax.js';
import { numberWithCommas } from '../../utils/formatters.js';
const baseUrl = `${HOST_URL}/products`;

const productSelect = document.getElementById('product');
const currentStockInput = document.getElementById('current_stock');
const current_store = document.getElementById('current_store');
const saleDateInput = document.getElementById('sale_date');
const qtyInput = document.getElementById('qty');
const rateInput = document.getElementById('rate');
const valueInput = document.getElementById('total_value');
const alertBox = document.querySelector('.alert');

productSelect?.addEventListener('change', async e => {
  if (!e.target.value || e.target.value.toString().trim().length === 0) return;
  const [rate, stock] = await Promise.all([getRate(), getStock()]);
  rateInput.value = numberWithCommas(rate);
  currentStockInput.value = stock;
});

async function getRate() {
  const product = productSelect.value;
  if (!product || product.toString().trim().length === 0) return;
  const res = await getRequest(
    `${baseUrl}/get_rate?product_id=${product}`,
    alertBox
  );
  if (!res.success) {
    alertBox.classList.add('custom-destructive');
    alertBox.innerHTML = `<p>${res.message}</p>`;
  }

  return res.data;
}

qtyInput?.addEventListener('blur', getValue);

async function getStock() {
  const date = saleDateInput.value;
  const product = productSelect.value;
  if (!date || !product) return;
  const res = await getRequest(
    `${baseUrl}/get_stock?product=${product}&date=${date}&store=${current_store.value}`,
    alertBox
  );
  if (!res.success) {
    alertBox.classList.add('custom-destructive');
    alertBox.innerHTML = `<p>${res.message}</p>`;
  }

  return res.data;
}

function getValue() {
  const qty = qtyInput.value;
  const rate = rateInput.value;
  if (!qty || !rate || qty.trim().length === 0 || rate.trim().length === 0)
    return;

  valueInput.value = numberWithCommas(parseFloat(qty) * parseFloat(rate));
}
