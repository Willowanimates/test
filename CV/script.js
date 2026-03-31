// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', () => {
    const fileInput = document.getElementById('profile_pic');
    const imagePreview = document.getElementById('img-preview');

    // 1. Live Image Preview Logic
    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            // Check if the file is actually an image
            if (!file.type.startsWith('image/')) {
                alert("Please select an actual image file (JPG, PNG).");
                this.value = ""; // Clear the input
                return;
            }

            // Check file size (e.g., limit to 2MB to prevent PHP upload errors)
            if (file.size > 2 * 1024 * 1024) {
                alert("File is too large! Please choose an image under 2MB.");
                this.value = "";
                return;
            }

            // Create a temporary URL for the selected file
            const reader = new FileReader();
            
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
                
                // Add a smooth fade-in effect via JS
                imagePreview.style.opacity = 0;
                setTimeout(() => {
                    imagePreview.style.transition = "opacity 0.5s ease";
                    imagePreview.style.opacity = 1;
                }, 10);
            };

            reader.readAsDataURL(file);
        }
    });

    // 2. Simple Form Animation
    // Adds a focus effect to the parent form-group when typing
    const inputs = document.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.style.transform = "translateX(5px)";
            input.parentElement.style.transition = "transform 0.3s ease";
        });
        
        input.addEventListener('blur', () => {
            input.parentElement.style.transform = "translateX(0)";
        });
    });
});