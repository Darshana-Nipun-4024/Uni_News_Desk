// NSBM NewsDesk Admin - basic behaviour (front-end only)
// Replace the TODO parts with real calls to your backend API.

document.addEventListener("DOMContentLoaded", () => {
  const table = document.getElementById("submissions-table");
  if (!table) return; // only runs on submissions.html

  const rows = Array.from(table.querySelectorAll("tr[data-id]"));
  const tabs = document.querySelectorAll("[data-filter]");
  const detail = {
    category: document.getElementById("detail-category"),
    status: document.getElementById("detail-status"),
    title: document.getElementById("detail-title"),
    meta: document.getElementById("detail-meta"),
    body: document.getElementById("detail-body"),
    actions: document.getElementById("detail-actions"),
    decided: document.getElementById("detail-decided"),
    note: document.getElementById("detail-note"),
  };
  let selected = rows[0];

  function setPill(el, status) {
    el.textContent = status.charAt(0).toUpperCase() + status.slice(1);
    el.className = "status status-" + status;
  }

  function showDetail(row) {
    selected = row;
    rows.forEach((r) => r.classList.toggle("selected", r === row));
    detail.category.textContent = row.dataset.category;
    detail.title.textContent = row.dataset.title;
    detail.meta.textContent = "By " + row.dataset.author + " - Submitted " + row.dataset.time;
    detail.body.textContent = row.dataset.body;
    setPill(detail.status, row.dataset.status);
    const pending = row.dataset.status === "pending";
    detail.actions.hidden = !pending;
    detail.decided.hidden = pending;
  }

  function setStatus(status) {
    // TODO: send to backend, e.g. fetch("/api/articles/" + selected.dataset.id, {...})
    selected.dataset.status = status;
    setPill(selected.querySelector(".status"), status);
    showDetail(selected);
    applyFilter(document.querySelector("[data-filter].active").dataset.filter);
  }

  function applyFilter(filter) {
    tabs.forEach((t) => t.classList.toggle("active", t.dataset.filter === filter));
    rows.forEach((r) => {
      r.hidden = !(filter === "all" || r.dataset.status === filter);
    });
  }

  rows.forEach((r) => r.addEventListener("click", () => showDetail(r)));
  tabs.forEach((t) => t.addEventListener("click", () => applyFilter(t.dataset.filter)));
  document.getElementById("btn-approve").addEventListener("click", () => setStatus("approved"));
  document.getElementById("btn-reject").addEventListener("click", () => setStatus("rejected"));
  document.getElementById("btn-reopen").addEventListener("click", () => setStatus("pending"));

  applyFilter("pending");
  showDetail(selected);
});
