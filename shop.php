<?php
session_start();
require_once('produit/produit.class.php');
$produit = new Produit();



require_once('user.class.php');
$us = new user();
$x = $us->get_user($_SESSION['email']);
require_once('cart/cart.class.php');
$cart = new Cart();
$cartItemCount = $cart->countCartRowsPerUser($x['id']);

if (isset($_GET['categorie'])) {
    $selectedCategory = $_GET['categorie'];
    $produitsCategorie = $produit->listerProduitsParCategorie($selectedCategory);
} else {
    $produits = $produit->listerProduits();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOP'ini - Boutique</title>
    <link rel="shortcut icon" href="img/SHOPINIlo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/shop.css">
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
                        <a class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="shop.php">Boutique</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    <a href="cart.php" class="btn btn-link position-relative p-0">
                        <i class="fas fa-shopping-cart fs-5"></i>
                        <span class="cart-badge"><?php echo $cartItemCount; ?></span>
                    </a>
                    
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle p-0" type="button" id="userDropdown" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                <?php echo substr($x['nom'], 0, 1); ?>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header"><?php echo $x['nom']; ?></h6></li>
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

    
    <main class="container py-4">
       
        <div class="filter-bar">
            <div class="filter-group">
                <label for="categoryFilter" class="form-label mb-0">Catégorie:</label>
                <select id="categoryFilter" class="form-select form-select-sm" onchange="location = this.value;">
                    <option value="shop.php">Toutes catégories</option>
                    <?php foreach (['PC', 'Mobile', 'Accessoire PC','watch','Tablette','Accessoire Mobile',''] as $categorie): ?>
                    <option value="?categorie=<?php echo urlencode($categorie); ?>" 
                        <?php echo (isset($_GET['categorie']) && $_GET['categorie'] === $categorie) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($categorie); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="priceRange" class="form-label mb-0">Prix max:</label>
                <input type="range" class="form-range" min="0" max="10000" step="50" id="priceRange" style="width: 120px;">
                <span id="priceValue" class="ms-2">1000 €</span>
            </div>
            
            <div class="filter-group ms-auto">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" placeholder="Rechercher...">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
        
        
        <div class="product-grid">
            <?php 
            $produitsAfficher = isset($produitsCategorie) ? $produitsCategorie : $produits;
            if (empty($produitsAfficher)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center py-5">
                    <h4 class="alert-heading">Aucun produit trouvé</h4>
                    <p>Nous n'avons trouvé aucun produit correspondant à votre sélection.</p>
                    <a href="shop.php" class="btn btn-primary">Voir tous les produits</a>
                </div>
            </div>
            <?php else: ?>
                <?php foreach ($produitsAfficher as $prod): ?>
                <div class="product-card">
                    <div class="product-media">
                        <img src="img/<?php echo htmlspecialchars($prod['image']); ?>" 
                             class="product-img" 
                             alt="<?php echo htmlspecialchars($prod['nom_produit']); ?>"
                             loading="lazy">
                        <span class="product-badge"><?php echo htmlspecialchars($prod['categorie']); ?></span>
                    </div>
                    <div class="product-body">
                        <h3 class="product-title"><?php echo htmlspecialchars($prod['nom_produit']); ?></h3>
                        <p class="product-description">
                            <?php echo htmlspecialchars(substr($prod['description'], 0, 100)); ?>
                            <?php echo strlen($prod['description']) > 100 ? '...' : ''; ?>
                        </p>
                        <div class="product-footer">
                        <span class="product-price"><?php echo number_format($prod['prix'], 2, ',', ' '); ?> €</span>
                            <form action="cart/ajouter_au_panier.php" method="post">
                                <input type="hidden" name="id_user" value="<?php echo $x['id']; ?>">
                                <input type="hidden" name="id_produit" value="<?php echo $prod['id_produit']; ?>">
                                <input type="hidden" name="nom_produit" value="<?php echo htmlspecialchars($prod['nom_produit']); ?>">
                                <input type="hidden" name="prix_produit" value="<?php echo $prod['prix']; ?>">
                                <input type="number" name="quantite" value="1" min="1" class="form-control form-control-sm d-none">
                                <button type="submit" class="add-to-cart-btn" title="Ajouter au panier">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-5">
            <ul class="pagination pagination-custom justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </main>

    <!-- Pied de page -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <img src="img/SHOPINILOGO11.png" alt="SHOP'ini" style="width: 400px; height: 110px" class="mb-3">
                    <p>
                    SHOP'ini est votre destination premium pour les produits high-tech.
                        Nous sélectionnons avec soin chaque article pour vous offrir le meilleur.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h5 class="mb-3">Navigation</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php">Accueil</a></li>
                        <li class="mb-2"><a href="shop.php">Boutique</a></li>
                        <li class="mb-2"><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h5 class="mb-3">Catégories</h5>
                    <ul class="list-unstyled">
                        <?php foreach (array_slice(['PC', 'Mobile', 'Accessoire PC','watch','Tablette','Accessoire Mobile',''], 0, 5) as $categorie): ?>
                        <li class="mb-2"><a href="shop.php?categorie=<?= urlencode($categorie) ?>"><?= htmlspecialchars($categorie) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="col-lg-4">
                    <h5 class="mb-3">Contact</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Rue Tech, Sfax, Tunisie</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> contact@SHOP'ini.tn</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +216 12 345 678</li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4 bg-light">
            
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small mb-0">&copy; <?= date('Y') ?> SHOP'ini. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="small mb-0">
                        <a href="#">Conditions générales</a> | 
                        <a href="#">Politique de confidentialité</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gestion de la plage de prix
        const priceRange = document.getElementById('priceRange');
        const priceValue = document.getElementById('priceValue');
        
        if (priceRange && priceValue) {
            priceRange.addEventListener('input', function() {
                priceValue.textContent = this.value + ' €';
            });
        }
        
        // Animation 
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
        priceRange.addEventListener('change', function() {
    const maxPrice = parseFloat(this.value);
    document.querySelectorAll('.product-card').forEach(card => {
        const priceText = card.querySelector('.product-price').textContent;
        
        const price = parseFloat(priceText
            .replace(/[^\d,.]/g, '')  
            .replace(',', '.')        
            .replace(/\.(?=.*\.)/g, '') 
        );
        
        if (price <= maxPrice) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});


    </script>
</body>
</html>