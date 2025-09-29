<?php
require 'config.php';
require 'functions.php';

// vérifier admin
$stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
$stmt->execute([current_user_id()]);
if ($stmt->fetchColumn() !== 'admin') { flash('Accès refusé','danger'); header('Location: index.php'); exit; }

$fname = 'export_annonces_' . date('Ymd_His') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $fname);
$out = fopen('php://output', 'w');
fputcsv($out, ['ID','Titre','Type','Transaction','Prix','Ville','Statut','Propriétaire','Date']);

$q = $pdo->query("SELECT a.id,a.titre,c.nom AS category,t.nom AS transaction,a.prix,a.ville,a.statut_disponibilite,u.nom AS owner,a.created_at FROM annonces a JOIN categories c ON a.category_id=c.id JOIN types_transaction t ON a.type_transaction_id=t.id JOIN users u ON a.user_id=u.id ORDER BY a.created_at DESC");
while($row = $q->fetch(PDO::FETCH_ASSOC)){
    fputcsv($out, [$row['id'],$row['titre'],$row['category'],$row['transaction'],$row['prix'],$row['ville'],$row['statut_disponibilite'],$row['owner'],$row['created_at']]);
}
fclose($out);
exit;
