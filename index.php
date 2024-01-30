<?php
session_start();

$letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$WON = false;

$guess = "HANGMAN";
$maxLetters = strlen($guess) - 1;
$responses = ["H", "G", "A"];

$bodyParts = ["nohead", "head", "body", "hand", "hands", "leg", "legs"];

$words = [
    "HANGMAN", "BUTTERFLY", "APPLE", "INSIDIOUSLY", "DUPLICATE",
    "CASUALTY", "GLOOMFUL"
];

function getCurrentPicture($part)
{
    return "./images/hangman_" . $part . ".png";
}

function restartGame()
{
    session_destroy();
    session_start();
}

function getParts()
{
    global $bodyParts;
    return isset($_SESSION["parts"]) ? $_SESSION["parts"] : $bodyParts;
}

function addPart()
{
    $parts = getParts();
    array_shift($parts);
    $_SESSION["parts"] = $parts;
}

function getCurrentPart()
{
    $parts = getParts();
    return $parts[0];
}

function getCurrentWord()
{
    global $words;
    if (!isset($_SESSION["word"]) && empty($_SESSION["word"])) {
        $key = array_rand($words);
        $_SESSION["word"] = $words[$key];
    }
    return $_SESSION["word"];
}

function getCurrentResponses()
{
    return isset($_SESSION["responses"]) ? $_SESSION["responses"] : [];
}

function addResponse($letter)
{
    $responses = getCurrentResponses();
    array_push($responses, $letter);
    $_SESSION["responses"] = $responses;
}

function isLetterCorrect($letter)
{
    $word = getCurrentWord();
    $max = strlen($word) - 1;
    for ($i = 0; $i <= $max; $i++) {
        if ($letter == $word[$i]) {
            return true;
        }
    }
    return false;
}

function isWordCorrect()
{
    $guess = getCurrentWord();
    $responses = getCurrentResponses();
    $max = strlen($guess) - 1;
    for ($i = 0; $i <= $max; $i++) {
        if (!in_array($guess[$i],  $responses)) {
            return false;
        }
    }
    return true;
}

function isBodyComplete()
{
    $parts = getParts();
    if (count($parts) <= 1) {
        return true;
    }
    return false;
}

function gameComplete()
{
    return isset($_SESSION["gamecomplete"]) ? $_SESSION["gamecomplete"] : false;
}

function markGameAsComplete()
{
    $_SESSION["gamecomplete"] = true;
}

function markGameAsNew()
{
    $_SESSION["gamecomplete"] = false;
}

// Function to increment wins for a player
function incrementWins($playerName)
{
    $players = getPlayerData();
    foreach ($players as &$player) {
        if ($player["name"] === $playerName) {
            $player["wins"]++;
            break;
        }
    }
    updatePlayerData($players);
}

// Function to increment losses for a player
function incrementLosses($playerName)
{
    $players = getPlayerData();
    foreach ($players as &$player) {
        if ($player["name"] === $playerName) {
            $player["losses"]++;
            break;
        }
    }
    updatePlayerData($players);
}

// Function to get player data from session
function getPlayerData()
{
    return isset($_SESSION["hangman_players"]) ? $_SESSION["hangman_players"] : [];
}

// Function to update player data in session
function updatePlayerData($players)
{
    $_SESSION["hangman_players"] = $players;
}

if (isset($_GET['start'])) {
    restartGame();
}

if (isset($_GET['kp'])) {
    $currentPressedKey = isset($_GET['kp']) ? $_GET['kp'] : null;
    if ($currentPressedKey
        && isLetterCorrect($currentPressedKey)
        && !isBodyComplete()
        && !gameComplete()
    ) {
        addResponse($currentPressedKey);
        if (isWordCorrect()) {
            $WON = true;
            markGameAsComplete();
            $currentPlayer = isset($_SESSION["currentPlayer"]) ? $_SESSION["currentPlayer"] : null;
            if ($currentPlayer) {
                incrementWins($currentPlayer);
            }
        }
    } else {
        if (!isBodyComplete()) {
            addPart();
            if (isBodyComplete()) {
                markGameAsComplete();
                $currentPlayer = isset($_SESSION["currentPlayer"]) ? $_SESSION["currentPlayer"] : null;
                if ($currentPlayer) {
                    incrementLosses($currentPlayer);
                }
            }
        } else {
            markGameAsComplete();
            $currentPlayer = isset($_SESSION["currentPlayer"]) ? $_SESSION["currentPlayer"] : null;
            if ($currentPlayer) {
                incrementLosses($currentPlayer);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hangman The Game</title>
    <style>
        #actionButtonsContainer {
            margin-top: 20px;
        }

        #actionButtons a {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            margin-right: 10px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #actionButtons a:hover {
            background-color: #45a049;
        }

        body {
            background-image: url('man2.jpg');
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        #container {
            background: black;
            width: 500px;
            padding: 15px;
            border-radius: 3px;
            text-align: center;
        }

        #images {
            margin-bottom: 20px;
        }

        #images img {
            width: 80%;
        }

        #gameStatus {
            font-size: 18px;
            margin-bottom: 10px;
        }

        #keyboard {
            margin-top: 20px;
        }

        #keyboard button {
            padding: 8px 15px;
            font-size: 16px;
            margin: 5px;
            background-color: lightblue;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #keyboard button:hover {
            background-color: #45a049;
        }

        #wordPuzzle {
            margin-top: 20px;
            padding: 15px;
            background: lightseagreen;
            color: #fcf8e3;
            font-size: 25px;
        }

        #wordPuzzle span {
            display: inline-block;
            border-bottom: 3px solid #000;
            margin-right: 5px;
            min-width: 35px;
        }
    </style>
</head>
<body>
    <div id="container">
        <div id="images">
            <img src="<?php echo getCurrentPicture(getCurrentPart()); ?>" alt="Hangman Image"/>
        </div>

        <?php if (gameComplete()): ?>
            <h1 id="gameStatus">GAME COMPLETE</h1>
        <?php endif; ?>
        <?php if ($WON && gameComplete()): ?>
            <p style="color: darkgreen; font-size: 25px;">You Won! HURRAY! :)</p>
        <?php elseif (!$WON && gameComplete()): ?>
            <p style="color: darkred; font-size: 25px;">You LOST! OH NO! :(</p>
        <?php endif; ?>

        <div id="keyboard">
            <form method="get">
                <?php
                $max = strlen($letters) - 1;
                for ($i = 0; $i <= $max; $i++) {
                    echo "<button type='submit' name='kp' value='" . $letters[$i] . "'>" .
                        $letters[$i] . "</button>";
                    if ($i % 7 == 0 && $i > 0) {
                        echo '<br>';
                    }
                }
                ?>
                <br><br>
                <button type="submit" name="start">Restart Game</button>
                <div id="actionButtonsContainer">
                    <div id="actionButtons">
                        <a href="result.php">RESULT</a>
                        <a href="player.php">Change player</a>
                    </div>
                </div>
            </form>
        </div>

        <div id="wordPuzzle">
            <?php
            $guess = getCurrentWord();
            $maxLetters = strlen($guess) - 1;
            for ($j = 0; $j <= $maxLetters; $j++) : $l = getCurrentWord()[$j]; ?>
                <?php if (in_array($l, getCurrentResponses())) : ?>
                    <span><?php echo $l; ?></span>
                <?php else : ?>
                    <span>&nbsp;&nbsp;&nbsp;</span>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>
