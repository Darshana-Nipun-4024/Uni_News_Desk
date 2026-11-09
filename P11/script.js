document.addEventListener('DOMContentLoaded', () => {
    
    const form = document.getElementById('newsForm');
    const cancelBtn = document.getElementById('cancelBtn');
    const complianceCheckbox = document.getElementById('compliance');

    // Handle Cancel Button
    cancelBtn.addEventListener('click', () => {
        if (confirm('Are you sure you want to cancel? Your changes will be lost.')) {
            form.reset();
            // Optionally redirect: window.location.href = 'index.html';
        }
    });

    // Handle Form Submission
    form.addEventListener('submit', (e) => {
        e.preventDefault(); // Stop default form submission

        // Validate compliance checkbox
        if (!complianceCheckbox.checked) {
            alert('⚠️ You must agree to the NSBM media policy before submitting.');
            return;
        }

        // Collect form data
        const formData = new FormData(form);

        // Send data to PHP via Fetch API
        fetch('submit_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('✅ ' + data.message);
                form.reset();
            } else {
                alert('❌ Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Submission error:', error);
            alert('❌ Failed to submit. Please check your connection and try again.');
        });
    });

    // Live character feedback for textarea (optional enhancement)
    const content = document.getElementById('content');
    content.addEventListener('input', () => {
        if (content.value.length > 0 && content.value.length < 20) {
            content.style.borderColor = '#f01414';
        } else {
            content.style.borderColor = '';
        }
    });
});