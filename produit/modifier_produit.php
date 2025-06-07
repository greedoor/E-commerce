<?php
session_start();
require_once('produit.class.php');


if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "ID de produit invalide";
    header('Location: ../dashboard/dashboard.php');
    exit;
}

$pr = new Produit();
$product = $pr->getProduct($_GET['id']);


$id = $product['id_produit'];
$nom = $product['nom_produit']; 
$desc = $product['description']; 
$cat = $product['categorie']; 
$prix = $product['prix']; 
$stock = $product['stock'];
$image = $product['image'];
$categories = $pr->selectionnerCategories();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOP'ini | Modifier Produit</title>
    <link rel="shortcut icon" href="../img/SHOPINIlo.png" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/modifier_produit.css">
</head>
<body>
    <div class="container py-4">
        <div class="form-container">
            <h2><i class="fas fa-edit me-2"></i>Modifier le produit</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form action="update_Product.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <input type="hidden" name="current_image" value="<?= htmlspecialchars($image) ?>">
                
                <!-- Section Image -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Image actuelle</label>
                            <?php if ($image && file_exists("../img/".$image)): ?>
                                <div>
                                    <img src="../img/<?= htmlspecialchars($image) ?>" class="current-image" alt="Image actuelle du produit">
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">Aucune image disponible</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nouvelle image</label>
                            <p class="text-muted small mb-3">Formats acceptés: JPG, PNG, GIF (max 2MB)</p>
                            <div class="file-upload">
                                <label class="file-upload-btn">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: var(--primary);"></i>
                                    <div>Cliquez pour télécharger une nouvelle image</div>
                                    <input type="file" class="file-upload-input" name="image" accept="image/jpeg, image/png, image/gif">
                                </label>
                            </div>
                        </div>
                    </div> -->
                </div>

                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nom du produit *</label>
                            <input type="text" class="form-control" name="nom" 
                                   value="<?= htmlspecialchars($nom) ?>" required minlength="3" maxlength="255">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Catégorie *</label>
                            <select class="form-select" name="categorie" required>
                                <option value="">Sélectionnez une catégorie</option>
                                <?php foreach (['PC', 'Mobile', 'Accessoire PC','watch', 'Tablette', 'Accessoire Mobile'] as $category): ?>
                                    <option value="<?=$category ?>" 
                                        <?= ($category == $cat) ? 'selected' : '' ?>>
                                        <?= $category ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Prix (€) *</label>
                            <input type="number" step="0.01" class="form-control" name="prix" 
                                   value="<?= htmlspecialchars($prix) ?>" required min="0.01" max="9999.99">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Stock *</label>
                            <input type="number" class="form-control" name="stock" 
                                   value="<?= htmlspecialchars($stock) ?>" required min="0" max="9999">
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="form-label">Description *</label>
                    <textarea class="form-control" name="description" rows="5" required><?= htmlspecialchars($desc) ?></textarea>
                </div>

                <!-- Boutons -->
                <div class="d-flex justify-content-between mt-4">
                    <a href="../dashboard/produit.php" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
        document.querySelector('.file-upload-input').addEventListener('change', function() {
            const fileName = this.files[0] ? this.files[0].name : "Aucun fichier sélectionné";
            const uploadBtn = document.querySelector('.file-upload-btn');
            
            if (this.files[0]) {
                const fileSize = (this.files[0].size / (1024*1024)).toFixed(2); // Taille en MB
                uploadBtn.innerHTML = `
                    <i class="fas fa-check-circle fa-2x mb-2" style="color: var(--secondary);"></i>
                    <div>${fileName}</div>
                    <small class="text-muted">${fileSize} MB - Cliquez pour changer</small>
                `;
            } else {
                uploadBtn.innerHTML = `
                    <i class="fas fa-cloud-upload-alt fa-2x mb-2" style="color: var(--primary);"></i>
                    <div>Cliquez pour télécharger une nouvelle image</div>
                `;
            }
        });
    </script>
</body>
</html>