<?php
require_once "config.php";

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$dsn = 'mysql:host=localhost;dbname=romo_anjelyn';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $gender = $_POST["gender"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $religion = $_POST["religion"];

    $query = "INSERT INTO members (name, gender, email, user, address, religion) 
              VALUES (:name, :gender, :email, :user, :address, :religion)";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':user', $_SESSION["username"], PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':religion', $religion, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header("location: members.php");
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
}

$query = "SELECT firstname, lastname FROM users WHERE username = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION["username"]]);
$user = $stmt->fetch();

$firstname = $user['firstname'];
$lastname = $user['lastname'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Members Area</title>
    
    <style>
        body {
            font: 14px sans-serif;
            text-align: center;
        }

        .custom-margin {
            margin-left: -100px;
        }

        .custom-input {
            width: 20%;
        }

        .form-group.row input[type="date"]{
            padding-left: 15px;
        }

        .form-group.row input, .form-group.row textarea{
            padding-left: 16px;
        }

        .form-group.row {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-group.row label {
            margin-bottom: 0;
            flex-basis: 20%;
            text-align: left;
        }

        .form-group.row .col-sm-10 {
            flex-basis: 80%;
        }
    </style>
</head>

<body>
    <h1 style="margin-bottom: 0px;"><b><?php echo htmlspecialchars($firstname . " " . $lastname); ?></b>'s Members Area</h1>
    <p>
        <a href="logout.php">Sign Out of Your Account</a>
    </p>

    <hr>

    <div>
        <div>
            <h2>Add New Member:</h2>
        </div>
        <div>
            <p><a href="members.php">Member List</a></p>
        </div>
    </div>

    <form action="add_process.php" method="post">
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="3" required></textarea>
        </div>
        <div>
            <label for="religion">Religion:</label>
            <input type="religion" id="religion" name="religion" required>
        </div>
        <button type="submit">Add Member</button>
    </form>
    

</body>

</html>