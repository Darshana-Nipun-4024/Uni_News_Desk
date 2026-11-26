<?php

require_once 'includes/db_connect.php';
require_once 'includes/auth_check.php';
require_once 'includes/functions.php';


requireLogin();

on
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$msg = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $content = sanitize($_POST['content']);
    $category_id = (int)$_POST['category_id'];
    $author = sanitize($_POST['author']);
    $image_path = ''; 

    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['featured_image'];

        if ($file['error'] === UPLOAD_ERR_OK) {
            $max_size = 5 * 1024 * 1024; 
            $allowed_mimes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp'
            ];

           
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $finfo->file($file['tmp_name']);

            if (!array_key_exists($mime_type, $allowed_mimes)) {
                $error = "Invalid file format. Only PNG, JPG, and WEBP images are allowed.";
            } elseif ($file['size'] > $max_size) {
                $error = "File is too large. Maximum allowed size is 5MB.";
            } else {
                
                $upload_dir = 'uploads/articles/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                
                $ext = $allowed_mimes[$mime_type];
                $new_filename = uniqid('news_', true) . '.' . $ext;
                $destination = $upload_dir . $new_filename;

                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $image_path = $destination; 
                } else {
                    $error = "Failed to save the uploaded image. Please check folder permissions.";
                }
            }
        } else {
            $error = "An error occurred while uploading the file.";
        }
    }

    
    if (empty($error)) {
       
        $stmt = $pdo->prepare("INSERT INTO articles (title, content, category_id, author_id, author_name, image_url, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
        
       
        if ($stmt->execute([$title, $content, $category_id, $_SESSION['user_id'], $author, $image_path])) {
            $msg = "Article submitted successfully! It will be reviewed by the admin.";
        } else {
            $error = "Database error: Could not submit your article.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit News - NSBM NewsDesk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container" style="padding:40px 20px;">
    <div class="submit-form-wrapper">
        <span class="portal-tag">Student & Club Portal</span>
        <h1>Submit News Article or Announcement</h1>
        <p>Submitted articles undergo review by the Faculty NewsDesk Admin before being published live.</p>
        
        <?php if($msg): ?><div class="alert success"><?= $msg ?></div><?php endif; ?>
        <?php if($error): ?><div class="alert error"><?= $error ?></div><?php endif; ?>
        
     
        <form method="POST" id="newsForm" enctype="multipart/form-data">
            <label>Article Title *</label>
            <input type="text" name="title" placeholder="e.g. Computing Faculty Hackathon 2026" required>

            <div class="form-row">
                <div>
                    <label>Category *</label>
                    <select name="category_id" required>
                        <option value="">Select</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['category_id'] ?>"><?= $cat['name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Author / Club *</label>
                    <input type="text" name="author" placeholder="e.g. Software Engineering Society" required>
                </div>
            </div>

        
            <label for="featured_image">Featured Image (Optional)</label>
            <div class="file-upload-wrapper" id="drop-zone">
                <input type="file" name="featured_image" id="featured_image" accept="image/png, image/jpeg, image/webp" hidden>
                
                <div class="upload-placeholder" id="upload-placeholder">
                    <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                    <p class="upload-text">
                        <span class="browse-btn">Click to upload</span> or drag and drop
                    </p>
                    <span class="upload-hint">PNG, JPG, or WEBP (Max. 5MB)</span>
                </div>

              
                <div class="file-preview" id="file-preview" style="display: none;">
                    <img id="preview-img" src="" alt="Preview">
                    <div class="file-info">
                        <span id="file-name" class="file-name">image.jpg</span>
                        <button type="button" id="remove-file" class="remove-btn">Remove</button>
                    </div>
                </div>
            </div>

            <label>Article Content *</label>
            <textarea name="content" rows="8" required></textarea>

            <label class="checkbox-label">
                <input type="checkbox" required> I confirm the submitted content complies with NSBM media policies.
            </label>

            <div class="form-actions">
                <a href="index.php" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit-main">Submit for Approval</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<style>
.submit-form-wrapper {
    background: #b8e0c2;
    max-width: 750px;
    margin: 20px auto;
    padding: 40px;
    border-radius: 20px;
}
.portal-tag { background:#1a8c2f; color:white; padding:5px 15px; border-radius:15px; font-size:12px; }
.submit-form-wrapper h1 { color:#0d5c1c; margin:15px 0 5px; font-size:26px; }
.submit-form-wrapper label { display:block; margin:15px 0 5px; font-weight:500; font-size:14px; }
.submit-form-wrapper input[type="text"], 
.submit-form-wrapper select, 
.submit-form-wrapper textarea {
    width:100%; padding:12px; background:#92c9a1; border:1px solid #1a8c2f; border-radius:8px; font-family:inherit; box-sizing: border-box;
}
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:15px; }


.file-upload-wrapper {
    background: #92c9a1;
    border: 1.5px dashed #1a8c2f;
    border-radius: 8px;
    padding: 22px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
}
.file-upload-wrapper:hover,
.file-upload-wrapper.dragover {
    background: #84be94;
    border-style: solid;
}
.upload-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}
.upload-icon {
    font-size: 28px;
    color: #1a8c2f;
}
.upload-text {
    margin: 0;
    font-size: 14px;
    color: #0d3b16;
}
.browse-btn {
    color: #0d5c1c;
    font-weight: 700;
    text-decoration: underline;
}
.upload-hint {
    font-size: 12px;
    color: #21522b;
}


.file-preview {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    text-align: left;
}
.file-preview img {
    width: 68px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #1a8c2f;
}
.file-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex: 1;
    overflow: hidden;
}
.file-name {
    font-size: 14px;
    font-weight: 500;
    color: #0d3b16;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 350px;
}
.remove-btn {
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
}
.remove-btn:hover { opacity: 0.85; }

.checkbox-label { display:flex; align-items:center; gap:10px; background:white; padding:12px; border-radius:8px; margin:20px 0; cursor: pointer; }
.checkbox-label input[type="checkbox"] { width: auto; }
.form-actions { display:flex; justify-content:flex-end; gap:10px; }
.btn-cancel { padding:10px 25px; background:white; border:1px solid #ccc; border-radius:8px; text-decoration:none; color:black; }
.btn-submit-main { padding:10px 25px; background:#1a8c2f; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:700; }
.alert.success { background:#d4edda; color:#155724; padding:15px; border-radius:8px; margin:15px 0; }
.alert.error { background:#f8d7da; color:#721c24; padding:15px; border-radius:8px; margin:15px 0; }
</style>

<script>
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('featured_image');
    const placeholder = document.getElementById('upload-placeholder');
    const previewContainer = document.getElementById('file-preview');
    const previewImg = document.getElementById('preview-img');
    const fileNameDisplay = document.getElementById('file-name');
    const removeBtn = document.getElementById('remove-file');

    dropZone.addEventListener('click', (e) => {
        if (e.target !== removeBtn) {
            fileInput.click();
        }
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            handleFile(fileInput.files[0]);
        }
    });

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            handleFile(e.dataTransfer.files[0]);
        }
    });

    function handleFile(file) {
        const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert('Please upload a valid image file (PNG, JPG, or WEBP).');
            fileInput.value = '';
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            alert('File size exceeds 5MB.');
            fileInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            fileNameDisplay.textContent = file.name;
            placeholder.style.display = 'none';
            previewContainer.style.display = 'flex';
        };
        reader.readAsDataURL(file);
    }

    removeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        fileInput.value = '';
        previewImg.src = '';
        previewContainer.style.display = 'none';
        placeholder.style.display = 'flex';
    });
</script>
</body>
</html>
