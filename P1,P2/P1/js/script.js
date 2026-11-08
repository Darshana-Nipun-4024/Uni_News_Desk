
function checkSearch() {
  var box = document.getElementById("searchInput");
  if (box.value.trim() === "") {
    alert("Please type something to search.");
    return false;
  }
  return true;
}
