const API_URL = "api.php";

const newsGrid = document.getElementById("newsGrid");
const searchForm = document.getElementById("searchForm");
const searchInput = document.getElementById("searchInput");
const clearFilter = document.getElementById("clearFilter");
const categoryButtons = document.querySelectorAll(".category-card");
const submitForm = document.getElementById("submitForm");
const formMessage = document.getElementById("formMessage");

let activeCategory = "";
let currentQuery = "";

function escapeHTML(value) {
  return String(value ?? "").replace(/[&<>"']/g, char => ({
    "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;"
  })[char]);
}

function renderNews(items) {
  if (!items || items.length === 0) {
    newsGrid.innerHTML = '<p class="empty-state">No news found. Try another search or category.</p>';
    return;
  }

  newsGrid.innerHTML = items.map(item => `
    <article class="news-card">
      <div class="news-image" style="${item.image ? `background-image:linear-gradient(0deg,rgba(10,20,40,.2),rgba(10,20,40,.05)),url('${escapeHTML(item.image)}');background-size:cover;background-position:center;` : ""}"></div>
      <div class="news-body">
        <span class="news-category">${escapeHTML(item.category)}</span>
        <h3>${escapeHTML(item.title)}</h3>
        <p>${escapeHTML(item.description)}</p>
        <span class="news-date">${escapeHTML(item.date)}</span>
      </div>
    </article>
  `).join("");
}

async function loadNews() {
  newsGrid.innerHTML = '<p class="loading">Loading campus updates...</p>';
  const params = new URLSearchParams();
  if (activeCategory) params.set("category", activeCategory);
  if (currentQuery) params.set("q", currentQuery);

  try {
    const response = await fetch(`${API_URL}?${params.toString()}`);
    if (!response.ok) throw new Error("Could not load news");
    const data = await response.json();
    renderNews(data.news);
  } catch (error) {
    newsGrid.innerHTML = '<p class="empty-state">Unable to load news. Make sure you are running this project through a PHP server.</p>';
  }
}

searchForm.addEventListener("submit", event => {
  event.preventDefault();
  currentQuery = searchInput.value.trim();
  loadNews();
});

categoryButtons.forEach(button => {
  button.addEventListener("click", () => {
    activeCategory = button.dataset.category;
    categoryButtons.forEach(item => item.classList.toggle("active", item === button));
    loadNews();
    document.getElementById("news").scrollIntoView({ behavior: "smooth" });
  });
});

clearFilter.addEventListener("click", () => {
  activeCategory = "";
  currentQuery = "";
  searchInput.value = "";
  categoryButtons.forEach(button => button.classList.remove("active"));
  loadNews();
});

submitForm.addEventListener("submit", async event => {
  event.preventDefault();
  formMessage.textContent = "Submitting...";
  const formData = new FormData(submitForm);
  const payload = Object.fromEntries(formData.entries());

  try {
    const response = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    });
    const result = await response.json();
    if (!response.ok || !result.success) throw new Error(result.message || "Submission failed");
    formMessage.textContent = result.message;
    submitForm.reset();
  } catch (error) {
    formMessage.textContent = error.message || "Unable to submit your news.";
  }
});

document.getElementById("menuToggle").addEventListener("click", () => {
  document.querySelector(".main-nav").classList.toggle("open");
});

loadNews();
