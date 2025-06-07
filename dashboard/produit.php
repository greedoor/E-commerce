"<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../login.php');
    exit();
}

require_once __DIR__.'/../user.class.php';
require_once __DIR__.'/../produit/produit.class.php';
require_once __DIR__.'/../cart/cart.class.php';

$us = new user();
$x = $us->get_user($_SESSION['email']);

if ($x['role'] != 'admin' && $x['role'] != 'super_admin') {
    header('Location: ../index.php');
    exit();
}

$produit = new Produit();
$products = $produit->listerProduits();

$cart = new Cart();
$cartItemCount = $cart->countCartRowsPerUser($x['id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOP'ini | Gestion Produits</title>
    <link rel="shortcut icon" href="../img/SHOPINIlo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0182ca;       
            --primary-dark: #016ba3; 
            --secondary: #14c18e;    
            --dark: #1e293b;
            --light: #f8fafc;
            --light-gray: #e2e8f0;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f8f9fa;
            padding-top: 80px;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
        }
        
        .table th {
            background-color: var(--primary);
            color: white;
            border: none;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        
        .product-img-table {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-success {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(1, 130, 202, 0.05);
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            background: var(--secondary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 600;
        }
        
        .footer {
            background: white;
            color: var(--dark);
            padding: 3rem 0;
            margin-top: 3rem;
        }
        
        .footer h5 {
            color: var(--dark);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        
        .footer a {
            color: #4b5563;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .footer a:hover {
            color: var(--primary);
        }
    </style>
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
                            <li><a class="dropdown-item" href="settings.php"><i class="fas fa-user me-2"></i>Profil</a></li>
                            <li><a class="dropdown-item" href="produit.php"><i class="fas fa-box me-2"></i>Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../deconnexion.php"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="fw-bold mb-3">Gestion des Produits</h2>
                <!-- <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="../index.php">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="produit.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Produits</li>
                    </ol>
                </nav> -->
            </div>
            <div class="col-md-6 text-md-end">
                <a href="../produit/add_Products.php" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i> Ajouter Produit
                </a>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Prix</th>
                                <th>Stock</th>
                                <th>Catégorie</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($products): ?>
                                <?php foreach($products as $product): ?>
                                <tr>
                                    <td>
                                        <img src="../img/<?= htmlspecialchars($product['image']) ?>" 
                                             alt="<?= htmlspecialchars($product['nom_produit']) ?>" 
                                             class="product-img-table">
                                    </td>
                                    <td><?= htmlspecialchars($product['nom_produit']) ?></td>
                                    <td><?= substr(htmlspecialchars($product['description']), 0, 50) ?>...</td>
                                    <td><?= number_format($product['prix'], 2) ?> €</td>
                                    <td><?= $product['stock'] ?></td>
                                    <td><?= htmlspecialchars($product['categorie']) ?></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="../produit/modifier_produit.php?id=<?= $product['id_produit'] ?>" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="../produit/supProd.php?id=<?= $product['id_produit'] ?>" 
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">Aucun produit trouvé</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer identique à l'index -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <img src="../img/SHOPINILOGO11.png" alt="SHOP'ini" style="width: 400px; height: 110px" class="mb-3">
                    <p>
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
                    <h5 class="mb-3">Navigation</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="../index.php">Accueil</a></li>
                        <li class="mb-2"><a href="../shop.php">Boutique</a></li>
                        <li class="mb-2"><a href="../contact.php">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-4">
                    <h5 class="mb-3">Catégories</h5>
                    <ul class="list-unstyled">
                        <?php 
                        $categories = $produit->selectionnerCategories();
                        foreach (array_slice($categories, 0, 5) as $categorie): ?>
                        <li class="mb-2"><a href="../shop.php?categorie=<?= urlencode($categorie) ?>"><?= $categorie ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-4">
                    <h5 class="mb-3">Contact</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Rue Tech, Sfax, Tunisie</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> contact@SHOP'ini.tn</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +216 12 345 678</li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4 bg-secondary">
            
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
        document.querySelectorAll('.btn-danger').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if(!confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>