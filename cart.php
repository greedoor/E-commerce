<?php
session_start();
require_once('user.class.php');
require_once('cart/cart.class.php');
require_once('produit/produit.class.php');

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}


$us = new User();
$cart = new Cart();
$p = new Produit();


$x = $us->get_user($_SESSION['email']);
if (!$x) {
    die("Erreur : Utilisateur non trouvé");
}

// Récupé le panier
$user_id = $x['id'];
$cart_items = $cart->getUserCart($user_id);
$cartItemCount = $cart->countCartRowsPerUser($user_id);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOP'ini | Panier</title>
    <link rel="shortcut icon" href="img/SHOPINIlo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/cart.css">
    
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="img/SHOPINILOGO11.png" alt="SHOP'ini" style="width: 150px; height: 45px">
            </a>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="shop.php">Boutique</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
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

    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold">Votre Panier</h2>
                <nav aria-label="breadcrumb">
                    <!-- <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Accueil</a></li>
                        <li class="breadcrumb-item active">Panier</li>
                    </ol> -->
                </nav>
            </div>
        </div>
        
        <?php if (empty($cart_items)): ?>
            <div class="alert alert-info text-center py-5">
                <h4 class="alert-heading">Votre panier est vide</h4>
                <p>Commencez votre shopping dès maintenant !</p>
                <a href="shop.php" class="btn btn-primary-custom mt-3">Explorer la boutique</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="cart-table table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Prix</th>
                                    <th>Quantité</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total_price = 0;
                                foreach ($cart_items as $item): 
                                    $product = $p->getProduct($item['id_produit']);
                                    if ($product):
                                        $total_price += $item['total_price'];
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="img/<?= htmlspecialchars($product['image']) ?>" 
                                                     class="product-img-cart me-3" 
                                                     alt="<?= htmlspecialchars($product['nom_produit']) ?>">
                                                <div>
                                                    <h6 class="mb-0"><?= htmlspecialchars($product['nom_produit']) ?></h6>
                                                    <small class="text-muted"><?= htmlspecialchars($product['categorie']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= number_format($product['prix'], 2) ?> €</td>
                                        <td>
                                            <input type="number" class="quantity-input" 
                                                   value="<?= $item['quantity'] ?>" min="1" readonly>
                                        </td>
                                        <td><?= number_format($item['total_price'], 2) ?> €</td>
                                        <td>
                                            <form action="cart/sup_cart.php" method="post">
                                                <input type="hidden" name="id_cart" value="<?= $item['id'] ?>">
                                                <input type="hidden" name="id_user" value="<?= $user_id ?>">
                                                <button type="submit" class="remove-btn">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-danger">
                                            Produit introuvable (ID: <?= $item['id_produit'] ?>)
                                        </td>
                                    </tr>
                                <?php endif; endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="shop.php" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Continuer vos achats
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="summary-card">
                        <h4 class="mb-4">Récapitulatif de commande</h4>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span>Sous-total</span>
                            <span><?= number_format($total_price, 2) ?> €</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span>Livraison</span>
                            <span>Gratuite</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <h5>Total</h5>
                            <h5><?= number_format($total_price, 2) ?> €</h5>
                        </div>
                        
                        <form action="cart/commande.php" method="post">
                            <?php foreach ($cart_items as $item): ?>
                                <input type="hidden" name="quantite[]" value="<?= $item['quantity'] ?>">
                                <input type="hidden" name="id_produit[]" value="<?= $item['id_produit'] ?>">
                            <?php endforeach; ?>
                            <button type="submit" class="btn btn-primary-custom w-100 py-3">
                                Passer la commande
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer identique à la page index -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <img src="img/SHOPINILOGO11.png" alt="SHOP'ini" style="width: 400px; height: 110px" class="mb-3">
                    <p>SHOP'ini est votre destination premium pour les produits high-tech.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4">
                    <h5 class="mb-3">Navigation</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php">Accueil</a></li>
                        <li class="mb-2"><a href="shop.php">Boutique</a></li>
                        <li class="mb-2"><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-4">
                    <h5 class="mb-3">Catégories</h5>
                    <ul class="list-unstyled">
                        <?php 
                        
                            foreach (array_slice(['PC', 'Mobile', 'Accessoire PC','watch','Tablette','Accessoire Mobile',''], 0, 5) as $categorie): ?>
                                <li class="mb-2"><a href="shop.php?categorie=<?= urlencode($categorie) ?>"><?= $categorie ?></a></li>
                            <?php endforeach;
                        ?>
                    </ul>
                </div>
                
                <div class="col-lg-4">
                    <h5 class="mb-3">Contact</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Rue Tech, Sfax, Tunisie</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> contact@shopini.tn</li>
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
</body>
</html>