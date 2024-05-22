<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Login</title>
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
             $emailErr = $passwordErr = $loginErr = "";
             $email = $password = "";

             // Fonction de validation de l'email
             function validateEmail($email) {
                 return filter_var($email, FILTER_VALIDATE_EMAIL);
             }

             // Fonction de validation du mot de passe (minimum 8 caractères)
             function validatePassword($password) {
                 return strlen($password) >= 8;
             }

             if(isset($_POST['submit'])){
               $email = htmlspecialchars($_POST['email']);
               $password = $_POST['password']; // Le mot de passe ne doit pas être échappé pour la vérification

               // Validation des entrées
               $isValid = true;
               if (!validateEmail($email)) {
                   $emailErr = "Format d'email invalide";
                   $isValid = false;
               }
               if (!validatePassword($password)) {
                   $passwordErr = "Le mot de passe doit contenir au moins 8 caractères";
                   $isValid = false;
               }

               if ($isValid) {
                   // Si les validations sont correctes, vérifier les informations d'identification
                   $query = $pdo->prepare("SELECT * FROM users WHERE Email = ?");
                   $query->execute([$email]);
                   $row = $query->fetch(PDO::FETCH_ASSOC);

                   if($row && password_verify($password, $row['Password'])){
                       $_SESSION['valid'] = $row['Email'];
                       $_SESSION['username'] = $row['Username'];
                       $_SESSION['age'] = $row['Age'];
                       $_SESSION['id'] = $row['Id'];

                       header("Location: home.php");
                       exit; // Arrêter l'exécution du script après la redirection
                   } else {
                       $loginErr = "Email ou mot de passe incorrect";
                   }
               }
             }
           ?>
            <header>Login</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="<?= htmlspecialchars($email) ?>" >
                    <span class="error"><?= $emailErr ?></span>
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" >
                    <span class="error"><?= $passwordErr ?></span>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login">
                </div>
                <div class="error"><?= $loginErr ?></div>
                <div class="links">
                    Don't have an account? <a href="register.php">Sign Up Now</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>