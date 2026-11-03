// Add News - image upload previews (front-end only)
// The files are sent with the form (multipart/form-data) when you connect a backend.

document.addEventListener("DOMContentLoaded", () => {
  const MAX_SIZE = 5 * 1024 * 1024; // 5 MB
  const MAX_GALLERY = 10;

  const bannerInput = document.getElementById("banner-file");
  const bannerPreview = document.getElementById("banner-preview");
  const galleryInput = document.getElementById("gallery-files");
  const galleryPreview = document.getElementById("gallery-preview");
  const errorBox = document.getElementById("image-error");
  const form = document.getElementById("add-news-form");

  let galleryFiles = []; // our own list so single images can be removed

  function showError(msg) {
    errorBox.textContent = msg;
    errorBox.hidden = !msg;
  }

  function isValid(file) {
    if (!file.type.startsWith("image/")) {
      showError(file.name + " is not an image.");
      return false;
    }
    if (file.size > MAX_SIZE) {
      showError(file.name + " is larger than 5 MB.");
      return false;
    }
    return true;
  }

  // Build one thumbnail with an optional remove button
  function makeThumb(file, onRemove) {
    const figure = document.createElement("figure");
    figure.className = "image-thumb";

    const img = document.createElement("img");
    img.src = URL.createObjectURL(file);
    img.alt = file.name;
    img.onload = () => URL.revokeObjectURL(img.src);

    const caption = document.createElement("figcaption");
    caption.textContent = file.name;

    const btn = document.createElement("button");
    btn.type = "button";
    btn.textContent = "Remove";
    btn.setAttribute("aria-label", "Remove " + file.name);
    btn.addEventListener("click", onRemove);

    figure.append(img, caption, btn);
    return figure;
  }

  // ----- Banner (single image) -----
  bannerInput.addEventListener("change", () => {
    showError("");
    bannerPreview.innerHTML = "";
    const file = bannerInput.files[0];
    if (!file) return;
    if (!isValid(file)) {
      bannerInput.value = "";
      return;
    }
    bannerPreview.appendChild(
      makeThumb(file, () => {
        bannerInput.value = "";
        bannerPreview.innerHTML = "";
      })
    );
  });

  // ----- Gallery (many images) -----
  function syncGalleryInput() {
    // Put our list back into the real input so the form submits it
    const dt = new DataTransfer();
    galleryFiles.forEach((f) => dt.items.add(f));
    galleryInput.files = dt.files;
  }

  function renderGallery() {
    galleryPreview.innerHTML = "";
    galleryFiles.forEach((file, index) => {
      galleryPreview.appendChild(
        makeThumb(file, () => {
          galleryFiles.splice(index, 1);
          syncGalleryInput();
          renderGallery();
        })
      );
    });
  }

  galleryInput.addEventListener("change", () => {
    showError("");
    Array.from(galleryInput.files).forEach((file) => {
      if (galleryFiles.length >= MAX_GALLERY) {
        showError("You can add up to " + MAX_GALLERY + " images.");
        return;
      }
      if (isValid(file)) galleryFiles.push(file);
    });
    syncGalleryInput();
    renderGallery();
  });

  // Clear previews when the form is reset
  form.addEventListener("reset", () => {
    galleryFiles = [];
    bannerPreview.innerHTML = "";
    galleryPreview.innerHTML = "";
    showError("");
  });
});
