<?php
session_start();
require_once('produit.class.php');
require_once('../user.class.php');
require_once('../cart/cart.class.php');

$us = new user();
$x = $us->get_user($_SESSION['email']);

$cart = new Cart();
$cartItemCount = $cart->countCartRowsPerUser($x['id']);

$produit = new Produit();
$categories = ['PC', 'Mobile', 'Accessoire PC','watch','Tablette','Accessoire Mobile'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOP'ini - Ajouter un produit</title>
    <link rel="shortcut icon" href="../img/SHOPINIlo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/add_Products.css">
</head>
<body>
    <!-- Navigation identique à l'index -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <img src="../img/SHOPINILOGO11.png" alt="SHOP'ini" style="width: 150px; height: 45px">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../shop.php">Boutique</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../contact.php">Contact</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    <a href="../cart.php" class="btn btn-link position-relative p-0">
                        <i class="fas fa-shopping-cart fs-5"></i>
                        <span class="cart-badge"><?= $cartItemCount ?></span>
                    </a>
                    
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle p-0" type="button" id="userDropdown" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                <?= substr($x['nom'], 0, 1) ?>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header"><?= $x['nom'] ?></h6></li>
                            <li><a class="dropdown-item" href="../dashboard/settings.php"><i class="fas fa-user me-2"></i>Profil</a></li>
                            <li><a class="dropdown-item" href="../dashboard/produit.php"><i class="fas fa-box me-2"></i>Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../deconnexion.php"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">               
                <div class="form-container">
                    <h2 class="text-center mb-4 fw-bold">Ajouter un produit</h2>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="add_Product.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label for="nom" class="form-label fw-semibold">Nom du produit</label>
                            <input type="text" class="form-control" id="nom" name="nom" required 
                                   minlength="3" maxlength="255" placeholder="Ex: Smartphone X200">
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" id="description" name="description" 
                                      rows="4" required minlength="10" maxlength="2000"
                                      placeholder="Décrivez le produit en détail..."></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="categorie" class="form-label fw-semibold">Catégorie</label>
                                <select class="form-select" id="categorie" name="categorie" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    <?php foreach (['PC', 'Mobile', 'Accessoire PC','watch', 'Tablette', 'Accessoire Mobile'] as $cat): ?>
                                        <option value="<?= $cat ?>">
                                            <?= $cat ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="prix" class="form-label fw-semibold">Prix (€)</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" id="prix" 
                                           name="prix" required min="0.01" max="999999.99" placeholder="0.00">
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="stock" class="form-label fw-semibold">Stock</label>
                                <input type="number" class="form-control" id="stock" name="stock" 
                                       required min="0" max="9999" placeholder="Quantité disponible">
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="image" class="form-label fw-semibold">Image du produit</label>
                                <input type="file" class="form-control" id="image" name="image" 
                                       accept="image/*" required>
                                <small class="text-muted">Formats: JPG, PNG, GIF (max 2MB)</small>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-3 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg py-2">
                                <i class="fas fa-plus-circle me-2"></i> Ajouter le produit
                            </button>
                            <a href="../dashboard/produit.php" class="btn btn-secondary py-2">
                                <i class="fas fa-times me-2"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validation côté client pour l'image
        document.querySelector('form').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('image');
            if (fileInput.files.length > 0) {
                const fileSize = fileInput.files[0].size / 1024 / 1024; // en MB
                if (fileSize > 2) {
                    alert('La taille de l\'image ne doit pas dépasser 2MB');
                    e.preventDefault();
                }
            }
        });
    </script>
</body>
</html>