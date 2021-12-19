# e-commerce

### Récupérer le projer

```
$ git clone https://gitlab.com/edrichard/e-commerce.git
```

### Installation du projet 

1. Installation des assets PHP

```
$ composer install
```

2. Installation des assets JS 

```
$ yarn install
```

3. Compilation des assets JS 

```
$ yarn dev
```

4. Installation de la base de données 

Créer un fichier `.env.local`, ajouter les 3 variables suivantes :

- `APP_ENV` : mettre la valeur `dev`
- `DATABASE_URL` : renseigner l'url de connexion à la base de données `mysql://user:password@127.0.0.1:3306/db_name?serverVersion=5.7`
- `MAILER_DSN` : rensigner le DSN de connexion pour se connecter au SMTP de la boite au lettre `smtp://localhost:1025` (mailHog)

Monter la base de données :

```
$ php bin/console doctrine:migrations:migrate
```

Jouer les fixtures : 

```
$ php bin/console doctrine:fixtures:load
```

### Se connecter au site 

Rendez-vous sur la page : https://127.0.0.1:8000/
