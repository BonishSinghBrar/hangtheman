<!-- result.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hangman Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }

        h1 {
            color: #333;
        }

        #resultsList {
            text-align: left;
            max-width: 400px;
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <h1>Hangman Results</h1>
    <div id="resultsList">
        <h2>Player Results</h2>
        <ul id="resultsUl"></ul>
    </div>

    <script>
        // Function to display player results
        function displayPlayerResults() {
            var players = JSON.parse(localStorage.getItem("hangman_players")) || [];
            var resultsUl = document.getElementById("resultsUl");
            resultsUl.innerHTML = "";

            players.forEach(player => {
                var li = document.createElement("li");
                li.textContent = `${player.name} - Wins: ${player.wins}, Losses: ${player.losses}`;
                resultsUl.appendChild(li);
            });
        }

        // Call the function on page load
        displayPlayerResults();
    </script>
</body>
</html>
