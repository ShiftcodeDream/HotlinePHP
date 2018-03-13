# Logiciel de hotline
Développé par la Dream Team :
- Nawal Sehaki
- Adrien Dauvilaire
- Matthias Delamare

## Prérequis
### Base de données
Le logiciel requiert un moteur de base de données de type Mysql version 5 minimum.

### PHP
Ce logiciel requiert PHP 5.6 au minmumu. Son fonctionnement n'est pas garanti en deçà de cette version.
Le fonctionnement n'est pas garanti avec la version 7 de PHP

### Serveur web
Le logiciel est compatible avec les serveurs Apache et Nginx. IIS n'est pas supporté par l'application.

## Installation
Depuis phpMyAdmin, exécuter le script CreateDatabase.sql
Dans le dossier config, renommer le fichier credential-model.php en credential.php
Editer ce fichier en renseignant les valeurs adéquates pour se connecter à la base de données.

## Première utilisation
Le logiciel est fourni avec un premier identifiant, ayant les droits nécessaires à la création des autres utilisateurs.
Se connecter avec les identifiants suivants : 
    Identifiant : Denis
    Mot de passe : ni deux

Dans le menu, cliquer sur Ajouter un utilisateur pour créer les nouveaux utilisateurs.

## Documentation Technique
### Etats du ticket
Le ticket peut être dans les états suivants :
- 0 : Soumis : le ticket vient d'être saisi par l'utilisateur. Tant que le ticket n'est pas pris en compte, l'utilisateur peut modifier (reformuler) sa demande.
- 1 : Pris en charge. Ticket pris en charge par un technicien mais non résolu. A ce stade, seul le technicien est en mesure de modifier le ticket.
- 2 : Résolu. Le ticket est résolu, une description de la solution a été saisie par un technicien. Plus aucune modification ne peut être effectuée sur le ticket.

### Différences entre Urgence et impact
Dans une entreprise, un utilisateur peut considérer sa demande urgente, tout en étant faiblement
impactante pour l'entreprise. Par exemple, si l'utilisateur n'arrive pas à imprimer le bon de 
livraison à envoyer au client parce qu'une feuille est coincée dans son imprimante, il va considérer
sa demande comme urgente. Surtout s'il a d'autres documents à imprimer pour le travail qu'il est
amené à faire.
Maintenant, si cette imprimante est utilisée par l'ensemble de l'entreprise pour sortir tous les
documents relatifs à tous les clients (factures comprises), le problème devient nettement plus
impactant pour l'entreprise.
A contrario, si l'utilisateur peut imrpimer sur une autre imprimante ou s'il peut remettre à plus
tard la tâche, il peut considérer son problème comme peu urgent, malgré le fait qu'il soit
problématique pour l'entreprise.
Ainsi, le degré d'urgence vu de l'utilisateur peut être très différent du degré d'urgence vu de l'entreprise.
La priorité réelle de la demande est donc une combinaison de l'urgence utilisateur et de l'impact
réeel.
