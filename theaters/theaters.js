import { request } from "/scripts/xhr.js";

let shareBtn = document.getElementById("shareButton");
shareBtn.addEventListener("click", async () => {
  let url =
    shareBtn.getAttribute("data-url") == "null" ? location.href : shareBtn.getAttribute("data-url");
  try {
    const shareData = {
      title: document.title,
      text: "Check out this video!",
      url: url,
    };
    await navigator.share(shareData);
  } catch (error) {
    console.error(error);
    shareBtn.querySelector(".material-symbols-rounded").innerText = "Error sharing";
    shareBtn.querySelector(".text").innerText = "error";
    shareBtn.classList.add("btn-danger");
  }
});

let commentator = document.getElementById("commentator");
let commentContent = document.getElementById("commentContent");
let sendButton = document.getElementById("sendButton");
let commentContainer = document.getElementById("commentLogs");

function updateSendButton() {
  if (
    commentContent.value.trim() != "" &&
    commentContent.value.trim().length < 800 &&
    (localStorage.getItem("commentator") != "" || commentator.value.trim() != "") &&
    (commentator.value.trim().length < 12 || localStorage.getItem("commentator").trim() < 12)
  ) {
    sendButton.disabled = false;
  } else {
    sendButton.disabled = true;
  }
}

if (localStorage.getItem("commentator") != null) {
  commentator.style.display = "none";
}

commentator.addEventListener("input", (e) => {
  if (e.target.value.length < 12) {
    localStorage.setItem("commentator", e.target.value.trim());
  }
});

commentContent.addEventListener("input", updateSendButton);

sendButton.addEventListener("click", (e) => {
  updateSendButton();
  let body = new FormData();
  let c = commentContent.value.trim();
  body.append("commentator", localStorage.getItem("commentator"));
  body.append("commentContent", c);
  body.append("articleID", e.target.getAttribute("data-article") * 1);
  request("POST", "/helpers/addComment.php", body, (response) => {
    console.log(response);
    response = JSON.parse(response);
    if (response.status != 200) {
      return;
    } else {
      let comment = document.createElement("div");
      comment.className = "comment";
      let commentData = document.createElement("div");
      commentData.className = "data";
      let commentatorName = document.createElement("div");
      commentatorName.className = "name";
      commentatorName.innerText = localStorage.getItem("commentator");
      let commentDate = document.createElement("div");
      commentDate.className = "date";
      commentDate.innerText = "Just now";
      commentData.append(commentatorName);
      commentData.append(commentDate);
      let commentText = document.createElement("div");
      commentText.className = "content";
      commentText.innerText = c;
      comment.append(commentData);
      comment.append(commentText);
      commentContainer.insertBefore(comment, commentContainer.firstChild);
    }
  });
  commentator.value = "";
  commentContent.value = "";
  updateSendButton();
  commentator.style.display = "none";
});

updateSendButton();
