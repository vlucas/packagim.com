Packagim.com
============

Package Information Manager

Running Migrations
------------------

Migrations are automatically determined and run on the fly based on the current
Entity class definition in the static `fields` method vs. what the current
table structure looks like in the database.

The first thing you will have to do after cloning the repo is run the migratons
so that the database tables will be setup for you. You will also have to run
this command if you edit any of the entity fields or constraints on any of the
Entity classes.

```
php www/index.php -u db/migrate
```

If you also need to seed the database for development or testing, run:
```
php www/index.php -u db/seeds
```

If you need to start from a clean slate, run:
```
php www/index.php -u db/reset
```
**Note:** `db/reset` will NOT run on production to prevent accidental data loss

