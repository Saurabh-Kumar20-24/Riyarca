<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Desk</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #f4f6fb;
            min-height: 100vh;
            padding: 2rem 1rem;
            color: #1a1a2e;
        }

        .hd-container {
            max-width: 780px;
            margin: 0 auto;
        }

        .hd-hero {
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .hd-hero h1 {
            font-size: 26px;
            font-weight: 600;
            color: #1a1a2e;
        }

        .hd-hero p {
            font-size: 14px;
            color: #6b7280;
            margin-top: 4px;
        }

        .status-badge {
            display: inline-block;
            font-size: 12px;
            font-weight: 500;
            padding: 4px 12px;
            border-radius: 99px;
        }

        .badge-open {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-closed {
            background: #fee2e2;
            color: #991b1b;
        }

        .section-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 0.75rem;
        }

        .hd-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1rem 1.25rem;
            margin-bottom: 0.75rem;
        }

        .hd-card:last-child {
            margin-bottom: 0;
        }

        hr.hd-divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 1.75rem 0;
        }

        .step-row {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .step-num {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #1a1a2e;
            border: 1px solid #1a1a2e;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            color: #fff;
            flex-shrink: 0;
        }

        .step-content {
            flex: 1;
        }

        .step-title {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a2e;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
        }

        .contact-item {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px;
            text-align: center;
        }

        .contact-item .ci-icon {
            font-size: 20px;
            margin-bottom: 6px;
        }

        .contact-item .ci-label {
            font-size: 11px;
            color: #9ca3af;
            margin-bottom: 4px;
        }

        .contact-item .ci-val {
            font-size: 13px;
            font-weight: 500;
        }

        .contact-item a {
            color: #2563eb;
            text-decoration: none;
        }

        .contact-item a:hover {
            text-decoration: underline;
        }

        .address-row {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .addr-icon {
            width: 36px;
            height: 36px;
            background: #f3f4f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .addr-text {
            font-size: 13px;
            color: #4b5563;
            line-height: 1.7;
        }

        .addr-text strong {
            color: #1a1a2e;
            font-weight: 600;
            display: block;
            margin-bottom: 2px;
        }

        .hours-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
        }

        .hours-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .dot-open {
            background: #10b981;
        }

        .dot-closed {
            background: #ef4444;
        }

        .hours-text {
            font-size: 13px;
            color: #4b5563;
        }

        .hours-text strong {
            color: #1a1a2e;
        }

        .hours-divider {
            border: none;
            border-top: 1px solid #f3f4f6;
            margin: 4px 0;
        }

        .back-btn {
            margin-top: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #1a1a2e;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: opacity 0.2s;
            text-decoration: none;
        }

        .back-btn:hover {
            opacity: 0.85;
        }

        .float-cluster {
            position: fixed;
            bottom: 24px;
            right: 24px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 12px;
            z-index: 999;
        }

        .float-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .float-tooltip {
            background: #1a1a2e;
            color: #fff;
            font-size: 12px;
            font-weight: 500;
            padding: 5px 12px;
            border-radius: 20px;
            white-space: nowrap;
            opacity: 0;
            transform: translateX(8px);
            transition: opacity 0.2s, transform 0.2s;
            pointer-events: none;
        }

        .float-item:hover .float-tooltip {
            opacity: 1;
            transform: translateX(0);
        }

        .float-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.18);
            transition: transform 0.15s;
            text-decoration: none;
            flex-shrink: 0;
        }

        .float-btn:hover {
            transform: scale(1.1);
        }

        .fb-wa {
            background: #25d366;
            box-shadow: 0 4px 16px rgba(37, 211, 102, 0.4);
            animation: wa-pulse 2.5s infinite;
        }

        .fb-mail {
            background: #2563eb;
        }

        .fb-web {
            background: #7c3aed;
        }

        .fb-wa:hover {
            animation: none;
        }

        @keyframes wa-pulse {

            0%,
            100% {
                box-shadow: 0 4px 16px rgba(37, 211, 102, 0.4);
            }

            50% {
                box-shadow: 0 4px 24px rgba(37, 211, 102, 0.7);
            }
        }

        .need-help-banner {
            background: linear-gradient(135deg, #1a1a2e 0%, #2563eb 100%);
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 0.75rem;
        }

        .nhb-text {
            color: #fff;
        }

        .nhb-text h3 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .nhb-text p {
            font-size: 12px;
            opacity: 0.75;
        }

        .nhb-btn {
            padding: 8px 18px;
            background: #25d366;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            transition: opacity 0.2s;
        }

        .nhb-btn:hover {
            opacity: 0.88;
        }
    </style>
</head>

<body>

    <div class="hd-container">
        <div class="hd-hero">
            <div>
                <h1>Help Desk <span class="status-badge" id="statusBadge"></span></h1>
                <p>Employee support center — find guides, contacts, and quick answers below</p>
            </div>
        </div>
        <div class="section-label">Password reset — step by step</div>

        @php
        $steps = [
        ['title' => 'Go to the login page'],
        ['title' => "Click 'Forgot password?'"],
        ['title' => 'Enter your registered email'],
        ['title' => 'Check your inbox for the reset link'],
        ['title' => 'Create a new strong password'],
        ['title' => 'Log in with your new password'],
        ];
        @endphp

        @foreach($steps as $index => $step)
        <div class="hd-card">
            <div class="step-row">
                <div class="step-num">{{ $index + 1 }}</div>
                <div class="step-content">
                    <div class="step-title">{{ $step['title'] }}</div>
                </div>
            </div>
        </div>
        @endforeach

        <hr class="hd-divider">
        <div class="section-label">Contact us</div>
        <div class="contact-grid">

            <div class="contact-item">
                <div class="ci-icon">📞</div>
                <div class="ci-label">Phone</div>
                <div class="ci-val">
                    <a href="tel:{{ $data['phone'] }}">{{ $data['phone'] }}</a>
                </div>
            </div>

            <div class="contact-item">
                <div class="ci-icon">📧</div>
                <div class="ci-label">Email</div>
                <div class="ci-val">
                    <a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a>
                </div>
            </div>

            @if(!empty($data['whatsapp']))
            <div class="contact-item">
                <div class="ci-icon">💬</div>
                <div class="ci-label">WhatsApp</div>
                <div class="ci-val">
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $data['whatsapp']) }}" target="_blank">Chat with us</a>
                </div>
            </div>
            @endif

            <div class="contact-item">
                <div class="ci-icon">🌐</div>
                <div class="ci-label">Portal</div>
                <div class="ci-val">
                    <a href="https://{{ $data['website'] }}" target="_blank">{{ $data['website'] }}</a>
                </div>
            </div>

        </div>

        <hr class="hd-divider">
        <div class="section-label">Office address</div>
        <div class="hd-card">
            <div class="address-row">
                <div class="addr-icon">📍</div>
                <div class="addr-text">
                    <strong>Head Office</strong>
                    {{ $data['address'] }}
                </div>
            </div>
        </div>

        <hr class="hd-divider">
        <div class="section-label">Support hours</div>
        <div class="hd-card">
            <div class="hours-row">
                <div class="hours-dot dot-open"></div>
                <div class="hours-text">
                    <strong>{{ $data['timing_days'] }}</strong> &nbsp; {{ $data['timing_hours'] }}
                </div>
            </div>
            <hr class="hours-divider">
            <div class="hours-row">
                <div class="hours-dot dot-closed"></div>
                <div class="hours-text">
                    <strong>Saturday – Sunday</strong> &nbsp; Closed
                </div>
            </div>
        </div>

        <hr class="hd-divider">
        @if(!empty($data['whatsapp']))
        <div class="need-help-banner">
            <div class="nhb-text">
                <h3>Still stuck? We're here to help 👋</h3>
                <p>Chat with our support team instantly on WhatsApp</p>
            </div>
            <a class="nhb-btn" href="https://wa.me/{{ preg_replace('/\D/', '', $data['whatsapp']) }}" target="_blank">
                Chat on WhatsApp
            </a>
        </div>
        @endif
        <button class="back-btn" onclick="window.history.back()">
            ← Back
        </button>

    </div>
    <div class="float-cluster">

        @if(!empty($data['whatsapp']))
        <div class="float-item">
            <span class="float-tooltip">💬 WhatsApp Support</span>
            <a class="float-btn fb-wa"
                href="https://wa.me/{{ preg_replace('/\D/', '', $data['whatsapp']) }}"
                target="_blank">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="white">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z" />
                </svg>
            </a>
        </div>
        @endif

        <div class="float-item">
            <span class="float-tooltip">📧 Email Us</span>
            <a class="float-btn fb-mail" href="mailto:{{ $data['email'] }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="4" width="20" height="16" rx="2" />
                    <polyline points="2,4 12,13 22,4" />
                </svg>
            </a>
        </div>
        <div class="float-item">
            <span class="float-tooltip">🌐 Help Portal</span>
            <a class="float-btn fb-web" href="https://{{ $data['website'] }}" target="_blank">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                </svg>
            </a>
        </div>
    </div>
    <script>
        (function() {
            const badge = document.getElementById('statusBadge');
            const now = new Date();
            const day = now.getDay();
            const hour = now.getHours();
            const isOpen = day >= 1 && day <= 5 && hour >= 9 && hour < 18;
            badge.textContent = isOpen ? 'Open now' : 'Closed';
            badge.className = 'status-badge ' + (isOpen ? 'badge-open' : 'badge-closed');
        })();
    </script>
</body>

</html>