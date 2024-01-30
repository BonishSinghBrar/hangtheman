<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hang the Man</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-align: center;
            margin: 0;
            background: url('man1.jpg') center/cover no-repeat; /* Added background image */
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        h1 {
            color: #fff;
            font-size: 2em;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
            
        p {
            color: #fff; 
        } 

        #startButton {
            padding: 15px 25px;
            font-size: 18px;
            background-color: #ff6b6b;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #startButton:hover {
            background-color: #ff5252;
        }
    </style>
</head>
<body>
    <h1>Welcome to Hang the Man</h1>
    <p>Embark on an exciting word-guessing adventure!</p>
    <form action="player.php">
        <button type="submit" id="startButton">START GAME</button>
    </form>
</body>
</html>
