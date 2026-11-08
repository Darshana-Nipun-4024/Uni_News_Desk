var params = new URLSearchParams(window.location.search);
var q = params.get("q") || "";
var category = params.get("category") || "";

document.getElementById("searchInput").value = q;
