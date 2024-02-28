function request(method = "POST", url, body, callback, progressCallback = null, headers = {}) {
  let xhr = new XMLHttpRequest();

  return new Promise((resolve, reject) => {
    xhr.open(method, url);

    for (let headerName in headers) {
      xhr.setRequestHeader(headerName, headers[headerName]);
    }

    xhr.upload.onprogress = (event) => {
      if (progressCallback && event.lengthComputable) {
        let progress = event.loaded / event.total;
        progressCallback(progress);
      }
    };
    xhr.onload = () => {
      if (xhr.status === 200) {
        resolve(xhr.response);
      } else {
        reject(xhr.status);
      }
    };

    xhr.onerror = () => {
      reject(new Error("Network error occurred"));
    };

    xhr.send(body);
  })
    .then((response) => {
      if (callback) {
        callback(response);
      }
    })
    .catch((err) => {
      console.error(err);
    });
}

export { request };