<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hangman Game</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }

        h1 {
            color: #333;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            margin: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        #playerNameInput {
            margin: 20px;
        }

        #playerList {
            text-align: left;
            max-width: 400px;
            margin: 20px auto;
        }

        #resultButton {
            display: none;
        }
    </style>
</head>
<body>
    <h1>Welcome to Hangman Game</h1>
    
    <button onclick="showNewPlayerPage()">New Player</button>
    <button onclick="showPreviousPlayersPage()">Previous Players</button>

    <div id="playerNameInput" style="display: none;">
        <label for="newPlayerName">Enter your name: </label>
        <input type="text" id="newPlayerName">
        <button onclick="addNewPlayer()">Submit</button>
    </div>

    <div id="playerList" style="display: none;">
        <h2>Previous Players</h2>
        <ul id="playersUl"></ul>
    </div>

    <!-- Result button to navigate to the result page -->
    <button id="resultButton" onclick="showResultsPage()">View Results</button>

    <!-- Start Game button to navigate to the game page -->
    <form action="index.php" method="get">
        <button type="submit" id="startGameButton" style="display: none;">Start Game</button>
    </form>

    <script>
        function showNewPlayerPage() {
            document.getElementById("playerList").style.display = "none";
            document.getElementById("playerNameInput").style.display = "block";
            // Hide result and start game buttons when entering new player
            document.getElementById("resultButton").style.display = "none";
            document.getElementById("startGameButton").style.display = "none";
        }

        function showPreviousPlayersPage() {
            document.getElementById("playerNameInput").style.display = "none";
            document.getElementById("playerList").style.display = "block";
            displayPlayerList();
            // Show result and start game buttons when entering the previous players page
            document.getElementById("resultButton").style.display = "inline-block";
            document.getElementById("startGameButton").style.display = "inline-block";
        }

        function showResultsPage() {
            window.location.href = "result.php";
        }

        function addNewPlayer() {
            var playerName = document.getElementById("newPlayerName").value;

            if (playerName.trim() !== "") {
                var players = JSON.parse(localStorage.getItem("hangman_players")) || [];
                var existingPlayer = players.find(player => player.name === playerName);

                if (!existingPlayer) {
                    players.push({ name: playerName, wins: 0, losses: 0 });
                    localStorage.setItem("hangman_players", JSON.stringify(players));

                    // Switch to the previous players page
                    showPreviousPlayersPage();
                } else {
                    alert("Player with the same name already exists. Please choose a different name.");
                }
            } else {
                alert("Please enter a valid name.");
            }
        }

        function displayPlayerList() {
            var players = JSON.parse(localStorage.getItem("hangman_players")) || [];
            var playersUl = document.getElementById("playersUl");
            playersUl.innerHTML = "";

            players.forEach(player => {
                var li = document.createElement("li");
                li.textContent = `${player.name} - Wins: ${player.wins}, Losses: ${player.losses}`;
                playersUl.appendChild(li);
            });
        }
    </script>
</body>
</html>
