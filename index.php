<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // جلب المدخلات من الواجهة الأمامية
    $input = $_POST['input'];

    // تنظيف المدخلات (تجنب الهجمات)
    $input = filter_var($input, FILTER_SANITIZE_STRING);

    // إرسال المدخل إلى Python لمعالجته
    $command = escapeshellcmd("python3 -c 'import re; input_data=\"$input\"; print(\"تحليل البريد الإلكتروني أو النطاق:\", input_data)'");
    $python_output = shell_exec($command);

    // عرض النتائج
    echo "<div>النتائج: <br>" . nl2br($python_output) . "</div>";
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OSINT Tool</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }

        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        #result {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>OSINT Tool</h1>
    <label for="input">أدخل البريد الإلكتروني أو اسم النطاق:</label>
    <input type="text" id="input" name="input" placeholder="البريد الإلكتروني أو اسم النطاق">
    <button onclick="fetchInfo()">بحث</button>

    <div id="result"></div>
</div>

<script>
    function fetchInfo() {
        let input = document.getElementById('input').value;
        document.getElementById('result').innerHTML = 'جاري البحث...';

        // إرسال البيانات إلى ملف PHP
        fetch('osint_tool.php', {
            method: 'POST',
            body: new URLSearchParams({
                'input': input
            })
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('result').innerHTML = data;
        })
        .catch(error => {
            document.getElementById('result').innerHTML = 'حدث خطأ.';
            console.error('Error:', error);
        });
    }
</script>

</body>
</html>
