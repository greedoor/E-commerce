<?php
session_start();
require_once('user.class.php');
$us = new user();
$x = $us->get_user($_SESSION['email']);
require_once('cart/cart.class.php');
$cart = new Cart();
$cartItemCount = $cart->countCartRowsPerUser($x['id']);

require_once('produit/produit.class.php');
$produit = new Produit();



// Traitement du formulaire
$messageSent = false;
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validation
    if (empty($name)) $errors['name'] = 'Veuillez entrer votre nom';
    if (empty($email)) {
        $errors['email'] = 'Veuillez entrer votre email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email invalide';
    }
    if (empty($subject)) $errors['subject'] = 'Veuillez entrer un sujet';
    if (empty($message)) $errors['message'] = 'Veuillez entrer votre message';
    
    if (empty($errors)) {
        // Envoyer l'email (à implémenter selon votre configuration)
        // $to = "contact@techlek.tn";
        // $headers = "From: $email";
        // mail($to, $subject, $message, $headers);
        
        $messageSent = true;
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechLek | Contact</title>
    <link rel="shortcut icon" href="img/SHOPINIlo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/contact.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Animation CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>



<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="img/SHOPINILOGO11.png" alt="TechLek" style="width: 150px; height: 45px">
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
                        <a class="nav-link" href="shop.php">Boutique</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contact.php">Contact</a>
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

    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container text-center contact-hero-content animate__animated animate__fadeIn">
            <h1 class="display-5 fw-bold mb-3">Contactez-nous</h1>
            <p class="lead mb-4">Nous sommes à votre écoute pour toute question ou demande d'information.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#contact-form" class="btn btn-primary btn-lg px-4 animate__animated animate__pulse animate__infinite animate__slower">
                    <i class="fas fa-paper-plane me-2"></i>Envoyer un message
                </a>
                <a href="tel:+21612345678" class="btn btn-outline-primary btn-lg px-4">
                    <i class="fas fa-phone me-2"></i>Nous appeler
                </a>
            </div>
        </div>
    </section>

    <section class="container mb-5">
        <?php if ($messageSent): ?>
        <div class="success-message animate__animated animate__fadeInDown">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-success fa-2x me-3"></i>
                </div>
                <div class="flex-grow-1">
                    <h4 class="text-success mb-1">Message envoyé avec succès!</h4>
                    <p class="mb-0">Nous vous répondrons dans les plus brefs délais.</p>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="contact-form animate__animated animate__fadeInLeft" id="contact-form">
                    <h2 class="mb-4">Formulaire de contact</h2>
                    <p class="text-muted mb-4">Remplissez ce formulaire et nous vous répondrons rapidement.</p>
                    
                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                        <div class="floating-label">
                            <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                                   id="name" name="name" placeholder=" " 
                                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                            <label for="name">Votre nom complet</label>
                            <?php if (isset($errors['name'])): ?>
                            <div class="error-message"><?= $errors['name'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="floating-label">
                            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                   id="email" name="email" placeholder=" " 
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            <label for="email">Adresse email</label>
                            <?php if (isset($errors['email'])): ?>
                            <div class="error-message"><?= $errors['email'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="floating-label">
                            <input type="text" class="form-control <?= isset($errors['subject']) ? 'is-invalid' : '' ?>" 
                                   id="subject" name="subject" placeholder=" " 
                                   value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">
                            <label for="subject">Sujet du message</label>
                            <?php if (isset($errors['subject'])): ?>
                            <div class="error-message"><?= $errors['subject'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="floating-label">
                            <textarea class="form-control <?= isset($errors['message']) ? 'is-invalid' : '' ?>" 
                                      id="message" name="message" rows="5" placeholder=" "><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                            <label for="message">Votre message</label>
                            <?php if (isset($errors['message'])): ?>
                            <div class="error-message"><?= $errors['message'] ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-3">
                            <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-5">
                <div class="map-container mb-4 animate__animated animate__fadeInRight">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d104888.66487015999!2d10.650525939067307!3d34.76136662969943!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x13002cda1486c695%3A0x22dfe0a62c50ce6f!2sSfax%2C%20Tunisia!5e0!3m2!1sen!2sbd!4v1710777146411!5m2!1sen!2sbd" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Section FAQ -->
    <section class="container mb-5">
        <div class="faq-section">
            <h2 class="text-center mb-4 fw-bold">Questions fréquentes</h2>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Question 1 -->
                    <div class="d-flex align-items-start mb-4">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-question text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-2">Comment suivre ma commande ?</h5>
                            <p class="text-muted mb-0">Connectez-vous à votre compte pour voir le statut ou utilisez le lien de suivi dans l'email de confirmation.</p>
                        </div>
                    </div>
                    
                    <!-- Question 2 -->
                    <div class="d-flex align-items-start mb-4">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-truck text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-2">Délais de livraison ?</h5>
                            <p class="text-muted mb-0">Livraison en 2-3 jours ouvrés en ville, 3-5 jours en région.</p>
                        </div>
                    </div>
                    
                    <!-- Question 3 -->
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-exchange-alt text-primary"></i>
                        </div>
                        <div>
                            <h5 class="mb-2">Politique de retour ?</h5>
                            <p class="text-muted mb-0">Retours acceptés sous 14 jours. Produit doit être neuf dans son emballage d'origine.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="newsletter-section">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="mb-3">Abonnez-vous à notre newsletter</h2>
                    <p class="text-muted mb-4">Recevez les dernières nouveautés et offres exclusives directement dans votre boîte email.</p>
                    
                    <form class="row g-2 justify-content-center">
                        <div class="col-md-8">
                            <input type="email" class="form-control form-control-lg" placeholder="Votre email">
                        </div>
                        <div class="col-md-4 d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">S'abonner</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <img src="img/SHOPINILOGO11.png" alt="TechLek" style="width: 400px; height: 110px" class="mb-3">
                    <p>
                        TechLek est votre destination premium pour les produits high-tech.
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
                        <li class="mb-2"><a href="shop.php?categorie=<?= urlencode($categorie) ?>"><?= $categorie ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="col-lg-4">
                    <h5 class="mb-3">Contact</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 Rue Tech, Sfax, Tunisie</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> contact@techlek.tn</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +216 12 345 678</li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4 bg-light">
            
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small mb-0">&copy; <?= date('Y') ?> TechLek. Tous droits réservés.</p>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Scroll doux pour les ancres
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
            
            // Validation en temps réel du formulaire
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('input', function(e) {
                    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                        if (e.target.value.trim() !== '') {
                            e.target.classList.remove('is-invalid');
                        }
                    }
                });
            }
            
            // Animation des éléments au scroll
            const animateOnScroll = function() {
                const elements = document.querySelectorAll('.animate__animated');
                elements.forEach(element => {
                    const elementPosition = element.getBoundingClientRect().top;
                    const screenPosition = window.innerHeight / 1.2;
                    
                    if (elementPosition < screenPosition) {
                        const animation = element.getAttribute('class').match(/animate__\w+/)[0];
                        element.classList.add(animation);
                    }
                });
            };
            
            window.addEventListener('scroll', animateOnScroll);
            animateOnScroll(); // Déclencher au chargement pour les éléments déjà visibles
        });
    </script>
</body>
</html>