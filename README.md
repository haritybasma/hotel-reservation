# Hotel Reservation

Petite application web de réservation de chambres d'hôtel, faite en PHP avec la programmation orientée objet et PDO (MySQL).

Je l'ai réalisée pour réviser les notions vues en LST Ingénierie Informatique (FST Mohammedia) : PHP de base, POO et connexion à une base de données avec PDO.

## Fonctionnalités

- liste des chambres avec filtre par type (Simple, Double, Suite) et nombre de chambres libres
- connexion avec session, option « se souvenir de mon login » (cookie de 30 jours), déconnexion
- réservation d'une chambre libre pour 1 à 10 nuits, le montant est calculé automatiquement
- liste des réservations du client connecté, modification du nombre de nuits, suppression avec confirmation
- une chambre se libère quand sa réservation est supprimée
## Captures d'écran

### Liste des chambres
<img width="474" height="310" alt="image" src="https://github.com/user-attachments/assets/4a9366fe-d832-453d-b1c6-41b0f84b4dcb" />

### Réservation d'une chambre
<img width="464" height="204" alt="image" src="https://github.com/user-attachments/assets/86ecfe5a-9f2e-433f-876a-edc7da8db29c" />


### Mes réservations
<img width="476" height="197" alt="image" src="https://github.com/user-attachments/assets/663c5185-dda5-453a-8aed-f8f076aa3b62" />

## Ce que j'ai utilisé

- **POO** : classes `Chambre`, `ChambreDouble` (héritage) et `Reservation`, propriétés privées, accesseurs, règles de gestion écrites dans les classes (prix entre 300 et 3000 DH, durée de 1 à 10 nuits, pas de double réservation)
- **PDO** : connexion avec `try / catch`, requêtes préparées pour toutes les valeurs venant du navigateur, transactions quand il y a deux requêtes à la suite
- **Sessions et cookies** : `$_SESSION`, `setcookie`, redirections avec `header`
- **Sécurité de base** : mots de passe hachés (`password_hash` et `password_verify`), `htmlspecialchars` à l'affichage, aucune erreur technique affichée à l'utilisateur, une modification de la base passe toujours par un formulaire POST, chaque client ne voit et ne modifie que ses propres réservations

## Installation (WAMP ou XAMPP)

1. Copier le dossier dans `C:\wamp64\www\` (ou `C:\xampp\htdocs\`).
2. Dans phpMyAdmin, ouvrir l'onglet SQL et exécuter le fichier `sql/hotel.sql`. Il crée la base `hotel_resa_db`, les tables, des données de test et un compte MySQL pour l'application.
3. Copier `includes/config.example.php` en `includes/config.php`. Si vous gardez le compte créé par le script, il faut y mettre `hotel_resa_user` et `hotel_resa_pass`.
4. Ouvrir `http://localhost/hotel-reservation/`.

## Comptes de démonstration

| Login | Mot de passe |
|---|---|
| sara | s1234 |
| yassir | y5678 |

Ce sont des mots de passe de test, valables seulement sur une installation locale.

## Structure

```
hotel-reservation/
├── classes/        Chambre.php, ChambreDouble.php, Reservation.php
├── includes/       config.php, auth.php, header.php, footer.php
├── css/            style.css
├── sql/            hotel.sql
├── chambres.php    liste et filtre des chambres
├── connexion.php, verifier.php, espace.php, deconnexion.php
├── reserver.php, liste.php, modifier.php, supprimer.php
└── test-reservations.php   petit script pour tester les classes
```

## Pistes pour la suite

- permettre le choix des dates au lieu d'un nombre de nuits
- gérer le colocataire d'une chambre double dans l'interface (la classe `ChambreDouble` le prévoit déjà)
- ajouter une protection CSRF sur les formulaires
- refaire le même projet avec SQL Server en ne modifiant que `config.php`

## Auteure

Basma Harity - LST Ingénierie Informatique, FST Mohammedia
