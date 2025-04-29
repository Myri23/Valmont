# README - Valmont

## 🌐 Présentation du projet

Ce projet est un site web permettant de découvrir les différents lieux, événements culturels et moyens de transport d'une ville. 

Il a été réalisé dans le cadre d’un projet de développement web, avec une organisation structurée pour faciliter la navigation et l’évolution du site.

---

## Table des Matières

1. [Installation](#installation)
2. [Fonctionnalités principales](#fonctionnalités-principales)
3. [Guide d'utilisation](#guide-dutilisation)
4. [Fonctionnalités de la page d'acceuil](#fonctionnalités-de-la-page-dacceuil)
5. [FAQ](#faq)
6. [Contributeurs](#contributeurs)

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
4. Effectuer la commande pour la génération de rapports :
   ```bash
   composer require dompdf/dompdf
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

Ici serons noté les principales fonctionnalitées

---

## Guide d'utilisation

Ici, si besoin un guide d'utilisation.

---

## Fonctionnalités de la page d'acceuil

Fonctionnalités de la page d'acceuil

---


## Contributeurs
- **Balit Ilian**
- **Georgel Eleonore**
- **Ribar Ines**
- **Saadi Myriam**
- **Zerrouki Abbes**
