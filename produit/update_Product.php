<?php
session_start();
require_once('produit.class.php');

// Vérification des données POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
    $_SESSION['error'] = "Requête invalide";
    header('Location: ../dashboard/produit.php');
    exit;
}

$pr = new Produit();

// Récupération des données du formulaire
$id = (int)$_POST['id'];
$nom = trim($_POST['nom']);
$description = trim($_POST['description']);
$categorie = $_POST['categorie'];
$prix = (float)$_POST['prix'];
$stock = (int)$_POST['stock'];
$current_image = $_POST['current_image'] ?? null;

// Gestion du fichier image
$image = $current_image; // Par défaut, on garde l'ancienne image

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    // Configuration du téléchargement
    $uploadDir = '../img/';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    $detectedType = finfo_file($fileInfo, $_FILES['image']['tmp_name']);
    
    // Vérification du type de fichier
    if (!in_array($detectedType, $allowedTypes)) {
        $_SESSION['error'] = "Type de fichier non autorisé. Seuls JPEG, PNG et GIF sont acceptés.";
        header("Location: modifier_produit.php?id=$id");
        exit;
    }
    
    // Génération d'un nom de fichier unique
    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $newFilename = uniqid('prod_', true) . '.' . $extension;
    $uploadPath = $uploadDir . $newFilename;
    
    // Déplacement du fichier
    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
        $image = $newFilename;
        
        // Suppression de l'ancienne image si elle existe
        if ($current_image && file_exists($uploadDir . $current_image)) {
            unlink($uploadDir . $current_image);
        }
    } else {
        $_SESSION['error'] = "Erreur lors du téléchargement de l'image";
        header("Location: modifier_produit.php?id=$id");
        exit;
    }
}

// Mise à jour du produit
try {
    // Toujours passer l'image (même si elle n'a pas changé)
    $success = $pr->modifierProduit($id, $nom, $description, $categorie, $prix, $stock, $image);
    
    if ($success) {
        $_SESSION['success'] = "Produit mis à jour avec succès";
        header('Location: ../dashboard/produit.php');
        exit;
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour du produit";
        header("Location: modifier_produit.php?id=$id");
        exit;
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur: " . $e->getMessage();
    header("Location: modifier_produit.php?id=$id");
    exit;
}