// resources/js/app.js
import './bootstrap';

// ═══════════════════════════════════════════════════
// 1. Theme Management (Light / Dark Mode)
// ═══════════════════════════════════════════════════
window.toggleTheme = function() {
    const isDark = document.documentElement.classList.contains('dark');
    if (isDark) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }
    window.dispatchEvent(new CustomEvent('theme-changed', { detail: { isDark: !isDark } }));
};

// ═══════════════════════════════════════════════════
// 2. Mobile & Responsive Sidebar Management
// ═══════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const closeBtn = document.getElementById('sidebarCloseBtn');

    function openSidebar() {
        if (!sidebar) return;
        sidebar.classList.remove('translate-x-full');
        sidebar.classList.add('translate-x-0');
        if (backdrop) {
            backdrop.classList.remove('hidden');
            setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
        }
    }

    function closeSidebar() {
        if (!sidebar) return;
        if (window.innerWidth < 1024) {
            sidebar.classList.add('translate-x-full');
            sidebar.classList.remove('translate-x-0');
            if (backdrop) {
                backdrop.classList.add('opacity-0');
                setTimeout(() => backdrop.classList.add('hidden'), 300);
            }
        }
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (sidebar.classList.contains('translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });
    }

    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (backdrop) backdrop.addEventListener('click', closeSidebar);

    // Initialize Lucide icons if available
    if (window.lucide) {
        window.lucide.createIcons();
    }
});

// ═══════════════════════════════════════════════════
// 3. Central Modals & Dropdowns System (Native & Pure)
// ═══════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
    // Dropdown toggles
    document.addEventListener('click', (e) => {
        const toggle = e.target.closest('[data-bs-toggle="dropdown"]');
        if (toggle) {
            e.preventDefault();
            e.stopPropagation();
            const parent = toggle.closest('.dropdown');
            const menu = parent ? parent.querySelector('.dropdown-menu') : null;
            if (menu) {
                const isOpen = !menu.classList.contains('hidden');
                // Close all other dropdowns
                document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
                if (!isOpen) {
                    menu.classList.remove('hidden');
                }
            }
            return;
        }

        // Close dropdown when clicking outside
        if (!e.target.closest('.dropdown-menu')) {
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
        }
    });

    // Modal open toggles
    document.addEventListener('click', (e) => {
        const toggle = e.target.closest('[data-bs-toggle="modal"]');
        if (toggle) {
            e.preventDefault();
            const targetId = toggle.getAttribute('data-bs-target') || toggle.getAttribute('href');
            if (targetId) {
                const modal = document.querySelector(targetId);
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                }
            }
        }

        // Modal close buttons
        const dismiss = e.target.closest('[data-bs-dismiss="modal"], .btn-close, .modal-close-btn');
        if (dismiss) {
            e.preventDefault();
            const modal = dismiss.closest('.modal, .modal-overlay, [role="dialog"]');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }
        }
    });
});

// ═══════════════════════════════════════════════════
// 4. Central Toast System (Matching tem ToastContext)
// ═══════════════════════════════════════════════════
window.showToast = function(type = 'success', title = '', message = '') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed bottom-5 left-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `pointer-events-auto p-4 rounded-2xl shadow-xl border flex items-start gap-3 transform transition-all duration-300 translate-y-4 opacity-0 ${
        type === 'success' ? 'bg-emerald-50 dark:bg-emerald-950/80 border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-100' :
        type === 'error'   ? 'bg-rose-50 dark:bg-rose-950/80 border-rose-200 dark:border-rose-800 text-rose-900 dark:text-rose-100' :
        type === 'warning' ? 'bg-amber-50 dark:bg-amber-950/80 border-amber-200 dark:border-amber-800 text-amber-900 dark:text-amber-100' :
                             'bg-indigo-50 dark:bg-indigo-950/80 border-indigo-200 dark:border-indigo-800 text-indigo-900 dark:text-indigo-100'
    }`;

    toast.innerHTML = `
        <div class="shrink-0 mt-0.5 font-bold">
            ${type === 'success' ? '✓' : type === 'error' ? '✕' : '!'}
        </div>
        <div class="flex-1">
            ${title ? `<div class="font-bold text-sm leading-tight mb-0.5">${title}</div>` : ''}
            <div class="text-xs opacity-90 leading-relaxed">${message}</div>
        </div>
    `;

    container.appendChild(toast);
    setTimeout(() => {
        toast.classList.remove('translate-y-4', 'opacity-0');
    }, 20);

    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-4');
        setTimeout(() => toast.remove(), 300);
    }, 4500);
};
