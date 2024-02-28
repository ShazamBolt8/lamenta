import { request } from "/scripts/xhr.js";

let articlesContainer = document.querySelector(".articles_container");

function applyImage(el) {
  let photo = el.getAttribute("data-pfp");
  el.style.background = `url(${photo})`;
}

function makeArticleElement(article) {
  let container = document.createElement("div");
  container.classList.add("box", "article");
  let title = document.createElement("a");
  title.classList.add("title", "head1");
  title.innerText = article.article_name;
  title.href = article.url;
  let pfp = document.createElement("span");
  pfp.classList.add("pfp");
  pfp.setAttribute("data-pfp", article.author_tiny_pfp);
  let category = document.createElement("span");
  category.classList.add("material-symbols-rounded", "category");
  category.innerText = article.category_icon;
  container.appendChild(title);
  container.appendChild(pfp);
  container.appendChild(category);
  applyImage(pfp);
  return container;
}

function requestArticles(query) {
  request("GET", `/helpers/getArticles.php?${query}`, null, (response) => {
    articlesContainer.innerHTML = "";
    let articles = JSON.parse(response);
    articles.forEach((article) => articlesContainer.append(makeArticleElement(article)));
  });
}

let searchBar = document.getElementById("searchBar");
let typingTimeout;
searchBar.addEventListener("input", (e) => {
  clearTimeout(typingTimeout);
  typingTimeout = setTimeout(() => {
    let input = e.target.value.trim();
    requestArticles(`search=${input}`);
  }, 300);
});

let pfp = [...document.getElementsByClassName("pfp")];
pfp.forEach((el) => applyImage(el));

let choiceTimeout;
let choices = [...document.getElementsByClassName("choice")];
choices.forEach((choice) => {
  choice.addEventListener("click", (e) => {
    let value = choice.getAttribute("data-id");
    clearTimeout(choiceTimeout);
    choiceTimeout = setTimeout(() => {
      if (value.startsWith("a")) {
        requestArticles(`author=${value.substring(1)}`);
      }
      if (value.startsWith("c")) {
        requestArticles(`category=${value.substring(1)}`);
      }
    }, 800);
  });
});
