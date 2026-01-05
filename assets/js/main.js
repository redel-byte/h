/**
 * Unity Care Clinic V2 - Main JavaScript
 */

class UnityCareClinic {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.setupForms();
        this.setupModals();
        this.setupNotifications();
    }
    
    setupEventListeners() {
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
        
        // Sidebar toggle for mobile
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });
        }
        
        // Close modals on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });
    }
    
    setupForms() {
        // Form validation
        document.querySelectorAll('form[data-validate]').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });
        });
        
        // Real-time validation
        document.querySelectorAll('input[data-validate]').forEach(input => {
            input.addEventListener('blur', () => {
                this.validateField(input);
            });
        });
    }
    
    validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    validateField(field) {
        const value = field.value.trim();
        const type = field.type;
        const name = field.name;
        const errorElement = field.nextElementSibling?.classList.contains('error-message') 
            ? field.nextElementSibling 
            : document.getElementById(`${name}-error`);
        
        // Remove previous error
        field.classList.remove('error-input');
        if (errorElement) {
            errorElement.remove();
        }
        
        // Validation rules
        let isValid = true;
        let errorMessage = '';
        
        if (field.required && !value) {
            isValid = false;
            errorMessage = 'Ce champ est obligatoire';
        } else if (type === 'email' && value && !this.isValidEmail(value)) {
            isValid = false;
            errorMessage = 'Email invalide';
        } else if (type === 'tel' && value && !this.isValidPhone(value)) {
            isValid = false;
            errorMessage = 'Numéro de téléphone invalide';
        } else if (field.min && parseInt(value) < parseInt(field.min)) {
            isValid = false;
            errorMessage = `La valeur minimale est ${field.min}`;
        } else if (field.max && parseInt(value) > parseInt(field.max)) {
            isValid = false;
            errorMessage = `La valeur maximale est ${field.max}`;
        }
        
        if (!isValid) {
            field.classList.add('error-input');
            this.showFieldError(field, errorMessage);
        }
        
        return isValid;
    }
    
    isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    isValidPhone(phone) {
        const re = /^[+]?[(]?[0-9]{3}[)]?[-\s.]?[0-9]{3}[-\s.]?[0-9]{4,6}$/;
        return re.test(phone);
    }
    
    showFieldError(field, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        errorDiv.id = `${field.name}-error`;
        
        field.parentNode.insertBefore(errorDiv, field.nextSibling);
    }
    
    setupModals() {
        // Close modal when clicking outside
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay')) {
                this.closeModal(e.target.closest('.modal'));
            }
        });
        
        // Close buttons
        document.querySelectorAll('.modal-close').forEach(btn => {
            btn.addEventListener('click', () => {
                this.closeModal(btn.closest('.modal'));
            });
        });
    }
    
    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }
    }
    
    closeModal(modal) {
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }
    }
    
    closeAllModals() {
        document.querySelectorAll('.modal').forEach(modal => {
            this.closeModal(modal);
        });
    }
    
    setupNotifications() {
        // Auto-dismiss success messages
        const successMessages = document.querySelectorAll('.alert-success');
        successMessages.forEach(message => {
            setTimeout(() => {
                message.style.opacity = '0';
                setTimeout(() => message.remove(), 300);
            }, 5000);
        });
    }
    
    // AJAX helper methods
    async fetchData(url, options = {}) {
        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    ...options.headers
                },
                ...options
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return await response.json();
            } else {
                return await response.text();
            }
        } catch (error) {
            console.error('Fetch error:', error);
            this.showNotification('Erreur de connexion au serveur', 'error');
            throw error;
        }
    }
    
    showNotification(message, type = 'info', duration = 5000) {
        // Implementation as shown in footer.php
        window.showNotification?.(message, type) || alert(message);
    }
    
    // Date/time helpers
    formatDate(date) {
        const d = new Date(date);
        return d.toLocaleDateString('fr-FR');
    }
    
    formatDateTime(date) {
        const d = new Date(date);
        return d.toLocaleString('fr-FR');
    }
    
    // Utility: Debounce function
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.app = new UnityCareClinic();
});