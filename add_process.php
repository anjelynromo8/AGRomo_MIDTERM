<?php
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

$query = "SELECT firstname, lastname FROM users WHERE username = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION["username"]]);
$user = $stmt->fetch();

$firstname = $user['firstname'];
$lastname = $user['lastname'];

// If form is submitted, save values to database
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $gender = $_POST["gender"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $religion = $_POST["religion"];

    $query = "INSERT INTO members (name, gender, email, address, religion) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$name, $gender, $email, $address, $religion]);

    header("location: members.php");
    exit;
}
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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div>
                    <label>Name:</label>
                    <input type="text" name="name" required>
                </div>
                <div>
                    <label>Gender:</label>
                    <select name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label>Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div>
                    <label>Address</label>
                    <textarea name="address" required></textarea>
                </div>
                <div>
                    <label>Religion</label>
                    <input type="religion" name="tin" required>
                </div>
                <div>
                    <button type="submit">Add Member</button>
                </div>
            </form>
        </div>

</body>

</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve the values from the form
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $religion = $_POST['religion'];

    // Connect to the database
    $dsn = 'mysql:host=localhost;dbname=romo_anjelyn';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }

    // Insert the values into the database
    $query = "INSERT INTO members (name, gender, email, user, address, religion) VALUES (:name, :gender, :email, :user, :address, :religion)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':user', $_SESSION['username']);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':religion', $religion);
    $stmt->execute();

    // Redirect to the member list page
    header("Location: members.php");
    exit();
}
?>