
// Notification system: bell panel + birthday card

(function () {
    'use strict';

    const POLL_INTERVAL = 30_000; // refresh every 30 s

    //  DOM refs
    const bell       = document.getElementById('notifBell');
    const panel      = document.getElementById('notifPanel');
    const badge      = document.getElementById('notifBadge');
    const list       = document.getElementById('notifList');
    const emptyState = document.getElementById('notifEmpty');
    const markAllBtn = document.getElementById('notifMarkAll');

    const bdayOverlay = document.getElementById('bdayOverlay');
    const bdayName    = document.getElementById('bdayName');
    const bdayClose   = document.getElementById('bdayClose');
    const confettiEl  = document.getElementById('confettiContainer');

    if (!bell) return; // guard if partial not included

    //  Helpers 

    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    }

    function post(url, body = {}) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
            },
            body: JSON.stringify(body),
        });
    }

    function get(url) {
        return fetch(url, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken() },
        });
    }

    //  Panel open/close 
    function openPanel() {
        panel.classList.add('open');
        bell.setAttribute('aria-expanded', 'true');
        loadNotifications();
    }

    function closePanel() {
        panel.classList.remove('open');
        bell.setAttribute('aria-expanded', 'false');
    }

    bell.addEventListener('click', (e) => {
        e.stopPropagation();
        panel.classList.contains('open') ? closePanel() : openPanel();
    });

    document.addEventListener('click', (e) => {
        if (!panel.contains(e.target) && e.target !== bell) closePanel();
    });

    // Load notifications 

    async function loadNotifications() {
        try {
            const res  = await get('/notifications');
            const data = await res.json();
            renderNotifications(data.notifications, data.unread_count);
        } catch (err) {
            console.error('Notification fetch error:', err);
        }
    }

    function renderNotifications(notifications, count) {
        // Badge
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : String(count); // ← String() to force text
            badge.style.display = 'flex';  // ← flex instead of block
            badge.style.visibility = 'visible';
        } else {
            badge.style.display = 'none';
        }

        // Clear existing items (keep empty state)
        list.querySelectorAll('.notif-item').forEach(el => el.remove());

        if (!notifications.length) {
            emptyState.style.display = 'flex';
            return;
        }

        emptyState.style.display = 'none';

        notifications.forEach(n => {
            const item = document.createElement('div');
            item.className = 'notif-item';
            item.dataset.id = n.id;
            item.innerHTML = `
                <div class="notif-icon ${n.color}"><i class="bi ${n.icon}"></i></div>
                <div class="notif-body">
                    <div class="notif-title">${escHtml(n.title)}</div>
                    <div class="notif-message">${escHtml(n.message)}</div>
                    <div class="notif-time">${escHtml(n.created_at)}</div>
                </div>
                <div class="notif-dot"></div>
            `;

            item.addEventListener('click', () => markRead(n.id, item));
            list.insertBefore(item, emptyState);
        });
    }

    function escHtml(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    //  Mark read 

    async function markRead(id, itemEl) {
        itemEl.classList.add('is-read');
        await post(`/notifications/${id}/read`);
        refreshBadge();
    }

    async function refreshBadge() {
        try {
            const res  = await get('/notifications');
            const data = await res.json();
            const count = data.unread_count;
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = count > 0 ? 'block' : 'none';
        } catch {}
    }

    markAllBtn?.addEventListener('click', async () => {
        list.querySelectorAll('.notif-item').forEach(el => el.classList.add('is-read'));
        badge.style.display = 'none';
        await post('/notifications/read-all');
    });

    //  Polling 

    setInterval(async () => {
        try {
            const res  = await get('/notifications');
            const data = await res.json();
            const count = data.unread_count;
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : String(count); // ← String()
                badge.style.display = 'flex';  // ← flex instead of block
                badge.style.visibility = 'visible';
            } else {
                badge.style.display = 'none';
            }
        } catch {}
    }, POLL_INTERVAL);

    // Birthday Card

    async function checkBirthdayCard() {
        try {
            const res  = await get('/notifications/birthday-card');
            const data = await res.json();
            if (data.show_card) showBirthdayCard(data.name);
        } catch {}
    }

    function showBirthdayCard(name) {
        bdayName.textContent = name;
        bdayOverlay.style.display = 'flex';
        spawnConfetti();
    }

    bdayClose?.addEventListener('click', () => {
        bdayOverlay.style.animation = 'none';
        bdayOverlay.style.opacity   = '0';
        bdayOverlay.style.transition = 'opacity .3s';
        setTimeout(() => { bdayOverlay.style.display = 'none'; }, 300);
    });

    //  Confetti

    const CONFETTI_COLORS = ['#f0abfc','#a78bfa','#fbbf24','#34d399','#60a5fa','#fb923c','#f472b6'];

    function spawnConfetti() {
        if (!confettiEl) return;
        confettiEl.innerHTML = '';
        for (let i = 0; i < 55; i++) {
            const piece = document.createElement('div');
            piece.className = 'confetti-piece';
            piece.style.cssText = `
                left:             ${Math.random() * 100}%;
                background:       ${CONFETTI_COLORS[Math.floor(Math.random() * CONFETTI_COLORS.length)]};
                width:            ${4 + Math.random() * 8}px;
                height:           ${4 + Math.random() * 8}px;
                border-radius:    ${Math.random() > .5 ? '50%' : '2px'};
                animation-delay:  ${Math.random() * 3}s;
                animation-duration:${2.5 + Math.random() * 2.5}s;
            `;
            confettiEl.appendChild(piece);
        }
    }

    // Init 

    loadNotifications();
    checkBirthdayCard();

})();