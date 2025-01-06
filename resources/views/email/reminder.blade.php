<!DOCTYPE html>
<html>
<head>
    <title>Reminder Jadwal Pelatihan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: #4CAF50;
            color: #fff;
            text-align: center;
            padding: 15px;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            margin: 10px 0;
            line-height: 1.6;
        }
        .content .label {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            padding: 10px;
            background: #f4f4f9;
            color: #888;
            font-size: 14px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Reminder Jadwal Pelatihan
        </div>
        <div class="content">
            <p><span class="label">Nama Pelatihan:</span> {{ $details['eventName'] }}</p>
            <p><span class="label">Divisi:</span> {{ $details['division'] }}</p>
            <p><span class="label">Nama Peserta:</span> {{ $details['personName'] }}</p>
            <p><span class="label">Jadwal:</span> {{ $details['startDate'] }} - {{ $details['endDate'] }}</p>
        </div>
        <div class="footer">
            © {{ date('Y') }} - HRD Milenia Group
        </div>
    </div>
</body>
</html>
