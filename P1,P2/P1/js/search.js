var params = new URLSearchParams(window.location.search);
var q = params.get("q") || "";
var category = params.get("category") || "";

document.getElementById("searchInput").value = q;
var url = "php/search.php?q=" + encodeURIComponent(q) + "&category=" + encodeURIComponent(category);

fetch(url)
  .then(function (response) { return response.json(); })
  .then(function (list) {
    var box = document.getElementById("results");

    if (list.length === 0) {
      box.innerHTML = "<p>No news found. Try a different word.</p>";
      return;
    }

    var html = "";
    for (var i = 0; i < list.length; i++) {
      html += '<article class="news-item">' +
                "<small>" + list[i].category + "</small>" +
                "<h3>" + list[i].title + "</h3>" +
                "<p>" + list[i].text + "</p>" +
              "</article>";
    }
    box.innerHTML = html;
  })
  .catch(function () {
    document.getElementById("results").innerHTML = "<p>Could not load news. Run this site on a PHP server (XAMPP).</p>";
  });
