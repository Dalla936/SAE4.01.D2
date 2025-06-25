# SAE 4.01 – Gestion des jeux de société

Version améliorée du projet S3.01 – BUT Informatique, Université Sorbonne Paris Nord (Groupe D2)

## 🎯 Objectif

Créer une application web MVC maison (sans framework) pour gérer une collection de 17 000 jeux.  
Refonte du projet S3.01 avec une meilleure organisation du code, plus de fonctionnalités, et une documentation par compétence.

## 👥 Équipe

- FERTIKH Marewane
- BELLOUCH Iliasse
- BEN MANSOUR Habib
- DIALLO Dalla

## 📁 Structure

- `Modele_MVC/controleurs/` → contrôleurs PHP
- `Modele_MVC/modele/` → modèles et accès à la base de données
- `Modele_MVC/Vue/` → vues HTML / PHP / CSS
- `Modele_MVC/img/` → images du projet
- `/scripts` → script pour intégrer la base de données 


## 🔗 Liens utiles

- [Dépôt public S3.01](https://github.com/marewane-fertikh/SAE_4.01_D2_Public)

## 🚀 Installation locale

### Prérequis

- **Serveur local** : MAMP, XAMPP ou LAMP
- **PostgreSQL** : Version 16 ou 17
- **PHP** : Compatible avec votre serveur local
- **Git** : Pour cloner le projet

### Étapes d'installation

1. **Cloner le projet**
   ```bash
   cd /path/to/your/htdocs
   # Pour MAMP : /Applications/MAMP/htdocs/
   # Pour XAMPP : /Applications/XAMPP/htdocs/ (macOS) ou C:\xampp\htdocs\ (Windows)
   # Pour LAMP : /var/www/html/
   
   git clone https://github.com/Dalla936/SAE4.01.D2.git
   cd SAE_4.01.D2/Modele_MVC/
   ```

2. **Configuration de la base de données**
   
   Créer une base de données PostgreSQL :
      ```sql

    psql -U postgres;
      ```


    Puis ensuite créer la base de données via l'utilisateur postgres : 
       ```sql

       CREATE DATABASE database_jeu;
       ```
   - **Nom de la base** : `database_jeu`
   - **Utilisateur** : `postgres`
   - **Mot de passe** : `dalla`

3. **Importation des données**
   
   a. Récupérer le script d'importation depuis le dépôt Git dans scripts
   
   b. Se connecter à PostgreSQL via SQL Shell (psql) :
   ```bash
   psql -U postgres -d database_jeu;
   ```
   
   c. Exécuter le script d'importation :
   ```sql
      \i ../scripts/scriptD2S4.sql;
   ```

4. **Démarrer votre serveur local**
   - Démarrer MAMP/XAMPP/LAMP
   - Accéder au projet via `http://localhost:(le port alloué)/SAE_4.01_D2/Modele_MVC/Vue/connexion.html`

### Configuration

Assurez-vous que les paramètres de connexion à la base de données dans votre application correspondent aux informations configurées :
- Host : `localhost`
- Database : `database_jeu`
- Username : `postgres`
- Password : `dalla`

## ✅ Avancement

- ✅ Architecture MVC maison opérationnelle
- ✅ Pages refaites à partir de la maquette Figma
- ✅ Nouvelle base de données intégrée
- ✅ Intégration de toutes les fonctionnalités finales
