document.addEventListener('DOMContentLoaded', () => {
    
    // Bookmark toggle
    const bookmarkBtn = document.querySelector('.bookmark-btn');
    if (bookmarkBtn) {
        bookmarkBtn.addEventListener('click', async () => {
            const id = bookmarkBtn.dataset.id;
            try {
                const res = await fetch('api/bookmark.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'article_id=' + id
                });
                const data = await res.json();
                if (data.status === 'success') {
                    bookmarkBtn.classList.toggle('active');
                    const icon = bookmarkBtn.querySelector('i');
                    const text = bookmarkBtn.querySelector('span');
                    if (data.action === 'added') {
                        icon.classList.replace('far', 'fas');
                        text.textContent = 'Bookmarked';
                    } else {
                        icon.classList.replace('fas', 'far');
                        text.textContent = 'Bookmark';
                    }
                } else {
                    alert(data.message);
                }
            } catch (err) {
                alert('Error: ' + err.message);
            }
        });
    }

    // Post Comment
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(commentForm);
            
            try {
                const res = await fetch('api/post_comment.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                
                if (data.status === 'success') {
                    // Prepend new comment
                    const list = document.getElementById('commentsList');
                    const commentHtml = `
                        <div class="comment" style="opacity:0;transition:0.5s;">
                            <div class="comment-avatar"><i class="fas fa-user-circle"></i></div>
                            <div class="comment-content">
                                <h4>${data.name} <small>Just now</small></h4>
                                <p>${data.comment}</p>
                            </div>
                        </div>
                    `;
                    list.insertAdjacentHTML('afterbegin', commentHtml);
                    setTimeout(() => list.firstElementChild.style.opacity = '1', 10);
                    commentForm.reset();
                } else {
                    alert(data.message);
                }
            } catch (err) {
                alert('Failed to post comment.');
            }
        });
    }
});