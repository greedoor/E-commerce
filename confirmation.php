<?php
session_start();
require_once('user.class.php');
$us = new user();
$x = $us->get_user($_SESSION['email']);

// Récupé les données de la commande
$total = isset($_SESSION['total']) ? number_format($_SESSION['total'], 2, ',', ' ') : '194,00';
$transaction_id = substr(md5(uniqid()), 0, 8);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="img/SHOPINIlo.png" type="image/x-icon">
    <title>SHOP'ini - Paiement sécurisé</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/confirmation.css">
</head>

<body>
    <div class="container py-4">
        <div class="payment-container">
            <div class="payment-header">
                <h2><i class="fas fa-lock"></i> Paiement sécurisé</h2>
            </div>
            
            <div class="payment-body">
                <div class="payment-section">
                    <div class="section-title">
                        <i class="fas fa-credit-card"></i> Méthode de paiement
                    </div>
                    
                    <div class="payment-methods">
                        <div class="payment-method active" data-method="card">
                            <img src="img/banktun.jpg" alt="Carte Bancaire">
                            <div class="method-name">Carte Bancaire</div>
                        </div>
                        <div class="payment-method" data-method="paypal">
                            <img src="img/paypal1.jpg" alt="PayPal">
                            <div class="method-name">PayPal</div>
                        </div>
                    </div>
                    
                    <div class="card-form">
                        <div class="form-group">
                            <label class="form-label">Numéro de carte</label>
                            <input type="text" class="form-control" placeholder="1234 5678 9012 3456" maxlength="19">
                        </div>
                        
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label">Date d'expiration</label>
                                    <input type="text" class="form-control" placeholder="MM/AA" maxlength="5">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label class="form-label">Code de sécurité (CVV)</label>
                                    <input type="text" class="form-control" placeholder="123" maxlength="3">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Nom sur la carte</label>
                            <input type="text" class="form-control" placeholder="Nom Prénom">
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="saveCard">
                            <label class="form-check-label" for="saveCard">
                                Enregistrer cette carte pour mes prochains achats
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="payment-section">
                    <div class="section-title">
                        <i class="fas fa-receipt"></i> Récapitulatif de commande
                    </div>
                    
                    <div class="payment-summary">
                        <div class="summary-item">
                            <span>Sous-total</span>
                            <span><?= $total ?> €</span>
                        </div>
                        <div class="summary-item">
                            <span>Livraison</span>
                            <span>0,00 €</span>
                        </div>
                        <div class="summary-item summary-total">
                            <span>Total TTC</span>
                            <span><?= $total ?> €</span>
                        </div>
                    </div>
                </div>
                
                <div class="payment-section">
                    <div class="section-title">
                        <i class="fas fa-info-circle"></i> Détails de la transaction
                    </div>
                    
                    <div class="transaction-details">
                        <div class="transaction-item">
                            <span>Marchand:</span>
                            <span>SHOP'ini</span>
                        </div>
                        <div class="transaction-item">
                            <span>N° de transaction:</span>
                            <span><?= $transaction_id ?></span>
                        </div>
                    </div>
                </div>
                
                <button class="btn-pay" id="payButton">
                    <i class="fas fa-lock"></i> Payer <?= $total ?> €
                </button>
                
                <div class="secure-payment">
                    <i class="fas fa-shield-alt"></i>
                    <span>Paiement 100% sécurisé - Vos données sont cryptées</span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sélection de la méthode de paiement
        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
                this.classList.add('active');
                
                // Masquer/afficher les formulaires selon la méthode choisie
                // (à implémenter selon vos besoins)
            });
        });
        
        // Formatage du numéro de carte
        const cardNumberInput = document.querySelector('input[placeholder="1234 5678 9012 3456"]');
        cardNumberInput.addEventListener('input', function(e) {
            let value = this.value.replace(/\s+/g, '');
            if (value.length > 0) {
                value = value.match(new RegExp('.{1,4}', 'g')).join(' ');
            }
            this.value = value;
        });
        
        // Formatage de la date d'expiration
        const expDateInput = document.querySelector('input[placeholder="MM/AA"]');
        expDateInput.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            this.value = value;
        });
        
        // Gestion du bouton de paiement
        document.getElementById('payButton').addEventListener('click', function() {
            // Validation et soumission du formulaire
            // (à implémenter selon votre logique de paiement)
            alert('Paiement en cours de traitement...');
        });
    </script>
</body>
</html>