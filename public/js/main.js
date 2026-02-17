// Main JavaScript for LinkedIn Clone

document.addEventListener('DOMContentLoaded', function() {
    // Post like functionality
    const likeButtons = document.querySelectorAll('.post-like-btn');
    if (likeButtons) {
        likeButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const postId = this.dataset.postId;
                const likeCount = document.querySelector(`#like-count-${postId}`);
                const icon = this.querySelector('i');
                
                // Make AJAX request to like/unlike post
                fetch(`${window.location.origin}/linkedin-clone/posts/like/${postId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Update UI based on action (liked or unliked)
                        if (data.action === 'liked') {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            icon.classList.add('text-primary');
                            if (likeCount) {
                                likeCount.textContent = parseInt(likeCount.textContent) + 1;
                            }
                        } else {
                            icon.classList.remove('fas');
                            icon.classList.remove('text-primary');
                            icon.classList.add('far');
                            if (likeCount) {
                                likeCount.textContent = parseInt(likeCount.textContent) - 1;
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    }

    // Comment form submission
    const commentForms = document.querySelectorAll('.comment-form');
    if (commentForms) {
        commentForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const commentInput = this.querySelector('.comment-input');
                if (!commentInput.value.trim()) {
                    e.preventDefault();
                    alert('Please enter a comment.');
                }
            });
        });
    }

    // Connection request buttons
    const connectionButtons = document.querySelectorAll('.connection-btn');
    if (connectionButtons) {
        connectionButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Change button state to pending/requested
                this.classList.remove('btn-primary');
                this.classList.add('btn-secondary');
                this.innerHTML = '<i class="fas fa-clock"></i> Pending';
                this.disabled = true;
            });
        });
    }

    // Notification mark as read buttons
    const markAsReadButtons = document.querySelectorAll('.mark-as-read-btn');
    if (markAsReadButtons) {
        markAsReadButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const notificationId = this.dataset.notificationId;
                const notificationItem = document.querySelector(`#notification-${notificationId}`);
                
                // Make AJAX request to mark notification as read
                fetch(`${window.location.origin}/linkedin-clone/notifications/markAsRead/${notificationId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI
                        notificationItem.classList.remove('notification-unread');
                        this.style.display = 'none';
                        
                        // Update badge count
                        const badge = document.getElementById('notification-badge');
                        if (badge) {
                            const count = parseInt(badge.textContent) - 1;
                            if (count > 0) {
                                badge.textContent = count;
                            } else {
                                badge.classList.add('d-none');
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    }

    // Mark all notifications as read button
    const markAllAsReadButton = document.querySelector('#mark-all-as-read-btn');
    if (markAllAsReadButton) {
        markAllAsReadButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Make AJAX request to mark all notifications as read
            fetch(`${window.location.origin}/linkedin-clone/notifications/markAllAsRead`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    document.querySelectorAll('.notification-unread').forEach(item => {
                        item.classList.remove('notification-unread');
                    });
                    
                    document.querySelectorAll('.mark-as-read-btn').forEach(btn => {
                        btn.style.display = 'none';
                    });
                    
                    // Update badge count
                    const badge = document.getElementById('notification-badge');
                    if (badge) {
                        badge.classList.add('d-none');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }

    // Premium subscription modal
    const premiumModal = document.getElementById('premiumModal');
    if (premiumModal) {
        const modalInstance = new bootstrap.Modal(premiumModal);
        
        // Show modal when premium feature buttons are clicked
        const premiumFeatureButtons = document.querySelectorAll('.premium-feature-btn');
        premiumFeatureButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                modalInstance.show();
            });
        });
    }

    // Profile picture upload preview
    const profilePictureInput = document.getElementById('profile-picture-input');
    const profilePicturePreview = document.getElementById('profile-picture-preview');
    
    if (profilePictureInput && profilePicturePreview) {
        profilePictureInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePicturePreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Job filter form
    const jobFilterForm = document.getElementById('job-filter-form');
    if (jobFilterForm) {
        // Clear filters button
        const clearFiltersBtn = document.getElementById('clear-filters-btn');
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const inputs = jobFilterForm.querySelectorAll('input, select');
                inputs.forEach(input => {
                    if (input.type === 'checkbox') {
                        input.checked = false;
                    } else {
                        input.value = '';
                    }
                });
                jobFilterForm.submit();
            });
        }
    }

    // Group post form toggle
    const groupPostToggleBtn = document.getElementById('group-post-toggle-btn');
    const groupPostForm = document.getElementById('group-post-form');
    
    if (groupPostToggleBtn && groupPostForm) {
        groupPostToggleBtn.addEventListener('click', function() {
            groupPostForm.classList.toggle('d-none');
            // Scroll to the form if it's now visible
            if (!groupPostForm.classList.contains('d-none')) {
                groupPostForm.scrollIntoView({ behavior: 'smooth' });
                // Focus on the input field
                const textArea = groupPostForm.querySelector('textarea');
                if (textArea) {
                    textArea.focus();
                }
            }
        });
    }
    
    // Post character counter
    const postTextareas = document.querySelectorAll('.post-textarea');
    if (postTextareas) {
        postTextareas.forEach(textarea => {
            const maxLength = 1000;
            const counterElement = document.createElement('div');
            counterElement.className = 'text-muted small text-end mt-1';
            counterElement.textContent = `0/${maxLength} characters`;
            textarea.parentNode.insertBefore(counterElement, textarea.nextSibling);
            
            textarea.addEventListener('input', function() {
                const currentLength = this.value.length;
                counterElement.textContent = `${currentLength}/${maxLength} characters`;
                
                if (currentLength > maxLength) {
                    counterElement.classList.add('text-danger');
                    this.value = this.value.substring(0, maxLength);
                    counterElement.textContent = `${maxLength}/${maxLength} characters`;
                } else {
                    counterElement.classList.remove('text-danger');
                }
            });
        });
    }
    
    // Initialize custom dropdowns and popovers
    initializeCustomComponents();
});

// Function to initialize custom UI components
function initializeCustomComponents() {
    // Initialize any custom dropdowns
    const customDropdowns = document.querySelectorAll('.custom-dropdown-toggle');
    if (customDropdowns) {
        customDropdowns.forEach(dropdown => {
            dropdown.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-target');
                const targetDropdown = document.getElementById(targetId);
                if (targetDropdown) {
                    targetDropdown.classList.toggle('show');
                }
            });
        });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.matches('.custom-dropdown-toggle')) {
                const dropdowns = document.querySelectorAll('.custom-dropdown-menu.show');
                dropdowns.forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });
    }
    
    // Initialize skill endorsement buttons
    const endorseButtons = document.querySelectorAll('.endorse-btn');
    if (endorseButtons) {
        endorseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const skillId = this.dataset.skillId;
                const endorseCount = document.querySelector(`#endorse-count-${skillId}`);
                
                if (this.classList.contains('endorsed')) {
                    // Unendorse
                    this.classList.remove('endorsed');
                    this.innerHTML = '<i class="far fa-thumbs-up"></i> Endorse';
                    if (endorseCount) {
                        endorseCount.textContent = parseInt(endorseCount.textContent) - 1;
                    }
                } else {
                    // Endorse
                    this.classList.add('endorsed');
                    this.innerHTML = '<i class="fas fa-thumbs-up"></i> Endorsed';
                    if (endorseCount) {
                        endorseCount.textContent = parseInt(endorseCount.textContent) + 1;
                    }
                }
            });
        });
    }
}

// Function to confirm deletion
function confirmDelete(formId, message) {
    if (confirm(message || 'Are you sure you want to delete this item?')) {
        document.getElementById(formId).submit();
    }
    return false;
}

// Function to toggle password visibility
function togglePasswordVisibility(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}