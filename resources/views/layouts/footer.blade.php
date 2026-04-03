</main>
        {{-- ── end main-content ── --}}

        {{-- ── Footer (fixed to bottom, inside page-wrapper) ── --}}
        <footer class="app-footer">
            <span>&copy; {{ date('Y') }} <strong>SeoMagics</strong>. All rights reserved.</span>
            <span class="footer-right">
                Built with <i class="bi bi-heart-fill" style="color:var(--danger);font-size:11px;"></i> using Laravel
            </span>
        </footer>

    </div>
    {{-- ── end page-wrapper ── --}}

</div>
{{-- ── end app-shell ── --}}

{{-- Bootstrap JS (for collapse / dropdowns in sidebar) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- Your existing JS --}}
<script src="{{ asset('assets/js/misc.js') }}"></script>

{{-- Sidebar toggle script --}}
<script>
    const sidebar        = document.getElementById('sidebar');
    const overlay        = document.getElementById('sidebarOverlay');
    const toggleBtn      = document.getElementById('sidebarToggle');

    function openSidebar()  { sidebar.classList.add('open');  overlay.classList.add('open'); }
    function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('open'); }

    toggleBtn?.addEventListener('click', () => {
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });
    overlay?.addEventListener('click', closeSidebar);

    // Auto-expand sidebar submenu if a child link is active
    document.querySelectorAll('.sub-menu .nav-link.active').forEach(link => {
        const collapse = link.closest('.collapse');
        if (collapse) {
            collapse.classList.add('show');
            const toggle = document.querySelector(`[href="#${collapse.id}"]`);
            if (toggle) toggle.setAttribute('aria-expanded', 'true');
        }
    });
</script>

@stack('scripts')

<style>
    /* ─── Main Content ───────────────────────────────────────── */
    .main-content {
        flex: 1;
        overflow-y: auto;
        padding: calc(var(--navbar-h) + 1.5rem) 1.5rem 1rem;
    }

    /* ─── Footer ─────────────────────────────────────────────── */
    .app-footer {
        flex-shrink: 0;
        height: 48px;
        background: var(--surface);
        border-top: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1.5rem;
        font-size: 12px;
        color: var(--muted);
        /* Footer stays at bottom, never scrolls — it's outside .main-content */
    }
    .footer-right { display: flex; align-items: center; gap: 4px; }

    @media (max-width: 480px) {
        .footer-right { display: none; }
    }
</style>

</body>
</html>