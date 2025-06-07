<?php
require_once(__DIR__ . '/../pdo.php');

class Produit
{
    public $image;
    private $allowed_categories = ['PC', 'Mobile', 'Accessoire PC','watch','Tablette','Accessoire Mobile',''];

    public function listerProduits()
    {
        try {
            $cnx = new Connexion();
            $pdo = $cnx->CNXbase();
            $sql = "SELECT * FROM produits LIMIT 12";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des produits: " . $e->getMessage());
            return [];
        }
    }

    public function listerProduitsParCategorie($categorie)
    {
        try {
            if (!$this->isValidCategory($categorie)) {
                throw new InvalidArgumentException("Catégorie invalide");
            }

            $cnx = new Connexion();
            $pdo = $cnx->CNXbase();
            $sql = "SELECT * FROM produits WHERE categorie = :categorie";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['categorie' => $categorie]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération par catégorie: " . $e->getMessage());
            return [];
        }
    }


    public function selectionnerCategories()
    {
        try {
            $cnx = new Connexion();
            $pdo = $cnx->CNXbase(); 
            $sql = "SELECT DISTINCT categorie FROM produits";
            $stmt = $pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des catégories: " . $e->getMessage());
            return $this->allowed_categories; // Retourne les catégories par défaut en cas d'erreur
        }
    }

    public function ajouterProduit($nom, $description, $categorie, $prix, $stock, $image)
    {
        try {
            if (!$this->isValidCategory($categorie)) {
                throw new InvalidArgumentException("Catégorie invalide: " . $categorie);
            }

            $cnx = new Connexion();
            $pdo = $cnx->CNXbase();
            $sql = "INSERT INTO produits (nom_produit, description, categorie, prix, stock, image) 
                    VALUES (:nom, :description, :categorie, :prix, :stock, :image)";
            
            $stmt = $pdo->prepare($sql);
            
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':categorie', $categorie, PDO::PARAM_STR);
            $stmt->bindParam(':prix', $prix, PDO::PARAM_STR);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindParam(':image', $image, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout du produit: " . $e->getMessage());
            throw $e;
        }
    }

    public function modifierProduit($id, $nom, $description, $categorie, $prix, $stock, $image)
    {
        try {
            if (!$this->isValidCategory($categorie)) {
                throw new InvalidArgumentException("Catégorie invalide");
            }

            $cnx = new Connexion();
            $pdo = $cnx->CNXbase();
            $sql = "UPDATE produits SET 
                    nom_produit = :nom, 
                    description = :description, 
                    categorie = :categorie, 
                    prix = :prix, 
                    stock = :stock, 
                    image = :image 
                    WHERE id_produit = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':categorie', $categorie, PDO::PARAM_STR);
            $stmt->bindParam(':prix', $prix, PDO::PARAM_STR);
            $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);
            $stmt->bindParam(':image', $image, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la modification du produit: " . $e->getMessage());
            throw $e;
        }
    }

    public function supprimerProduit($id)
    {
        try {
            $cnx = new Connexion();
            $pdo = $cnx->CNXbase();
            $sql = "DELETE FROM produits WHERE id_produit = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression du produit: " . $e->getMessage());
            throw $e;
        }
    }

    public function getProduct($id)
    {
        try {
            $cnx = new Connexion();
            $pdo = $cnx->CNXbase();
            $sql = "SELECT * FROM produits WHERE id_produit = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération du produit: " . $e->getMessage());
            return false;
        }
    }

    private function isValidCategory($category)
    {
        return in_array($category, $this->allowed_categories);
    }
}
?>