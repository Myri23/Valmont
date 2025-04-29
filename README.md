# README - Valmont

## 🌐 Présentation du projet

Valmont est une plateforme de ville intelligente (Smart City) qui combine des fonctionnalités d'information locale et de gestion d'objets connectés IoT. Le projet permet de découvrir les lieux d'intérêt, événements culturels et moyens de transport de la ville, tout en offrant une interface de gestion pour les objets connectés urbains (lampadaires, poubelles intelligentes, parkings).

Il a été réalisé dans le cadre d'un projet de développement web, avec une organisation structurée pour faciliter la navigation et l'évolution du site.

---

## Table des Matières

1. [Installation](#installation)
2. [Fonctionnalités principales](#fonctionnalités-principales)
3. [Guide d'utilisation](#guide-dutilisation)
4. [Architecture du projet](#architecture-du-projet)
5. [Fonctionnalités détaillées](#fonctionnalités-détaillées)
6. [FAQ](#faq)
7. [Contributeurs](#contributeurs)

---

## Installation
### Prérequis
1. Avoir php installé (Version recommandé : PHP 8.1 ou plus). Tester avec :
    ```bash
    php -v
    ```
2. Avoir Composer installé. Composer gère les dépendances PHP. Tester avec :
    ```bash
    composer --version
    ```
- Sinon installer avec :
    ```bash
    sudo apt install composer
    ```
3. Installer symfony. Pour lancer le serveur local Symfony. Tester avec :
    ```bash
    symfony -v
    ```
- Sinon installer avec :
    ```bash
    wget https://get.symfony.com/cli/installer -O - | bash
    ```
- Puis ajouter ceci au .bachrc ou .zshrc :
    ```bash
    export PATH="$HOME/.symfony/bin:$PATH"
    ```    
4. Extension PHP intl (Internationalization) installée et activée. Sous Ubuntu/Debian :
    ```bash
    sudo apt install php-intl
    ```    
5. Installer le transport mailer spécifique pour Gmail :
    ```bash
    composer require symfony/google-mailer
    ```

### Étapes d'installation
1. Clonez le dépôt Git :
    ```bash
    git clone git@github.com:myri23/Valmont.git
    ```
2. Ouvrez le dossier du projet dans votre éditeur :
    ```bash
    cd Valmont
    ```
3. Installer les dépendances avec Composer :
   ```bash
   composer install
   ```
4. Lancer le serveur symfony:
   ```bash
   symfony serve
   ```
5. Puis aller sur : 
    ```bash
    https://127.0.0.1:8000
    ```

---

## Fonctionnalités principales

Le site Valmont propose plusieurs fonctionnalités clés qui combinent information locale et gestion d'objets connectés IoT :

### Fonctionnalités d'information locale
- **Lieux d'intérêt** : Musées, parcs, restaurants et bibliothèques
- **Événements locaux** : Festivals, marchés, concerts et spectacles culturels
- **Transports publics** : Information sur les bus, tramways et métros avec horaires
- **Météo actuelle** : Affichage des conditions météorologiques en temps réel

### Fonctionnalités de gestion des objets connectés IoT
- **Gestion des lampadaires intelligents** : Programmation d'éclairage automatisé avec suivi de la batterie
- **Gestion des poubelles connectées** : Surveillance du niveau de remplissage et alertes
- **Gestion des parkings intelligents** : Suivi en temps réel des disponibilités de stationnement
- **Tableau de bord d'administration** : Vue d'ensemble et statistiques sur les objets connectés

### Fonctionnalités utilisateur
- **Système d'authentification** : Inscription et connexion des utilisateurs avec différents niveaux d'accès
- **Profil utilisateur** : Gestion des informations personnelles
- **Administration** : Gestion des utilisateurs et des objets connectés

---

## Guide d'utilisation

- La page d'accueil présente les quatre sections principales : Lieux d'intérêt, Événements locaux, Transports publics et Météo actuelle
- Chaque section possède un bouton permettant d'accéder à des informations plus détaillées
- Le menu de navigation en haut permet d'accéder rapidement aux différentes fonctionnalités

### Inscription et connexion
1. Cliquez sur "S'inscrire" en haut à droite pour créer un compte
2. Remplissez le formulaire avec vos informations personnelles
3. Confirmez votre inscription via l'email reçu
4. Connectez-vous via le bouton "Se connecter" avec vos identifiants

### Consultation des informations
- **Lieux d'intérêt** : Cliquez sur "Explorer les lieux" pour découvrir les points d'intérêt
- **Événements locaux** : Cliquez sur "Voir les événements" pour consulter l'agenda culturel
- **Transports publics** : Utilisez les boutons Bus, TramWay ou Metro pour accéder aux horaires spécifiques
- **Météo** : Consultez les informations météorologiques actuelles directement sur la page d'accueil

---

## Architecture du projet

Le projet Valmont est construit avec le framework Symfony et suit une architecture MVC (Modèle-Vue-Contrôleur) :

### Structure des dossiers
- `/src/Controller` : Contient les contrôleurs qui gèrent les requêtes
- `/src/Entity` : Définit les entités (modèles) de l'application
- `/src/Repository` : Contient les classes permettant d'interagir avec la base de données
- `/templates` : Contient les vues Twig de l'application
- `/assets` : Contient les ressources accessibles publiquement (CSS, JS, images)
- `/config` : Contient les fichiers de configuration

### Base de données
- Base de données relationnelle avec plusieurs tables pour les utilisateurs, lieux, événements, transports et objets connectés
- Utilisation de Doctrine ORM pour la gestion des entités et des relations
- Entités principales : User, Lieu, Evenement, Transport, ObjetConnecte, Lampadaire, Poubelle, Parking

### Architecture IoT
- Communication avec les objets connectés via API REST
- Stockage des données de capteurs dans des tables dédiées
- Système d'authentification sécurisé pour les objets connectés
- Gestion des alertes et notifications en temps réel

---

## Fonctionnalités détaillées

### Page d'accueil
- Présentation visuelle de la ville avec une image panoramique
- Navigation intuitive vers les différentes sections principales
- Message de bienvenue avec une brève description
- Bouton "Découvrir" pour en apprendre davantage

### Section Lieux d'intérêt
- Présentation des différentes catégories de lieux (musées, parcs, restaurants, bibliothèques)
- Description détaillée pour chaque lieu
- Fonctionnalité de recherche par catégorie ou mot-clé
- Possibilité de noter et commenter les lieux visités (utilisateurs connectés)

### Section Événements locaux
- Calendrier des événements à venir
- Filtrage par date, type d'événement ou lieu
- Informations détaillées pour chaque événement (date, heure, lieu, description)
- Option pour ajouter des événements à un agenda personnel (utilisateurs connectés)

### Section Transports publics
- Information sur les horaires des transports

### Section Météo
- Affichage des conditions météorologiques actuelles
- Température, humidité et vitesse du vent
- Prévisions pour les prochaines heures/jours
- Alertes météorologiques si nécessaire

### Gestion des objets connectés
- **Vue d'ensemble** : Liste de tous les objets connectés avec leur statut et localisation
- **Lampadaires intelligents** : Programmation des horaires d'allumage et visualisation de l'état
- **Poubelles connectées** : Surveillance du niveau de remplissage et gestion des alertes
- **Parkings intelligents** : Suivi des places disponibles en temps réel
- **Ajout d'objets** : Interface pour ajouter et configurer de nouveaux objets connectés

### Section Visualisation
- Visualisation intuitive de l'ensemble des objets connectés
- Filtrage par type d'objet, état et localisation
- Affichage des dernières interactions et de l'état de la batterie
- Interface optimisée pour le monitoring en temps réel

### Espace administrateur
- Tableau de bord principal avec accès à la gestion des utilisateurs et des objets
- Gestion complète des utilisateurs (ajout, modification, suppression)
- Niveaux d'accès différenciés (administrateur, expert, débutant, visiteur)
- Outils d'administration avancés (historique des connexions, statistiques, exportation de rapports)
- Sauvegarde et vérification de l'intégrité des données

---

## FAQ

**Q: Comment ajouter un nouvel événement au calendrier ?**  
R: Seuls les administrateurs peuvent ajouter de nouveaux événements via l'interface d'administration.

**Q: Comment signaler un problème sur le site ?**  
R: Utilisez le formulaire de contact accessible depuis le menu "Information" ou contactez directement l'équipe de développement.

**Q: Est-il possible de personnaliser mon expérience utilisateur ?**  
R: Oui, une fois connecté, vous pouvez personnaliser votre profil et vos préférences d'affichage.

**Q: Comment ajouter un nouvel objet connecté à la plateforme ?**  
R: Dans la section "Gestion", cliquez sur "Ajouter un objet connecté" puis sélectionnez le type d'objet (lampadaire, poubelle, parking) et complétez les informations requises.

**Q: À quoi correspondent les niveaux d'utilisateurs (expert, débutant) ?**  
R: Les niveaux définissent les permissions d'accès aux fonctionnalités. Les experts ont accès à toutes les fonctionnalités de gestion des objets connectés, tandis que les débutants ont un accès limité.

**Q: Comment sont surveillées les poubelles connectées ?**  
R: Les poubelles connectées disposent de capteurs qui mesurent leur niveau de remplissage. Ces données sont transmises à la plateforme Valmont qui peut envoyer des alertes lorsqu'une poubelle nécessite d'être vidée.

**Q: Que signifient les indicateurs verts et rouges dans la liste des objets connectés ?**  
R: L'indicateur vert signifie que l'objet est en ligne et fonctionne correctement. L'indicateur rouge permet de désactiver temporairement l'objet ou indique qu'il est hors service.

---

## Contributeurs
- **Balit Ilian**
- **Georgel Eleonore**
- **Ribar Ines**
- **Saadi Myriam**
- **Zerrouki Abbes**
