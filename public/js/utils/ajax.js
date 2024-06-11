export async function sendHttpRequest(
  url,
  method = 'GET',
  body = null,
  headers = {},
  alertBox = undefined
) {
  try {
    const res = await fetch(url, {
      method,
      body,
      headers,
    });

    const data = await res.json();
    if (!res.ok) throw new Error(data.message);
    return data;
  } catch (error) {
    if (alertBox) {
      console.log(error.message);
      displayAlert(alertBox, 'There was a problem while making this request.');
    } else {
      console.error(error.message);
    }
  }
}

export async function getRequest(url, alerBox) {
  return await sendHttpRequest(url, 'GET', undefined, {}, alerBox);
}
