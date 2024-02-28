import { request } from "/scripts/xhr.js";

function applyImage(el) {
  let photo = el.getAttribute("data-pfp");
  el.style.background = `url(${photo})`;
}
let pfp = [...document.getElementsByClassName("pfp")];
pfp.forEach((el) => applyImage(el));

let logoutBtn = document.getElementById("logout");
logoutBtn.addEventListener("click", () => {
  request("GET", "/logout");
  window.location.reload();
});

let editBtn = document.getElementById("edit");
let editText = editBtn.querySelector(".text");
let editIcon = editBtn.querySelector(".material-symbols-rounded");
let photoBtn = document.getElementById("photoBtn");

let submitBtn = document.getElementById("submitBtn");
let submitText = submitBtn.querySelector(".text");
let submitIcon = submitBtn.querySelector(".material-symbols-rounded");

let stats = document.getElementById("stats");
let statsHead = document.getElementById("statsHead");

let photoPicker = document.getElementById("photoPicker");

let inputName = document.getElementById("inputName");
let pfpPreview = document.getElementById("pfpPreview");

let ogName = document.getElementById("authorName");
let ogPfp = document.getElementById("authorPfp");

editBtn.addEventListener("click", () => {
  toggleEdit();
});

function toggleEdit() {
  if (editIcon.innerText == "edit") {
    openEditing();
  } else if (editIcon.innerText == "close") {
    closeEditing();
  }
}

function openEditing() {
  editText.innerText = "Close";
  editIcon.innerText = "close";
  submitBtn.style.display = photoBtn.style.display = "flex";
  stats.style.display = statsHead.style.display = "none";
  inputName.removeAttribute("readonly");
  inputName.focus();
}

function closeEditing() {
  editText.innerText = "Edit";
  editIcon.innerText = "edit";
  submitBtn.style.display = photoBtn.style.display = "none";
  stats.style.display = statsHead.style.display = "flex";
  inputName.setAttribute("readonly", "true");
  inputName.value = document.title = ogName.value;
  pfpPreview.src = ogPfp.value;
}

photoPicker.addEventListener("input", () => {
  let photo = photoPicker.files[0];
  pfpPreview.src = URL.createObjectURL(photo);
});

let profileUpdateForm = document.getElementById("profileUpdateForm");
profileUpdateForm.addEventListener("submit", (e) => {
  e.preventDefault();
  submitBtn.disabled = true;
  let fd = new FormData(e.target);
  request(
    "POST",
    e.target.action,
    fd,
    (response) => {
      console.log(response);
      response = JSON.parse(response);
      if (response.status != 200) {
        submitBtn.classList.add("btn-danger");
        submitBtn.classList.remove("btn-success");
        submitText.innerText = `Error: ${response.status}`;
        submitIcon.innerHTML = "error";
      } else {
        submitText.innerText = "Updated successfully!";
        submitIcon.innerHTML = "done";
        ogName.value = response.name;
        ogPfp.value = response.pfp;
      }
      setTimeout(() => {
        submitText.innerText = "Update profile";
        submitIcon.innerText = "chevron_right";
        submitBtn.classList.remove("btn-danger");
        submitBtn.classList.add("btn-success");
        submitBtn.disabled = false;
        closeEditing();
      }, 4000);
    },
    (progress) => {
      submitText.innerText = `Updating ${Math.ceil(progress * 100)}`;
      submitIcon.innerText = "percent";
    },
  );
});
