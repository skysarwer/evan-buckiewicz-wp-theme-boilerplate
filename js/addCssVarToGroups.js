document.addEventListener( 'DOMContentLoaded', function() {
    // Get all Group blocks
    const groupBlocks = document.querySelectorAll('.wp-block-group');

    // Iterate through each Group block
    groupBlocks.forEach(block => {
        // Get the backgroundColor attribute
        const bgColor = block.style.backgroundColor;

        // Check if the backgroundColor is set
        if (bgColor) {
            // Add the custom inline style
            block.style.setProperty('--sbtl-bg-color', bgColor);
        }
    });
});