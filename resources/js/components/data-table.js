// Data Table Component JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Add row click handlers for clickable tables
    const clickableTables = document.querySelectorAll('[data-clickable-rows="true"]');

    clickableTables.forEach(table => {
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            // Skip empty state row
            if (row.querySelector('td[colspan]')) return;

            const itemId = row.querySelector('[data-item-id]')?.dataset.itemId;
            if (itemId) {
                row.style.cursor = 'pointer';

                row.addEventListener('click', function(e) {
                    // Don't trigger if clicking on interactive elements
                    if (e.target.closest('button, .clean-select, .comments-btn, a, input, select')) {
                        return;
                    }

                    // Get the route from data attribute or construct it
                    const route = this.dataset.clickRoute || `/items/${itemId}/edit`;
                    window.location.href = route;
                });

                // Add hover effect
                row.addEventListener('mouseenter', function() {
                    if (!this.classList.contains('archived-row')) {
                        this.style.backgroundColor = 'rgba(59, 130, 246, 0.05)';
                    }
                });

                row.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            }
        });
    });
});

// Utility function for handling row clicks
function handleRowClick(event, url) {
    // Don't trigger if clicking on interactive elements
    if (event.target.closest('button, .clean-select, .comments-btn, a, input, select')) {
        return;
    }
    window.location.href = url;
}
