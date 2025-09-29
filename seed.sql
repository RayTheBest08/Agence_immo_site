USE agence_immo;
INSERT INTO categories (nom) VALUES ('Propriété'),('Voiture'),('Terrain');
INSERT INTO types_transaction (nom) VALUES ('Vente'),('Location');

-- Créez d'abord un compte administrateur via phpMyAdmin ou en exécutant un petit script PHP pour hacher le mot de passe.
-- Exemple PHP pour générer un hash (à exécuter localement) :
-- <?php echo password_hash('admin123', PASSWORD_DEFAULT); ?>

-- Exemple d'annonces (nécessitent des users existants : remplacez user_id par des IDs valides)
-- INSERT INTO annonces (user_id,category_id,type_transaction_id,titre,description,prix,ville,created_at) VALUES
-- (1,1,1,'Maison 3 chambres','Belle maison proche du centre',120000,'Lomé',NOW()),
-- (1,2,2,'Toyota Corolla 2018','Voiture en bon état',500,'Lomé',NOW()),
-- (1,3,2,'Terrain 500m2','Terrain plat proche route',20000,'Kara',NOW());
