document.addEventListener("DOMContentLoaded", () => {
  const table = document.getElementById("submissions-table");
  if (!table) return;

  //Elements 
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
    content: document.getElementById("detail-content"),
    empty: document.getElementById("detail-empty"),
  };

  // chose selected row
  let selected = rows[0];

  //Status update (Pending / Approved / Rejected)
  function setPill(el, status) {
    el.textContent = status.charAt(0).toUpperCase() + status.slice(1);
    el.className = "status status-" + status;
  }

  //click Row show the detail
  function showDetail(row) {
    selected = row;
    detail.content.hidden = false;
    detail.empty.hidden = true;

    rows.forEach((r) => r.classList.toggle("selected", r === row));

    detail.category.textContent = row.dataset.category;
    detail.title.textContent = row.dataset.title;
    detail.meta.textContent = "By " + row.dataset.author + " - Submitted " + row.dataset.time;
    detail.body.textContent = row.dataset.body;
    setPill(detail.status, row.dataset.status);

    // Pending buttons "decided" 
    const pending = row.dataset.status === "pending";
    detail.actions.hidden = !pending;
    detail.decided.hidden = pending;
  }

  //change the Status(approve / reject / reopen)
  function setStatus(status) {
    selected.dataset.status = status;
    setPill(selected.querySelector(".status"), status);
    showDetail(selected);
    applyFilter(document.querySelector("[data-filter].active").dataset.filter);
  }

  //Tab rows filter
  function applyFilter(filter) {
    tabs.forEach((t) => t.classList.toggle("active", t.dataset.filter === filter));
    rows.forEach((r) => {
      r.hidden = !(filter === "all" || r.dataset.status === filter);
    });
  }

  //Click events
  rows.forEach((r) => r.addEventListener("click", () => showDetail(r)));
  tabs.forEach((t) => t.addEventListener("click", () => applyFilter(t.dataset.filter)));

  // Empty Rows filter
  if (!selected) {
    applyFilter("pending");
    return;
  }

  document.getElementById("btn-approve").addEventListener("click", () => setStatus("approved"));
  document.getElementById("btn-reject").addEventListener("click", () => setStatus("rejected"));
  document.getElementById("btn-reopen").addEventListener("click", () => setStatus("pending"));

  //page load, show first row or empty
  applyFilter("pending");
  showDetail(selected);
});