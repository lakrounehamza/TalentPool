
## - Installation et Déploiement
### - Prérequis
- PHP 8.3.14 installé
- MySQL et un serveur Web (Apache, Nginx)
- Composer et Node.js

### - Installation
1. **Cloner le projet** :
   ```sh
   git clone https://github.com/lakrounehamza/TalentPool.git
   cd TalentPool
   ```
2. **Installer les dépendances** :
   ```sh
   composer install
   npm install && npm run build
   ```
3. **Configurer l'environnement** :
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```
   Modifier `.env` pour connecter la base de données.
4. **Exécuter les migrations et seeders** :
   ```sh
   php artisan migrate --seed
   ```
5. **Démarrer le serveur** :
   ```sh
   php artisan serve
   ```

## - Sécurité et Performances
- **Chiffrement des mots de passe avec bcrypt**
- **Protection contre les attaques DDoS**
- **Optimisation des requêtes SQL pour des temps de réponse rapides**
