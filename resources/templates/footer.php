    </main>

    <?php $base = defined('BASE_PATH') ? constant('BASE_PATH') : ''; ?>
    
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <div class="flex items-center">
                        <i class="fas fa-clinic-medical text-blue-600 text-xl mr-2"></i>
                        <span class="text-lg font-semibold text-gray-800">Unity Care Clinic V2</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Système de gestion médicale - Version 2.0</p>
                </div>
                
                <div class="flex items-center space-x-6">
                    <a href="<?php echo $base; ?>/privacy.php" class="text-sm text-gray-600 hover:text-gray-900">Confidentialité</a>
                    <a href="<?php echo $base; ?>/terms.php" class="text-sm text-gray-600 hover:text-gray-900">Conditions</a>
                    <a href="<?php echo $base; ?>/contact.php" class="text-sm text-gray-600 hover:text-gray-900">Contact</a>
                    <div class="text-sm text-gray-500">
                        &copy; <?php echo date('Y'); ?> Unity Care Clinic
                    </div>
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
                <p>Ceci est une démonstration. Pour des raisons de sécurité, certaines fonctionnalités sont simulées.</p>
                <p class="mt-1">Session active depuis: <?php 
                    if (isset($_SESSION['login_time'])) {
                        $duration = time() - $_SESSION['login_time'];
                        echo floor($duration / 60) . ' minutes';
                    }
                ?></p>
            </div>
        </div>
    </footer>

    <?php if (isset($isLoggedIn) && $isLoggedIn): ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- JavaScript -->
    <script src="<?php echo $base; ?>/assets/js/main.js"></script>
    <script>
    // Toggle user menu
    document.getElementById('user-menu-button')?.addEventListener('click', function() {
        const menu = document.getElementById('user-menu');
        menu.classList.toggle('hidden');
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('user-menu');
        const button = document.getElementById('user-menu-button');
        
        if (menu && !menu.contains(event.target) && button && !button.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
    
    // CSRF token for AJAX requests
    const csrfToken = "<?php echo htmlspecialchars($csrf_token); ?>";
    
    // Global AJAX setup
    const makeRequest = async (url, method = 'GET', data = null) => {
        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            }
        };
        
        if (data && (method === 'POST' || method === 'PUT')) {
            options.body = JSON.stringify(data);
        }
        
        try {
            const response = await fetch(url, options);
            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.error || 'Request failed');
            }
            
            return result;
        } catch (error) {
            console.error('Request error:', error);
            showNotification('Erreur: ' + error.message, 'error');
            throw error;
        }
    };
    
    // Notification system
    function showNotification(message, type = 'info') {
        const types = {
            'success': { bg: 'bg-green-100', border: 'border-green-400', text: 'text-green-700', icon: 'fa-check-circle' },
            'error': { bg: 'bg-red-100', border: 'border-red-400', text: 'text-red-700', icon: 'fa-exclamation-circle' },
            'warning': { bg: 'bg-yellow-100', border: 'border-yellow-400', text: 'text-yellow-700', icon: 'fa-exclamation-triangle' },
            'info': { bg: 'bg-blue-100', border: 'border-blue-400', text: 'text-blue-700', icon: 'fa-info-circle' }
        };
        
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 ${types[type].bg} border-l-4 ${types[type].border} p-4 z-50 max-w-sm rounded-r-lg shadow-lg`;
        notification.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas ${types[type].icon} ${types[type].text}"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm ${types[type].text}">${message}</p>
                </div>
                <div class="ml-auto pl-3">
                    <button onclick="this.parentElement.parentElement.remove()" class="inline-flex ${types[type].text} focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }
    
    // Session timeout warning
    let timeoutWarning;
    function checkSessionTimeout() {
        const warningTime = 5 * 60 * 1000; // 5 minutes before timeout
        const logoutTime = 30 * 60 * 1000; // 30 minutes timeout
        
        timeoutWarning = setTimeout(() => {
            if (confirm('Votre session va expirer dans 5 minutes. Souhaitez-vous rester connecté?')) {
                // Refresh session
                fetch('<?php echo $base; ?>/keep-alive.php')
                    .then(() => checkSessionTimeout());
            }
        }, logoutTime - warningTime);
    }
    
    <?php if (isset($isLoggedIn) && $isLoggedIn): ?>
    // Start session timeout check if logged in
    checkSessionTimeout();
    <?php endif; ?>
    
    // Clear timeout on page unload
    window.addEventListener('beforeunload', () => {
        if (timeoutWarning) {
            clearTimeout(timeoutWarning);
        }
    });
    </script>
</body>
</html>