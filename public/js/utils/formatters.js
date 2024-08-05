export function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

export function numberFormatter(number) {
  return new Intl.NumberFormat('en-KE', { maximumFractionDigits: 2 }).format(
    Number(number)
  );
}
