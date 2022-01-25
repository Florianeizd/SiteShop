# e-commerce

### Récupérer le projer

```
$ git clone https://gitlab.com/edrichard/e-commerce.git
```

### Installation des containers 

```
$ docker-compose up -d
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
![Yarn install](doc/yarn-install.png)

3. Compilation des assets JS 

```
$ yarn dev
```
![Yarn dev](doc/yarn-dev.png)

4. Installation de la base de données 

Créer un fichier `.env.local`, ajouter les 4 variables suivantes :

- `APP_ENV` : mettre la valeur `dev`
- `DATABASE_URL` : renseigner l'url de connexion à la base de données `mysql://root:toor@127.0.0.1:3306/site-shop?serverVersion=5.7`
- `MAILER_DSN` : rensigner le DSN de connexion pour se connecter au SMTP de la boite au lettre `smtp://mailhog:1025` (mailHog)

Monter la base de données :

```
$ php bin/console doctrine:migrations:migrate
```

Jouer les fixtures : 

```
$ php bin/console doctrine:fixtures:load --append
```

### Lancer le serveur web

```
$ symfony serve
```

### Accéder aux différents containers

| Nom container (service) | Port de connexion | Url                                                 |
|:------------------------|:-----------------:|:----------------------------------------------------|
| Application             |       8000        | https://127.0.0.1:8000/ (ou http://127.0.0.1:8000/) |
| db (MySQL)              |       3306        | mysql://root:toor@127.0.0.1:3306                    |
| phpmyadmin              |        80         | http://localhost:8585/                              |
| mailhog (SMTP)          |       1025        | smtp://mailhog:1025                                 |
| mailhog (HTTP)          |       8025        | http://localhost:8025/                              |
