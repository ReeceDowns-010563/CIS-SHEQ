// Search Bar Component JavaScript

function toggleFilters(event) {
event.stopPropagation();
    const dropdown = document.getElementById('filterDropdown');
    const button = document.getElementById('filterButton');

    if (dropdown.style.display === 'none' || dropdown.style.display === '') {
// Position the dropdown relative to the button
const buttonRect = button.getBoundingClientRect();
    const viewportHeight = window.innerHeight;
    const dropdownHeight = 400; // max-height from CSS

// Calculate if dropdown should open upward or downward
const spaceBelow = viewportHeight - buttonRect.bottom;
    const spaceAbove = buttonRect.top;

    if (spaceBelow >= dropdownHeight || spaceBelow >= spaceAbove) {
// Open downward
dropdown.style.top = (buttonRect.bottom + window.scrollY + 8) + 'px';
} else {
  // Open upward
  dropdown.style.top = (buttonRect.top + window.scrollY - dropdownHeight - 8) + 'px';
  }

dropdown.style.left = (buttonRect.right - 240) + 'px'; // 240px = 15rem width
dropdown.style.display = 'block';
} else {
      dropdown.style.display = 'none';
  }
}

// Close dropdown when clicking outside
   document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('filterDropdown');
    const button = document.getElementById('filterButton');

    if (dropdown && button && !button.contains(e.target) && !dropdown.contains(e.target)) {
dropdown.style.display = 'none';
}
});

// Handle window resize
   window.addEventListener('resize', function() {
    const dropdown = document.getElementById('filterDropdown');
    if (dropdown) {
dropdown.style.display = 'none';
}
});
