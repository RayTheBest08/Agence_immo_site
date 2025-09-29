# Site Agence Immobilière

## Installation locale
1. Copier le dossier `agence_immo_site` dans le dossier `www` (XAMPP) ou `htdocs`.
2. Importer `schema.sql` dans phpMyAdmin ou via MySQL pour créer la base.
3. Créer un utilisateur admin (role='admin') manuellement ou via un script PHP pour générer le mot de passe haché.
4. Configurer `config.php` (hôte, utilisateur, mot de passe, nom DB).
5. Donner les droits en écriture sur `assets/uploads/`.
6. Lancer le serveur et ouvrir `http://localhost/agence_immo_site/index.php`.

## Notes
- Les fichiers `edit_profile.php`, `admin.php`, et `admin_export.php` sont inclus.
- Pour le seed, générez les hash PHP localement et insérez les utilisateurs via phpMyAdmin si besoin.
- En production : forcer HTTPS, désactiver l'affichage des erreurs, et configurer la sécurité (limitation des uploads, vérification MIME, protection contre bruteforce).

## Améliorations possibles
- Pagination et recherche avancée.
- UI admin améliorée avec styles et filtre.
- Email notifications et validation.
