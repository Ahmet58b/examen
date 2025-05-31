<?php
session_start();

// Database connectie
$host = 'localhost';
$dbname = 'ahmet_myth';
$username = 'ahmet_bolukbasi';
$password = 'Thegang2020';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Signup
if (isset($_POST['signup'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $checkEmail = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($checkEmail->num_rows > 0) {
        $signupError = "Dit e-mailadres is al geregistreerd.";
    } else {
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql)) {
            $signupSuccess = "Account succesvol aangemaakt. Je kunt nu inloggen.";
        } else {
            $signupError = "Fout bij het aanmaken van je account: " . $conn->error;
        }
    }
}

// Login
if (isset($_POST['login'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $loginError = "Verkeerd wachtwoord.";
        }
    } else {
        $loginError = "Gebruiker niet gevonden.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mortal Kombat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dropdown.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="">
            <img src="https://ahmet.stedelijklyceumexpo.be/6AD/img/dg9l9s5-1e3d5c3b-e01d-4b4c-a3f3-af944c67971b.png" width = 40px , height = 40px></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                         <div class="dropdown">
                          <button class="dropbtn">Types of fighters</button>
                           <div class="dropdown-content">
                              <a href="#">Humans</a>
                              <a href="#">Outworlders</a>
                              <a href="#">Tarkatans</a>
                              <a href="#">Edenians</a>
                              <a href="#">Undead / Revenants</a>
                              <a href="#">Ninjas</a>
                              <a href="#">Gods and Demi-Gods</a>
                              <a href="#">Vampires / Blood Mages</a>
                              <a href="#">Zaterrans</a>
                              <a href="#">Cyborgs</a>
                              <a href="#">Sorcerers </a>
                              <a href="#">Martial Artists / Special Forces</a>
                              <a href="#">Hybrids</a>
                            </div>
                         </div> 
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#loginModal" data-bs-toggle="modal">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#signupModal" data-bs-toggle="modal">Signup</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="text-center">
            <h1>mortal kombat history</h1>
            <p class="lead">read information on the different fighters in mortal kombat history.</p>
        </div>

    

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <?php if (isset($loginError)): ?>
                            <div class="alert alert-danger"><?php echo $loginError; ?></div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="loginEmail" class="form-label">E-mailaddres</label>
                            <input type="email" name="email" class="form-control" id="loginEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="loginPassword" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="login" class="btn btn-primary">LogIn</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Signup Modal -->
    <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signupModalLabel">Signup</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <?php if (isset($signupError)): ?>
                            <div class="alert alert-danger"><?php echo $signupError; ?></div>
                        <?php elseif (isset($signupSuccess)): ?>
                            <div class="alert alert-success"><?php echo $signupSuccess; ?></div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="signupUsername" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" id="signupUsername" required>
                        </div>
                        <div class="mb-3">
                            <label for="signupEmail" class="form-label">E-mailaddres</label>
                            <input type="email" name="email" class="form-control" id="signupEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="signupPassword" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="signupPassword" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="signup" class="btn btn-success">Create account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025.MK</p>
        <a href="#" class="text-white text-decoration-none">Privacybeleid</a> |
        <a href="#" class="text-white text-decoration-none">Gebruiksvoorwaarden</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
