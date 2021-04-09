# FairElection backend setup

**1) Download Composer dependencies**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```

You may alternatively need to run `php composer.phar install`, depending
on how you installed Composer.

**2) Start the symfony web server**

You can use Nginx or Apache, but Symfony's local web server
works even better - especially if you're using Docker for
the database.

To install the Symfony local web server, follow
"Downloading the Symfony client" instructions found
here: https://symfony.com/download - you only need to do this
once on your system.

Then, to start the web server, open a terminal, move into the
project, and run:

```
symfony serve -d
```

(If this is your first time using this command, you may see an
error that you need to run `symfony server:ca:install` first).

**3) Database Setup (with Docker)**

The easiest way to set up the database is to use the `docker-compose.yaml`
file that's included in this project. First, make sure [Docker Compose](https://docs.docker.com/compose/install/) is downloaded
and running on your machine. Then, from inside the project, run:

```
docker-compose up -d
```

Congrats! You now have a database running! And as long as you use the
"symfony binary" web server (described below), the `DATABASE_URL`
environment variable will automatically be exposed to your web server:
no need to configure `.env`.

**3 Alternative Database Setup (without Docker)**

If you do not want to use Docker, you can also just install and run
MySQL manually. When you're done, open the `.env` file and make any
adjustments you need - specifically `DATABASE_URL`. Or, better,
you can create a `.env.local` file and *override* any configuration
you need there (instead of changing `.env` directly).

** 4) Database Schema**

To actually *create* the database and get some tables, run:

```
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
```

If you get an error that the database exists, that should
be ok. But if you have problems, completely drop the
database (`doctrine:database:drop --force`) and try again.
