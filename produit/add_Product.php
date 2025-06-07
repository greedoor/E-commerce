<?php
require_once('produit.class.php');
session_start();
require_once('../session.php');

Verifier_session();

$produit = new Produit();

$nom = $_POST['nom'];
$description = $_POST['description'];
$categorie = $_POST['categorie'];
$prix = $_POST['prix'];
$stock = $_POST['stock'];
$photo_tmp = $_FILES['image']['tmp_name'];
$photo_nom = $_FILES['image']['name'];

$destination = '../img/' . $photo_nom;
move_uploaded_file($photo_tmp, $destination);

$produit->image = $photo_nom;

$produit->ajouterProduit($nom, $description, $categorie, $prix, $stock, $photo_nom);

header('location:../dashboard/produit.php');
?>

