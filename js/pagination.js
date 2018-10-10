function getPageId(n) {
  return 'article-page-' + n;
}

function getDocumentHeight() {
  var body = document.body;
  var html = document.documentElement;

  return Math.max(
    body.scrollHeight, body.offsetHeight,
    html.clientHeight, html.scrollHeight, html.offsetHeight);

};

function getScrollTop() {
  return window.pageYOffset !== undefined ? window.pageYOffset : (document.documentElement || document.body.parentNode || document.body).scrollTop;
}

var wp_image_index = 0;
function getArticleImage() {
  var image = new Image();
  data = {};

  if (typeof wp_images === 'undefined' || wp_images.length < 1) {
    var hash = Math.floor(Math.random() * Number.MAX_SAFE_INTEGER);
    image.src = 'http://api.adorable.io/avatars/250/' + hash;
  } else {
    wp_image_index = (wp_image_index >= wp_images.length) ? 0 : wp_image_index;
    data.image_el = image
    data.image_el.src = wp_images[wp_image_index].image
    data.image = wp_images[wp_image_index].image
    data.link = wp_images[wp_image_index].link
    data.title = wp_images[wp_image_index].title
    wp_image_index++;
  }

  return data;
}

function createElementFromHTML(htmlString) {
  var div = document.createElement('div');
  div.innerHTML = htmlString.trim();

  // Change this to div.childNodes to support multiple top-level nodes
  return div.firstChild;
}

function getArticle() {
  var data = getArticleImage();
  var article = document.createElement('article');


  article.appendChild(data.image_el);

  block = createElementFromHTML(`<a href='${data.link}'>${article.innerHTML}
  <span>${data.title}</span></a>`);
  block.className = 'article-list__item article-list__item__image';

  block.onload = function () {
    block.classList.remove('article-list__item__image--loading');
  };

  return block
}

function getArticlePage(page) {
  var articlesPerPage = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 16;
  var pageElement = document.createElement('div');
  pageElement.id = getPageId(page);
  pageElement.className = 'article-list__page';

  while (articlesPerPage--) {
    pageElement.appendChild(getArticle());
  }

  return pageElement;
}

function addPaginationPage(page) {
  var pageLink = document.createElement('a');
  pageLink.href = '#' + getPageId(page);
  pageLink.innerHTML = page;

  var listItem = document.createElement('li');
  listItem.className = 'article-list__pagination__item';
  listItem.appendChild(pageLink);

  articleListPagination.appendChild(listItem);

  if (page === 2) {
    articleListPagination.classList.remove('article-list__pagination--inactive');
  }
}

function fetchPage(page) {
  articleList.appendChild(getArticlePage(page));
}

function addPage(page) {
  fetchPage(page);
  addPaginationPage(page);
}

var articleList = document.getElementById('article-list');
var articleListPagination = document.getElementById('article-list-pagination');
var page = 0;

addPage(++page);

window.onscroll = function () {
  if (getScrollTop() < getDocumentHeight() - window.innerHeight) return;
  addPage(++page);
};
