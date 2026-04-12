<!DOCTYPE html>
<html>
<head>
<title>Help Desk</title>

<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #1E5ED9, #6A2FE0, #D92BBF);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
.help-card {
    width: 430px;
    background: #fff;
    padding: 30px 25px;
    border-radius: 18px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    animation: fadeIn 0.4s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px);}
    to { opacity: 1; transform: translateY(0);}
}

.help-header {
    text-align: center;
    margin-bottom: 25px;
}

.help-header h3 {
    color: #1F2A44;
    margin-bottom: 5px;
}

.help-header p {
    font-size: 13px;
    color: #777;
}

.info {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding: 10px 12px;
    border-radius: 10px;
    transition: 0.3s;
    background: #f9f9f9;
}

.info:hover {
    background: rgba(106, 47, 224, 0.08);
    transform: translateX(3px);
}

.info span {
    font-weight: 600;
    width: 130px;
    color: #1E5ED9;
}

.info a, .info div {
    color: #333;
    font-size: 14px;
}

.info a {
    text-decoration: none;
    color: #6A2FE0;
}

.info a:hover {
    text-decoration: underline;
}

hr {
    margin: 20px 0;
    border: none;
    height: 1px;
    background: #eee;
}

.timing-box {
    background: linear-gradient(135deg, #1E5ED9, #6A2FE0);
    color: #fff;
    padding: 12px;
    border-radius: 10px;
    text-align: center;
    font-size: 14px;
}

.back-btn {
    margin-top: 20px;
    width: 100%;
    padding: 10px;
    background: linear-gradient(135deg, #1E5ED9, #6A2FE0, #D92BBF);
    color: #fff;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
}

.back-btn:hover {
    opacity: 0.9;
}
</style>
</head>

<body>

<div class="help-card">

    <div class="help-header">
        <h3>Help Desk</h3>
        <p>We are here to assist you</p>
    </div>

    <div class="info">
        <span>📞 Phone:</span> 
        <a href="tel:{{ $data['phone'] }}">{{ $data['phone'] }}</a>
    </div>

    <div class="info">
        <span>📧 Email:</span> 
        <a href="mailto:{{ $data['email'] }}">{{ $data['email'] }}</a>
    </div>

    <div class="info">
        <span>🌐 Website:</span> 
        <a href="https://{{ $data['website'] }}" target="_blank">
            {{ $data['website'] }}
        </a>
    </div>

    <div class="info">
        <span>📍 Address:</span> 
        <div>{{ $data['address'] }}</div>
    </div>

    <hr>

    <div class="timing-box">
        🕒 {{ $data['timing_days'] }} <br>
        {{ $data['timing_hours'] }}
    </div>

    <button class="back-btn" onclick="window.history.back()">
        ⬅ Back
    </button>

</div>

</body>
</html>