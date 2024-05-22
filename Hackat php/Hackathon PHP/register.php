<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Register</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="box form-box">
        <?php 
        include("php/config.php");

        // Variables pour stocker les messages d'erreur
        $usernameErr = $emailErr = $ageErr = $passwordErr = "";
        $username = $email = $age = $password = "";

        // Fonction de validation de l'email
        function validateEmail($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }

        // Fonction de validation du mot de passe (minimum 8 caractères)
        function validatePassword($password) {
            return strlen($password) >= 8;
        }

        // Fonction de validation de l'âge (doit être un nombre entre 0 et 120)
        function validateAge($age) {
            return is_numeric($age) && $age >= 0 && $age <= 120;
        }

        if(isset($_POST['submit'])){
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $age = htmlspecialchars($_POST['age']);
            $password = $_POST['password']; // Le mot de passe ne doit pas être échappé pour le hashage

            // Validation des entrées
            $isValid = true;
            if (empty($username)) {
                $usernameErr = "Ce champ est obligatoire";
                $isValid = false;
            }
            if (empty($email) || !validateEmail($email)) {
                $emailErr = empty($email) ? "Ce champ est obligatoire" : "Format d'email invalide";
                $isValid = false;
            }
            if (empty($age) || !validateAge($age)) {
                $ageErr = empty($age) ? "Ce champ est obligatoire" : "Âge invalide";
                $isValid = false;
            }
            if (empty($password) || !validatePassword($password)) {
                $passwordErr = empty($password) ? "Ce champ est obligatoire" : "Le mot de passe doit contenir au moins 8 caractères";
                $isValid = false;
            }

            if ($isValid) {
                // Vérification de l'email unique
                $verify_query = $pdo->prepare("SELECT Email FROM users WHERE Email = ?");
                $verify_query->execute([$email]);

                if($verify_query->rowCount() != 0 ){
                    echo "<div class='message'><p>This email is already used, please try another one!</p></div><br>";
                    echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button>";
                } else {
                    // Hashage du mot de passe pour la sécurité
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    $insert_query = $pdo->prepare("INSERT INTO users (Username, Email, Age, Password) VALUES (?, ?, ?, ?)");
                    $insert_query->execute([$username, $email, $age, $hashed_password]);

                    // Redirection vers la page de succès
                    header("Location: success.php");
                    exit;
                }
            }
        } 
        ?>

            <header>Sign Up</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" autocomplete="off" value="<?= htmlspecialchars($username) ?>" required>
                    <span class="error"><?= $usernameErr ?></span>
                </div>
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" autocomplete="off" value="<?= htmlspecialchars($email) ?>" required>
                    <span class="error"><?= $emailErr ?></span>
                </div>
                <div class="field input">
                    <label for="age">Age</label>
                    <input type="number" name="age" id="age" autocomplete="off" value="<?= htmlspecialchars($age) ?>" required>
                    <span class="error"><?= $ageErr ?></span>
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                    <span class="error"><?= $passwordErr ?></span>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Register">
                </div>
                <div class="links">
                    Already a member? <a href="index.php">Sign In</a>
                </div>
            </form>
        </div>
        <?php ?>
    </div>
</body>
</html>