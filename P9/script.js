document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Fetch Event Data from PHP API
    const eventsContainer = document.getElementById('events-container');

    // Call the PHP file
    fetch('events_data.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Clear the "Loading events..." text
            eventsContainer.innerHTML = '';

            // Loop through the array and create HTML for each event
            data.forEach(eventData => {
                const article = document.createElement('article');
                article.className = 'news-card';
                
                article.innerHTML = `
                    <div class="card-image-wrapper">
                        <img src="${eventData.image}" alt="Event Image">
                    </div>
                    <h2 class="card-title">${eventData.title}</h2>
                    <p class="card-content">${eventData.content}</p>
                `;

                eventsContainer.appendChild(article);
            });
        })
        .catch(error => {
            console.error('Error fetching events:', error);
            eventsContainer.innerHTML = '<p class="loading-text">Error loading events. Please try again later.</p>';
        });


    // 2. Handle Button Clicks
    const breakingBtn = document.getElementById('breakingNewsBtn');
    if(breakingBtn) {
        breakingBtn.addEventListener('click', () => {
            alert('Loading Breaking News Module...');
        });
    }

    const submitBtn = document.getElementById('submitStoryBtn');
    if(submitBtn) {
        submitBtn.addEventListener('click', () => {
            // If you have the submit.html from the previous prompt, you can redirect here:
            // window.location.href = 'submit.html';
            alert('Redirecting to Submit News page...');
        });
    }

});