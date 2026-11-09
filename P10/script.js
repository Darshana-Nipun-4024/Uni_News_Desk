document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Fetch News Data from PHP
    const newsContainer = document.getElementById('news-container');

    fetch('data.php')
        .then(response => response.json())
        .then(data => {
            // Clear the "Loading news..." text
            newsContainer.innerHTML = '';

            // Loop through the data and create HTML for each news item
            data.forEach(news => {
                const article = document.createElement('article');
                article.className = 'news-card';
                
                article.innerHTML = `
                    <div class="card-image-wrapper">
                        <img src="${news.image}" alt="News Image">
                    </div>
                    <h2 class="card-title">${news.title}</h2>
                    <p class="card-content">${news.content}</p>
                `;

                newsContainer.appendChild(article);
            });
        })
        .catch(error => {
            console.error('Error fetching news:', error);
            newsContainer.innerHTML = '<p>Error loading news. Please try again later.</p>';
        });

    // 2. Button functionalities
    const breakingBtn = document.getElementById('breakingNewsBtn');
    if(breakingBtn) {
        breakingBtn.addEventListener('click', () => {
            alert('Loading latest breaking news...');
        });
    }

    const submitBtn = document.getElementById('submitStoryBtn');
    if(submitBtn) {
        submitBtn.addEventListener('click', () => {
            alert('Redirecting to the news submission portal...');
        });
    }
});