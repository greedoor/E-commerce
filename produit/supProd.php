<?php
require_once('produit.class.php'); 
$produit = new Produit();
if(isset($_GET['id'])) {
    $produit->supprimerProduit($_GET['id']);
}
header('Location: ../dashboard/produit.php');
?>
