document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('newsForm');
    const cancelBtn = document.getElementById('cancelBtn');
    const submitBtn = document.getElementById('submitBtn');
    const messageBox = document.getElementById('form-message');

    // Handle "Cancel" button to clear the form
    cancelBtn.addEventListener('click', () => {
        if (confirm("Are you sure you want to clear the form?")) {
            form.reset();
            hideMessage();
        }
    });

    // Handle Form Submission
    form.addEventListener('submit', async (e) => {
        e.preventDefault(); 

        // Change button state
        submitBtn.textContent = 'Submitting...';
        submitBtn.disabled = true;
        hideMessage();

        // Gather form data
        const formData = new FormData(form);

        try {
            const response = await fetch('process.php', {
                method: 'POST',
                body: formData
            });

            // Check if the response is valid JSON
            const text = await response.text();
            let result;
            try {
                result = JSON.parse(text);
            } catch(err) {
                throw new Error("Server returned invalid response: " + text);
            }

            // Display success or error message
            if (result.status === 'success') {
                showMessage(result.message, 'success');
                form.reset(); 
                // Scroll to top to see the success message
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                showMessage(result.message, 'error');
            }
        } catch (error) {
            showMessage("An error occurred: " + error.message, 'error');
            console.error('Error:', error);
        } finally {
            // Restore button state
            submitBtn.textContent = 'Submit for Approval';
            submitBtn.disabled = false;
        }
    });

    // Helper function to show messages
    function showMessage(text, type) {
        messageBox.textContent = text;
        messageBox.className = 'message-box ' + type;
    }

    // Helper function to hide messages
    function hideMessage() {
        messageBox.textContent = '';
        messageBox.className = 'message-box';
    }
});