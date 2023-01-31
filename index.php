<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>hackeuse poulette</title>
</head>
<body>
<form method="post">
  <label for="nom">Nom:</label><br>
  <input type="text" id="nom" name="nom" required minlength="2" maxlength="255"><br>
  
  <label for="prenom">Prénom:</label><br>
  <input type="text" id="prenom" name="prenom" required minlength="2" maxlength="255"><br>
  
  <label for="email">Adresse e-mail:</label><br>
  <input type="email" id="email" name="email" required minlength="2" maxlength="255"><br>
  
  <label for="commentaire">Commentaire:</label><br>
  <textarea id="commentaire" name="commentaire" required minlength="255" maxlength="1000"></textarea><br>

  <div class="g-recaptcha" data-sitekey="6Ld6-D0kAAAAAN_UEwpo35Iq0DeIkabd5wYXloWI"></div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <input type="submit" value="Envoyer">
</form>



<script src="https://www.google.com/recaptcha/enterprise.js?render=6Ld6-D0kAAAAAN_UEwpo35Iq0DeIkabd5wYXloWI"></script>
<script>
grecaptcha.enterprise.ready(function() {
    grecaptcha.enterprise.execute('6Ld6-D0kAAAAAN_UEwpo35Iq0DeIkabd5wYXloWI', {action: 'login'}).then(function(token) {
       ...
    });
});
</script>



<?php


// Check if the form was submitted
if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['commentaire'])) {
  // Récupération des données du formulaire
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $email = $_POST['email'];
  $commentaire = $_POST['commentaire'];

  // Validation des données
  if (empty($nom) || strlen($nom) < 2 || strlen($nom) > 255) {
    die("Nom incorrect");
  }

  if (empty($prenom) || strlen($prenom) < 2 || strlen($prenom) > 255) {
    die("Prénom incorrect");
  }

  if (empty($email) || strlen($email) < 2 || strlen($email) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Adresse email incorrecte");
  }

  if (empty($commentaire) || strlen($commentaire) < 255 || strlen($commentaire) > 1000) {
    die("Commentaire incorrect");
  }

  $nom = preg_replace('/[^a-zA-Z0-9\s]/', '', $nom);
  $prenom = preg_replace('/[^a-zA-Z0-9\s]/', '', $prenom);
  $email = preg_replace('/[^a-zA-Z0-9@\.]/', '', $email);
  $commentaire = preg_replace('/[^a-zA-Z0-9\s\.\?,!:-]/', '', $commentaire);

  <?php

// Connexion à la base de données
try {
  $pdo = new PDO("mysql:host=localhost;dbname=hackeusepoulette", "username", "password");
} catch (PDOException $e) {
  die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Préparation et exécution de la requête SQL d'insertion
try {
  $stmt = $pdo->prepare("INSERT INTO table_name (nom, prenom, email, commentaire) VALUES (?, ?, ?, ?)");
  $stmt->execute([$nom, $prenom, $email, $commentaire]);
  echo "Données enregistrées avec succès";
} catch (PDOException $e) {
  die("Erreur lors de l'insertion des données : " . $e->getMessage());
}

// Chargement de l'autoloader de Composer
require 'vendor/autoload.php';

// Instantiation de PHPMailer
$mail = new PHPMailer(true);

try {
  // Paramètres du serveur
  $mail->SMTPDebug = 0;
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'miserquecorentin@gmail.com';
  $mail->Password = 'your-password';
  $mail->SMTPSecure = 'tls';
  $mail->Port = 587;

  // Destinataires
  $mail->setFrom('from@example.com', 'Mailer');
  $mail->addAddress($email, $nom . ' ' . $prenom);

  // Contenu
  $mail->isHTML(true);
  $mail->Subject = 'Sujet du message';
  $mail->Body    = $commentaire;
  $mail->AltBody = $commentaire;

  $mail->send();
  echo 'Message envoyé avec succès';
} catch (Exception $e) {
  echo "Le message n'a pas pu être envoyé. Erreur de PHPMailer : {$mail->ErrorInfo}";
}

