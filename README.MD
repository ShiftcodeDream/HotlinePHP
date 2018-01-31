# Logiciel de hotline

## Installation
Depuis phpMyAdmin, exécuter le script CreateDatabase.sql
Dans le dossier config, renommer le fichier credential-model.php en credential.php
Editer ce fichier en renseignant les valeurs adéquates pour se connecter à la base de données.

## Première utilisation
Le logiciel est fourni avec un premier identifiant, ayant les droits nécessaires à la création des autres utilisateurs.
Se connecter avec les identifiants suivants : 
    Identifiant : Denis
    Mot de passe : ni deux

Dans le menu, cliquer sur Nouvel utilisateur pour créer les nouveaux utilisateurs.

## Documentation Technique
### Etats du ticket
Le ticket peut être dans les états suivants :
- 0 : Soumis : le ticket vient d'être saisi par l'utilisateur
- 1 : Pris en charge. Ticket pris en charge par un technicien mais non résolu.
- 2 : Résolu. Le ticket est résolu, une description de la solution a été saisie par un technicien.

