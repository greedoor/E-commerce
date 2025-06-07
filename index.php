<?php

session_start();
if($_SESSION["connecte"]!=="1")
{
    header('Location: SignIn.html');
    exit();


}
else
{
    
    require_once('produit/produit.class.php');
    require_once('cart/cart.class.php');
    require_once('user.class.php');

    $us = new user();
    $x = $us->get_user($_SESSION['email']);

    $cart = new Cart();
    $cartItemCount = $cart->countCartRowsPerUser($x['id']);

    $produit = new Produit();
    
    $featuredProducts = $produit->listerProduits(); // Produits vedettes
    
}

$maxDuree = 30;


if (isset($_COOKIE['login_time'])) {
    $tempsConnexion = (int) $_COOKIE['login_time'];

    if (time() - $tempsConnexion > $maxDuree) {
        
        session_unset();
        session_destroy();
        setcookie('login_time', '', time() - 3600, "/"); 

        header('Location: SignIn.html');
        exit;
    } else {
        
        setcookie('login_time', time(), time() + $maxDuree, "/");
    }}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOP'ini | Accueil</title>
    <link rel="shortcut icon" href="img/SHOPINIlo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/index.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
</head>

<body>
    
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="img/SHOPINILOGO11.png" alt="SHOP'ini" style="width: 150px; height: 45px">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php">Boutique</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    <a href="cart.php" class="btn btn-link position-relative p-0">
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
                            <li><a class="dropdown-item" href="dashboard\settings.php"><i class="fas fa-user me-2"></i>Profil</a></li>
                            <?php
                            if ($_SESSION['email'] === 'admin@gmail.com') {
                                echo'<li><a class="dropdown-item" href="dashboard\produit.php"><i class="fas fa-box me-2"></i>Dashboard</a></li>' ;
                                echo'<li><hr class="dropdown-divider"></li>';
            
                                
                            }
                            ?>
                            
                            
                            <li><a class="dropdown-item" href="deconnexion.php"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section (spécifique à l'index) -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h1 class="display-4 fw-bold mb-4">L'innovation technologique à portée de main</h1>
                    <p class="lead mb-4">Découvrez notre sélection exclusive de produits high-tech soigneusement choisis pour leur qualité et performance.</p>
                    <!-- <a href="shop.php" class="btn btn-primary btn-lg px-4">Explorer la boutique</a> -->
                </div>
                <div class="col-lg-6">
                    <img src="img/WATCH2.png" alt="Produits SHOP'ini" class="img-fluid ">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h5 class="mb-2">Livraison rapide</h5>
                        <p class="text-muted mb-0">Expédition sous 24h</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="mb-2">Paiement sécurisé</h5>
                        <p class="text-muted mb-0">Cryptage SSL</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <h5 class="mb-2">Retours faciles</h5>
                        <p class="text-muted mb-0">30 jours pour changer d'avis</p>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h5 class="mb-2">Support 24/7</h5>
                        <p class="text-muted mb-0">Assistance technique</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Produits vedettes -->
<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h2 class="mb-3">Nos Packs Exclusifs</h2>
                <p class="text-muted">Découvrez nos offres spéciales préparées pour vous</p>
            </div>
        </div>
        
        <div class="product-grid">
            <!-- Pack 1 -->
            <div class="product-card">
                <div class="product-media">
                    <img src="img/Pack1.jpg" 
                         class="product-img" 
                         alt="Pack Starter"
                         loading="lazy">
                    <span class="product-badge">Pack</span>
                </div>
                <div class="product-body">
                    <h3 class="product-title">Pack Starter</h3>
                    <p class="product-description">
                        Parfait pour débuter avec notre sélection de produits essentiels à prix réduit.
                    </p>
                    <div class="product-footer">
                        <span class="product-price">2990,99 €</span>
                        <button class="add-to-cart-btn" disabled title="Contactez-nous pour ce pack">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Pack 2 -->
            <div class="product-card">
                <div class="product-media">
                    <img src="img/Pack2.png" 
                         class="product-img" 
                         alt="Pack Pro"
                         loading="lazy">
                    <span class="product-badge">Pack</span>
                </div>
                <div class="product-body">
                    <h3 class="product-title">Pack Pro</h3>
                    <p class="product-description">
                        Pour les professionnels exigeants, ce pack offre des performances optimales.
                    </p>
                    <div class="product-footer">
                        <span class="product-price">3990,99 €</span>
                        <button class="add-to-cart-btn" disabled title="Contactez-nous pour ce pack">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Pack 3 -->
            <div class="product-card">
                <div class="product-media">
                    <img src="img/Pack3.png" 
                         class="product-img" 
                         alt="Pack Premium"
                         loading="lazy">
                    <span class="product-badge">Pack</span>
                </div>
                <div class="product-body">
                    <h3 class="product-title">Pack Premium</h3>
                    <p class="product-description">
                        Notre offre haut de gamme avec les derniers produits et accessoires inclus.
                    </p>
                    <div class="product-footer">
                        <span class="product-price">3590,99 €</span>
                        <button class="add-to-cart-btn" disabled title="Contactez-nous pour ce pack">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
                        <!-- Pack 4 -->
                        <div class="product-card">
                <div class="product-media">
                    <img src="img/Pack4.png" 
                         class="product-img" 
                         alt="Pack Premium"
                         loading="lazy">
                    <span class="product-badge">Pack</span>
                </div>
                <div class="product-body">
                    <h3 class="product-title">Pack Premium</h3>
                    <p class="product-description">
                        Notre offre haut de gamme avec les derniers produits et accessoires inclus.
                    </p>
                    <div class="product-footer">
                        <span class="product-price">4290,99 €</span>
                        <button class="add-to-cart-btn" disabled title="Contactez-nous pour ce pack">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>
</section>

    <!-- Footer identique à shop.php -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    
                    <img src="img/SHOPINILOGO11.png" alt="SHOP'ini" style="width: 400px; height: 110px" class="mb-3">
                    <p class="text-light">
                    SHOP'ini est votre destination premium pour les produits high-tech.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4">
                    <h5 class="text-white mb-3">Navigation</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-light">Accueil</a></li>
                        <li class="mb-2"><a href="shop.php" class="text-light">Boutique</a></li>
                        <li class="mb-2"><a href="contact.php" class="text-light">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-4">
                    <h5 class="text-white mb-3">Catégories</h5>
                    <ul class="list-unstyled">
                        <?php foreach (array_slice(['PC', 'Mobile', 'Accessoire PC','watch','Tablette','Accessoire Mobile',''], 0, 5) as $categorie): ?>
                        <li class="mb-2"><a href="shop.php?categorie=<?= urlencode($categorie) ?>" class="text-light"><?= $categorie ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-4">
                    <h5 class="text-white mb-3">Contact</h5>
                    <ul class="list-unstyled text-light">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Rue Tech, Sfax, Tunisie</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> contact@SHOP'ini.tn</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +216 12 345 678</li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4 bg-secondary">
            
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small text-light mb-0">&copy; <?= date('Y') ?> SHOP'ini. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="small text-light mb-0">
                        <a href="#" class="text-light">Conditions générales</a> | 
                        <a href="#" class="text-light">Politique de confidentialité</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation des cartes produit
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
                card.style.boxShadow = '0 10px 20px rgba(0,0,0,0.15)';
                card.querySelector('.product-img').style.transform = 'scale(1.05)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
                card.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
                card.querySelector('.product-img').style.transform = '';
            });
        });
        document.getElementsByClassName("dropdown-item").style.display = "none";
        
    </script>
</body>
</html> 