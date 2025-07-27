/**
 * Responsive Sidebar JavaScript
 * Compatible with AdminLTE and custom responsive design
 */

class ResponsiveSidebar {
    constructor() {
        this.mobileToggle = document.getElementById('mobileToggle');
        this.sidebarClose = document.getElementById('sidebarClose');
        this.mainSidebar = document.getElementById('mainSidebar');
        this.sidebarOverlay = document.getElementById('sidebarOverlay');
        this.body = document.body;
        
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.setActiveMenuItem();
        this.handleResize();
        this.fixInitialState();
    }
    
    fixInitialState() {
        // Pastikan sidebar dalam state yang benar saat load
        if (this.isMobile()) {
            if (this.mainSidebar) {
                this.mainSidebar.classList.remove('active');
                this.mainSidebar.style.transform = 'translateX(-100%)';
            }
            if (this.sidebarOverlay) {
                this.sidebarOverlay.classList.remove('active');
            }
        } else {
            if (this.mainSidebar) {
                this.mainSidebar.classList.remove('active');
                this.mainSidebar.style.transform = '';
            }
        }
    }
    
    bindEvents() {
        // Mobile toggle click - pastikan event listener terpasang
        if (this.mobileToggle) {
            this.mobileToggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                console.log('Mobile toggle clicked'); // Debug log
                this.openSidebar();
            });
            
            // Debug: pastikan element terdeteksi
            console.log('Mobile toggle found:', this.mobileToggle);
        } else {
            console.warn('Mobile toggle not found');
        }
        
        // Close button click
        if (this.sidebarClose) {
            this.sidebarClose.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.closeSidebar();
            });
        }
        
        // Overlay click
        if (this.sidebarOverlay) {
            this.sidebarOverlay.addEventListener('click', (e) => {
                e.stopPropagation();
                this.closeSidebar();
            });
        }
        
        // Close sidebar when clicking on menu item (mobile)
        const menuLinks = document.querySelectorAll('.sidebar-menu a');
        menuLinks.forEach((link) => {
            link.addEventListener('click', () => {
                if (this.isMobile()) {
                    setTimeout(() => this.closeSidebar(), 300);
                }
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', () => {
            this.handleResize();
        });
        
        // Handle escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeSidebar();
            }
        });
        
        // Handle swipe gestures on mobile
        this.handleSwipeGestures();
    }
    
    openSidebar() {
        console.log('Opening sidebar'); // Debug log
        
        if (this.mainSidebar) {
            this.mainSidebar.classList.add('active');
            this.mainSidebar.style.transform = 'translateX(0)';
        }
        if (this.sidebarOverlay) {
            this.sidebarOverlay.classList.add('active');
            this.sidebarOverlay.style.display = 'block';
        }
        
        // Prevent body scroll on mobile
        if (this.isMobile()) {
            this.body.style.overflow = 'hidden';
        }
    }
    
    closeSidebar() {
        console.log('Closing sidebar'); // Debug log
        
        if (this.mainSidebar) {
            this.mainSidebar.classList.remove('active');
            if (this.isMobile()) {
                this.mainSidebar.style.transform = 'translateX(-100%)';
            }
        }
        if (this.sidebarOverlay) {
            this.sidebarOverlay.classList.remove('active');
            setTimeout(() => {
                if (this.sidebarOverlay) {
                    this.sidebarOverlay.style.display = 'none';
                }
            }, 200);
        }
        
        // Restore body scroll
        this.body.style.overflow = '';
    }
    
    setActiveMenuItem() {
        const currentUrl = window.location.href;
        const menuItems = document.querySelectorAll('.sidebar-menu li a');
        
        // Remove all active classes first
        document.querySelectorAll('.sidebar-menu li').forEach((item) => {
            item.classList.remove('active');
        });
        
        // Set active based on current URL
        menuItems.forEach((item) => {
            if (item.href === currentUrl) {
                item.parentElement.classList.add('active');
            }
        });
        
        // If no exact match, try to match based on route name
        const currentPath = window.location.pathname;
        menuItems.forEach((item) => {
            const itemPath = new URL(item.href).pathname;
            if (currentPath.startsWith(itemPath) && itemPath !== '/') {
                item.parentElement.classList.add('active');
            }
        });
    }
    
    handleResize() {
        if (!this.isMobile()) {
            this.closeSidebar();
            if (this.mainSidebar) {
                this.mainSidebar.style.transform = '';
            }
        }
    }
    
    isMobile() {
        return window.innerWidth <= 768;
    }
    
    handleSwipeGestures() {
        let startX = 0;
        let startY = 0;
        let endX = 0;
        let endY = 0;
        
        document.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        });
        
        document.addEventListener('touchend', (e) => {
            endX = e.changedTouches[0].clientX;
            endY = e.changedTouches[0].clientY;
            
            const deltaX = endX - startX;
            const deltaY = endY - startY;
            
            // Check if it's a horizontal swipe
            if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > 50) {
                if (deltaX > 0 && startX < 50) {
                    // Swipe right from left edge - open sidebar
                    this.openSidebar();
                } else if (deltaX < 0 && this.mainSidebar && this.mainSidebar.classList.contains('active')) {
                    // Swipe left when sidebar is open - close sidebar
                    this.closeSidebar();
                }
            }
        });
    }
    
    // Public methods for external use
    toggle() {
        if (this.mainSidebar && this.mainSidebar.classList.contains('active')) {
            this.closeSidebar();
        } else {
            this.openSidebar();
        }
    }
    
    isOpen() {
        return this.mainSidebar && this.mainSidebar.classList.contains('active');
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Create global instance
    window.responsiveSidebar = new ResponsiveSidebar();
    
    // Add smooth scrolling to menu items
    const menuLinks = document.querySelectorAll('.sidebar-menu a[href^="#"]');
    menuLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Add loading states to menu items
    const allMenuLinks = document.querySelectorAll('.sidebar-menu a:not([href^="#"])');
    allMenuLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Add loading class
            this.classList.add('loading');
            
            // Create loading spinner
            const spinner = document.createElement('i');
            spinner.className = 'fa fa-spinner fa-spin';
            spinner.style.marginLeft = '10px';
            
            // Add spinner if not already present
            if (!this.querySelector('.fa-spinner')) {
                this.appendChild(spinner);
            }
            
            // Remove loading state after navigation (fallback)
            setTimeout(() => {
                this.classList.remove('loading');
                const existingSpinner = this.querySelector('.fa-spinner');
                if (existingSpinner) {
                    existingSpinner.remove();
                }
            }, 3000);
        });
    });
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ResponsiveSidebar;
}
