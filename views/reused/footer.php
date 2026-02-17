</div>
    
    <footer class="bg-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>LinkedIn Clone</h5>
                    <p>A professional networking platform to connect with colleagues and opportunities.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo URLROOT; ?>" class="text-decoration-none">Home</a></li>
                        <li><a href="<?php echo URLROOT; ?>/pages/about" class="text-decoration-none">About Us</a></li>
                        <li><a href="<?php echo URLROOT; ?>/jobs" class="text-decoration-none">Find Jobs</a></li>
                        <li><a href="<?php echo URLROOT; ?>/pages/contact" class="text-decoration-none">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Connect With Us</h5>
                    <div class="d-flex gap-3 fs-4">
                        <a href="#" class="text-secondary"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-secondary"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-secondary"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-secondary"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> LinkedIn Clone. All rights reserved.</p>
                <p class="small mt-1">Version <?php echo APPVERSION; ?></p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (required for some components) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo assets('js/main.js'); ?>"></script>
    
    <!-- Initialize tooltips and popovers -->
    <script>
        // Activate tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Activate popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
        
        // Auto hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
        
        // Fetch unread notification count
        <?php if(isLoggedIn()): ?>
        function updateNotificationCount() {
            fetch('<?php echo URLROOT; ?>/notifications/count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    if (badge) {
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.classList.remove('d-none');
                        } else {
                            badge.classList.add('d-none');
                        }
                    }
                });
        }
        
        // Update notification count every 60 seconds
        updateNotificationCount();
        setInterval(updateNotificationCount, 60000);
        <?php endif; ?>
    </script>
</body>
</html>