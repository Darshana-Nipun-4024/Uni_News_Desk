document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Fetch Sports Data from PHP API
    const gridContainer = document.getElementById('sports-grid');

    fetch('sports_api.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response failed');
            }
            return response.json();
        })
        .then(data => {
            // Remove the loading message
            gridContainer.innerHTML = '';

            // Loop through the array of data
            data.forEach(item => {
                // Create the article container
                const article = document.createElement('article');
                article.className = 'news-card';
                
                // Construct the HTML for the card
                article.innerHTML = `
                    <div class="card-img-wrap">
                        <img src="${item.image}" alt="Sports News Image">
                    </div>
                    <h2 class="card-title">${item.title}</h2>
                    <p class="card-desc">${item.content}</p>
                `;

                // Add it to the grid on the page
                gridContainer.appendChild(article);
            });
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            gridContainer.innerHTML = '<p class="loading-msg">Error loading sports news. Please try again.</p>';
        });


    // 2. Add functionality to buttons
    const breakingBtn = document.getElementById('breakingBtn');
    if(breakingBtn) {
        breakingBtn.addEventListener('click', () => {
            alert('Loading Breaking News Module...');
        });
    }

    const submitNewsBtn = document.getElementById('submitNewsBtn');
    if(submitNewsBtn) {
        submitNewsBtn.addEventListener('click', () => {
            alert('Navigating to Submit News page...');
            // In a real app, this would be: window.location.href = 'submit.html';
        });
    }

});