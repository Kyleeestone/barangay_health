<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Earth Map with Legends</title>
    <style>
        /* General reset and modern styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
        }

        .container {
            text-align: center;
            margin: 20px;
        }

        a,
        button {
            display: inline-block;
            margin: 10px 10px;
            padding: 12px 20px;
            background-color: rgb(5, 58, 98);
            color: #fff;
            text-decoration: none;
            font-size: 1.2em;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        a:hover,
        button:hover {
            background-color: rgb(0, 0, 0);
        }

        h2 {
            text-align: center;
            margin-top: 20px;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 20px auto;
            max-width: 600px;
        }

        ul li {
            display: flex;
            align-items: center;
            margin: 10px 0;
            padding: 10px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-size: 1em;
        }

        ul li span.icon {
            font-size: 1.5em;
            margin-right: 15px;
        }

        ul li span.text {
            font-size: 1em;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Link to Google Earth Map -->
        <a href="https://earth.google.com/earth/d/1RG3bxR_Xx9a4-h-kVX1qhRcC8XKKf6Ra?usp=sharing" target="_blank">
            Open Google Earth Map
        </a>

        <!-- Back Button -->
        <button onclick="history.back()">Back</button>
    </div>

    <!-- Legends Section -->
    <h2>Map Legends</h2>
    <ul>
        <li>
            <span class="icon">👩‍🍼</span>
            <span class="text">Pregnant</span>
        </li>
        <li>
            <span class="icon">🍼</span>
            <span class="text">Baby</span>
        </li>
        <li>
            <span class="icon">❤️‍🩹</span>
            <span class="text">High Blood</span>
        </li>
        <li>
            <span class="icon">🧓</span>
            <span class="text">Senior Bedridden</span>
        </li>
        <li>
            <span class="icon">♿</span>
            <span class="text">Person with Disability (PWD)</span>
        </li>
        <li>
            <span class="icon">🫁</span>
            <span class="text">TB Case</span>
        </li>
        <li>
            <span class="icon">🍬</span>
            <span class="text">Diabetic</span>
        </li>
    </ul>
</body>

</html>