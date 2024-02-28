import { request } from "/scripts/xhr.js";

let choices = [...document.getElementsByClassName("choice")];
let summary = document.getElementById("summary");
let categoryID = document.getElementById("categoryID");
let details = document.getElementById("details");

choices.forEach((choice) => {
  choice.addEventListener("click", () => {
    summary.innerText = choice.querySelector(".name").innerText;
    categoryID.value = choice.getAttribute("data-id");
    details.removeAttribute("open");
  });
});

function setChoiceOnStart() {
  if (categoryID.value != "") {
    let category = document.querySelector(`.choice[data-id="${categoryID.value}"]`);
    //opening and closing is required to put value in summary
    details.setAttribute("open", true);
    summary.innerText = category.querySelector(".name").innerText;
    details.removeAttribute("open");
  }
}

function copyToClipboard(text) {
  navigator.clipboard.writeText(text);
}

let imageContainer = document.getElementById("imageContainer");
let filePicker = document.getElementById("uploader");
let status = document.getElementById("status");
let labelPicker = document.getElementById("labelPicker");

filePicker.addEventListener("input", (e) => {
  let picker = e.target;
  let image = picker.files[0];
  let body = new FormData();
  body.append("image", image);
  picker.disabled = true;
  labelPicker.classList.add("btn-success-disabled");
  labelPicker.classList.remove("btn-success");
  request(
    "POST",
    "/helpers/imageUpload.php",
    body,
    (res) => {
      console.log(res);
      res = JSON.parse(res);
      if (res.status === 200) {
        let imgBox = document.createElement("div");
        imgBox.classList.add("img");
        let pic = document.createElement("img");
        pic.src = res.url;
        let copyButton = document.createElement("button");
        copyButton.classList.add("btn");
        copyButton.setAttribute("data-src", res.url);
        let btnIcon = document.createElement("span");
        btnIcon.classList.add("material-symbols-rounded");
        btnIcon.innerText = "file_copy";
        copyButton.append(btnIcon);
        imgBox.append(pic);
        imgBox.append(copyButton);
        imageContainer.append(imgBox);
        status.innerText = "Uploaded successfully.";
        copyButton.addEventListener("click", function () {
          btnIcon.innerText = "done";
          copyToClipboard(this.getAttribute("data-src"));
          setTimeout(() => {
            btnIcon.innerText = "file_copy";
          }, 2000);
        });
      } else {
        status.innerText = `Error occured: ${res.status}`;
      }
      setTimeout(() => {
        picker.disabled = false;
        labelPicker.classList.remove("btn-success-disabled");
        labelPicker.classList.add("btn-success");
      }, 4000);
    },
    (progress) => {
      status.innerText = `Uploading: ${progress * 100}%`;
    },
  );
});

let leftBtn = document.getElementById("leftBtn");
let rightBtn = document.getElementById("rightBtn");
let search = document.getElementById("search");
let previewPhoto = document.getElementById("previewPhoto");
let copySearchSrc = document.getElementById("copySearchSrc");
let credit = document.getElementById("credit");

copySearchSrc.addEventListener("click", (e) => {
  copyToClipboard(e.target.getAttribute("data-src"));
  let copySearchSrcIcon = e.target.querySelector(".material-symbols-rounded");
  copySearchSrcIcon.innerText = "done";
  setTimeout(() => {
    copySearchSrcIcon.innerText = "file_copy";
  }, 2000);
});

let currentIndex = 0;
let searchResults = [];
let defaultPhoto = {
  description: "Batman &copy DC Studio",
  url: "https://i.postimg.cc/ZRk9sycr/the-dark-knight-20-1200-1200-675-675-crop-000000-1200x640.jpg",
};

searchResults.push(defaultPhoto);

function putImage(index) {
  previewPhoto.src = searchResults[index].url;
  previewPhoto.alt = credit.innerHTML = searchResults[index].description;
  copySearchSrc.setAttribute("data-src", searchResults[index].url);
}

putImage(currentIndex);

leftBtn.addEventListener("click", () => {
  if (currentIndex > 0) {
    currentIndex--;
    putImage(currentIndex);
  }
});

rightBtn.addEventListener("click", () => {
  if (currentIndex < searchResults.length - 1) {
    currentIndex++;
    putImage(currentIndex);
  }
});

let currentRequest = null;

search.addEventListener("input", (e) => {
  let value = e.target.value.trim();
  if (value.length <= 0) {
    return;
  }

  if (currentRequest) {
    clearTimeout(currentRequest);
  }

  currentRequest = setTimeout(() => {
    console.log("Sending request...");
    request(
      "GET",
      `https://api.pexels.com/v1/search?query=${value}`,
      null,
      (data) => {
        data = JSON.parse(data);
        let photos = data.photos;
        searchResults = [];
        for (let i = 0; i < photos.length; i++) {
          const photo = photos[i];
          let obj = {
            photographer: photo.photographer,
            photographer_url: photo.photographer_url,
            description: `Photo by <a href='${photo.photographer_url}'>${photo.photographer}</a> on <a href='https://pexels.com/'>Pexels.com</a>`,
            url: photo.src.large,
          };
          currentIndex = 0;
          searchResults.push(obj);
          putImage(currentIndex);
        }
      },
      (progress) => {},
      {
        Authorization: "AUTH_TOKEN_GOES_HERE",
      },
    );
  }, 1000);
});

let inputFields = [...document.getElementsByClassName("valueField")];
let requiredFields = [...document.querySelectorAll("[required]")];

requiredFields.forEach((field) => {
  field.addEventListener("input", updateSubmitButton);
});

function setValues() {
  let body = new FormData();
  inputFields.forEach((input) => {
    let value = input.value.trim();
    body.append(input.name, value);
  });
  return body;
}

let submitBtn = document.getElementById("submitBtn");
let submitBtnTxt = submitBtn.querySelector(".text");
let submitBtnIcon = submitBtn.querySelector(".material-symbols-rounded");

function updateSubmitButton() {
  submitBtn.disabled = requiredFields.some((field) => field.value.trim().length === 0);
}

submitBtn.addEventListener("click", (e) => {
  let isEmpty = requiredFields.some((field) => field.value.trim().length === 0);
  if (isEmpty) {
    return;
  }

  submitBtn.style.width = "60%";
  submitBtn.disabled = true;
  inputFields.forEach((field) => field.setAttribute("readonly", true));

  request(
    "POST",
    "/helpers/manageArticle.php",
    setValues(),
    (response) => {
      console.log(response);
      try {
        response = JSON.parse(response);
      } catch (error) {
        submitBtn.classList.remove("btn-success");
        submitBtn.classList.add("btn-danger");
        submitBtnIcon.innerText = "error";
        submitBtnTxt.innerText = `JSON ${error}`;
      }
      if (response.status === 200) {
        submitBtnTxt.innerText = "Submitted";
        submitBtnIcon.innerText = "done";
        setTimeout(() => {
          location.href = response.url;
        }, 1500);
      } else {
        submitBtn.classList.remove("btn-success");
        submitBtn.classList.add("btn-danger");
        submitBtnIcon.innerText = "priority_high";
        submitBtnTxt.innerText = `Error ${response.status}`;
      }
      setTimeout(() => {
        submitBtn.classList.add("btn-success");
        submitBtn.classList.remove("btn-danger");
        submitBtn.disabled = false;
        submitBtnTxt.innerText = "Submit";
        submitBtnIcon.innerText = "navigate_next";
        submitBtn.style.width = "30%";
        inputFields.forEach((field) => field.removeAttribute("readonly"));
      }, 3500);
    },
    (progress) => {
      submitBtnTxt.innerText = `Submitting ${Math.ceil(progress * 100)}`;
      submitBtnIcon.innerText = `percent`;
    },
  );
});

let backupBtn = document.getElementById("backupBtn");
backupBtn.addEventListener("click", (e) => {
  const date = new Date();
  const data = setValues();

  let dataJSON = {};
  for (var [key, value] of data.entries()) {
    dataJSON[key] = value;
  }

  let file = new File(
    [`\ufeff${JSON.stringify(dataJSON)}`],
    `${date.toString().replace(/ /g, "_")}_backup.json`,
    { type: "application/json; charset=UTF-8" },
  );
  let url = window.URL.createObjectURL(file);
  const a = document.createElement("a");

  a.style.display = "none";
  a.href = url;
  a.download = file.name;
  a.click();
  window.URL.revokeObjectURL(url);
});

let previewBtn = document.getElementById("previewBtn");
previewBtn?.addEventListener("click", (e) => {
  const data = setValues();
  const dataJSON = {};
  for (var [key, value] of data.entries()) {
    dataJSON[key] = value;
  }

  const params = new URLSearchParams();
  for (const key in dataJSON) {
    params.append(key, dataJSON[key]);
  }

  let type = e.target.getAttribute("data-isVideo") == 1 ? "theaters" : "articles";
  const url = `/${type}/?viewMethod=preview&${params.toString()}`;
  window.open(url, "_blank");
});

updateSubmitButton();
setChoiceOnStart();
