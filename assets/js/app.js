/**
 * InventiWhats JavaScript Application
 * Main application JavaScript file
 */

class InventiWhats {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupTooltips();
        this.setupFormValidations();
        this.autoHideAlerts();
    }

    setupEventListeners() {
        // Navbar toggler for mobile
        const navbarToggler = document.querySelector('.navbar-toggler');
        if (navbarToggler) {
            navbarToggler.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        }

        // Search functionality
        const searchForm = document.getElementById('searchForm');
        if (searchForm) {
            searchForm.addEventListener('submit', this.handleSearch.bind(this));
        }

        // Filter functionality
        const filterSelects = document.querySelectorAll('.filter-select');
        filterSelects.forEach(select => {
            select.addEventListener('change', this.handleFilter.bind(this));
        });

        // Table sorting
        const sortableHeaders = document.querySelectorAll('.sortable');
        sortableHeaders.forEach(header => {
            header.addEventListener('click', this.handleSort.bind(this));
            header.style.cursor = 'pointer';
        });
    }

    setupTooltips() {
        // Initialize Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    setupFormValidations() {
        // Custom form validation
        const forms = document.querySelectorAll('.needs-validation');
        
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });

        // Real-time validation for specific fields
        const emailInputs = document.querySelectorAll('input[type="email"]');
        emailInputs.forEach(input => {
            input.addEventListener('blur', this.validateEmail.bind(this, input));
        });

        const phoneInputs = document.querySelectorAll('input[type="tel"]');
        phoneInputs.forEach(input => {
            input.addEventListener('input', this.formatPhone.bind(this, input));
        });
    }

    autoHideAlerts() {
        // Auto-hide success alerts after 5 seconds
        const successAlerts = document.querySelectorAll('.alert-success');
        successAlerts.forEach(alert => {
            setTimeout(() => {
                if (alert && alert.parentNode) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    }

    handleSearch(event) {
        event.preventDefault();
        const searchTerm = event.target.search.value.trim();
        
        if (searchTerm.length < 2) {
            this.showAlert('Por favor ingrese al menos 2 caracteres para buscar', 'warning');
            return;
        }

        this.performSearch(searchTerm);
    }

    handleFilter(event) {
        const form = event.target.closest('form');
        if (form) {
            form.submit();
        }
    }

    handleSort(event) {
        const header = event.target.closest('.sortable');
        const table = header.closest('table');
        const column = header.dataset.column;
        const currentOrder = header.dataset.order || 'asc';
        const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';

        // Update header
        header.dataset.order = newOrder;
        
        // Update sort icon
        const icon = header.querySelector('i');
        if (icon) {
            icon.className = newOrder === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';
        }

        this.sortTable(table, column, newOrder);
    }

    performSearch(term) {
        // Show loading indicator
        this.showLoading('Buscando...');
        
        // Update URL with search parameters
        const url = new URL(window.location);
        url.searchParams.set('search', term);
        url.searchParams.set('page', '1');
        
        window.location.href = url.toString();
    }

    sortTable(table, column, order) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        rows.sort((a, b) => {
            const aValue = a.querySelector(`[data-column="${column}"]`)?.textContent.trim() || '';
            const bValue = b.querySelector(`[data-column="${column}"]`)?.textContent.trim() || '';

            if (order === 'asc') {
                return aValue.localeCompare(bValue, 'es', { numeric: true });
            } else {
                return bValue.localeCompare(aValue, 'es', { numeric: true });
            }
        });

        // Re-append sorted rows
        rows.forEach(row => tbody.appendChild(row));
    }

    validateEmail(input) {
        const email = input.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            this.setFieldError(input, 'Por favor ingrese un email válido');
        } else {
            this.clearFieldError(input);
        }
    }

    formatPhone(input) {
        let value = input.value.replace(/\D/g, '');
        
        if (value.length >= 10) {
            value = value.substring(0, 10);
            value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
        }
        
        input.value = value;
    }

    setFieldError(input, message) {
        input.classList.add('is-invalid');
        
        let feedback = input.parentNode.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            input.parentNode.appendChild(feedback);
        }
        feedback.textContent = message;
    }

    clearFieldError(input) {
        input.classList.remove('is-invalid');
        const feedback = input.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.remove();
        }
    }

    showAlert(message, type = 'info') {
        const alertsContainer = document.getElementById('alerts-container') || document.body;
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-info-circle me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        alertsContainer.insertAdjacentElement('afterbegin', alertDiv);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            if (alertDiv && alertDiv.parentNode) {
                const bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            }
        }, 5000);
    }

    showLoading(message = 'Cargando...') {
        const loadingDiv = document.createElement('div');
        loadingDiv.id = 'loading-indicator';
        loadingDiv.className = 'position-fixed top-50 start-50 translate-middle bg-white p-4 rounded shadow';
        loadingDiv.style.zIndex = '9999';
        loadingDiv.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary mb-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>${message}</div>
            </div>
        `;
        
        document.body.appendChild(loadingDiv);
    }

    hideLoading() {
        const loadingDiv = document.getElementById('loading-indicator');
        if (loadingDiv) {
            loadingDiv.remove();
        }
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat('es-MX', {
            style: 'currency',
            currency: 'MXN'
        }).format(amount);
    }

    formatDate(date) {
        return new Intl.DateTimeFormat('es-MX', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }).format(new Date(date));
    }

    copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            this.showAlert('Copiado al portapapeles', 'success');
        }).catch(() => {
            this.showAlert('Error al copiar al portapapeles', 'danger');
        });
    }
}

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.inventiwhat = new InventiWhats();
});

// Utility functions for global use
window.showAlert = function(message, type = 'info') {
    if (window.inventiwhat) {
        window.inventiwhat.showAlert(message, type);
    }
};

window.showLoading = function(message) {
    if (window.inventiwhat) {
        window.inventiwhat.showLoading(message);
    }
};

window.hideLoading = function() {
    if (window.inventiwhat) {
        window.inventiwhat.hideLoading();
    }
};

// AJAX helper function
window.ajaxRequest = function(url, options = {}) {
    const defaultOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };

    const finalOptions = { ...defaultOptions, ...options };
    
    return fetch(url, finalOptions)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .catch(error => {
            console.error('AJAX Error:', error);
            showAlert('Error de conexión', 'danger');
            throw error;
        });
};