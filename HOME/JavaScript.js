// Add your own custom JavaScript here

// Disable form submission on Enter key press
document.addEventListener('keydown', function(event) {
  if (event.keyCode === 13) {
    event.preventDefault();
  }
});

// Show loading spinner while waiting for response from API
function showLoadingSpinner() {
  var spinner = document.getElementById('loading-spinner');
  spinner.style.display = 'block';
}

// Hide loading spinner after API response is received
function hideLoadingSpinner() {
  var spinner = document.getElementById('loading-spinner');
  spinner.style.display = 'none';
}
