{{-- resources/views/layouts/partials/notification-panel.blade.php --}}
{{-- Replace your existing .icon-btn bell in the topbar with this --}}

<div class="notif-wrapper" id="notifWrapper">

    {{-- Bell button --}}
    <button class="icon-btn notif-bell" id="notifBell" aria-label="Notifications" title="Notifications">
        <i class="bi bi-bell"></i>
        <span class="badge notif-badge" id="notifBadge" style="display:none;"></span>
    </button>

    {{-- Dropdown Panel --}}
    <div class="notif-panel" id="notifPanel">
        <div class="notif-panel-header">
            <span class="notif-panel-title">Notifications</span>
            <button class="notif-mark-all" id="notifMarkAll">Mark all read</button>
        </div>

        <div class="notif-list" id="notifList">
            <div class="notif-empty" id="notifEmpty" style="display:none;">
                <i class="bi bi-check2-all"></i>
                <span>You're all caught up!</span>
            </div>
        </div>
    </div>
</div>

{{-- Birthday Card Modal --}}
<div class="bday-overlay" id="bdayOverlay" style="display:none;" role="dialog" aria-modal="true">
    <div class="bday-card" id="bdayCard">
        <div class="bday-confetti" id="confettiContainer"></div>

        <div class="bday-inner">
            <div class="bday-emoji">🎂</div>
            <h2 class="bday-heading">Happy Birthday,<br><span id="bdayName"></span>!</h2>
            <p class="bday-sub">Wishing you a wonderful day full of joy, laughter, and success. The whole team celebrates with you! 🎉</p>
            <button class="bday-btn" id="bdayClose">Thank You! 🎊</button>
        </div>
    </div>
</div>