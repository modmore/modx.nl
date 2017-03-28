# modx.nl

This repository contains the site hosted on [modx.nl](https://modx.nl) for the Dutch MODX community portal. 

## Contributing

Contributions are welcome! You can fork the repository, make changes in your own clone, and then send a pull request to this repository. When approved, it will deploy automatically. 

### Setting up

Here's in a nutshell how to set up the site on your own web server.

```` shell
git clone git@github.com:YOUR_USER_NAME/modx.nl.git
cd modx.nl
composer install
cp environment.sample.php environment.php
````

If you just want to run a local copy, and don't want to create your own fork, you can replace YOUR_USER_NAME with modmore in the first command. 

If you don't have Composer installed yet, [install that first](https://getcomposer.org/download/). 

At this point the site should be functional in the `www` folder. If you're having trouble getting it to work, please open an issue with details. 

### Configuration (`environment.php`)

When you created your environment.php file in the root of the project folder, you can make tweaks to that as necessary. If you don't need a value, don't remove it: just leave an empty value. 

- `SLIM_MODE`: set to `production` on production, and `development` on development.
- `TWIG_ENVIRONMENT` contains an array of options passed to twig. On production you could set `auto_reload` and `strict_variables` to `false` to get a minor performance boost, and to not trigger fatal errors on small issues.
- `SLIM_APP_OPTIONS` contains an array of options passed into Slim. Set `displayErrorDetails` to `false` on production to not expose critical information. Errors are logged into `/logs/app.log`. 
- `MEETUP_KEY` and `MEETUP_GROUP_PATH` are for the meetup.com integration. The key [can be found here](https://secure.meetup.com/meetup_api/key/), and the group path is the alias/slug of your meetup group, e.g. `modx-nederland`. 
- `GA_PROFILE` is the google analytics profile ID. When it's not empty the google analytics code is added to the footer of the page automatically. Don't set this on development ;)
- The `locale_set_default` call is used to set the right locale, and requires the `intl` php extension to be available. Feel free to remove 

### Folder Structure

The repository contains the following folders/files of note:

- `/container` and `/handlers` contain some set-up for the Slim app, including setting up the view handler (Twig) and a logger (Monolog).
- `/controllers` has controllers. MODX.nl is a simple site so there aren't many. Mostly you'll use the Html controller (from the routes) to parse a twig template and return that. 
- `/routes/routes.php` contains the accepted Slim routes
- `/templates` contains the twig templates. The `base.twig` template should be extended with the `{% block content %}` being provided in subtemplates. 
- `/www` is the actual webroot. It contains `/www/assets` (css, images) and a `.htaccess` and `index.php` that handle stuff.

Welcomed contributions include:

- Design/usability/accessibility to make it pretty; see `/www/assets` and `/templates` directories
- Content improvements/additions, mostly found in `/templates` as simple HTML

## Tech stack

As you may've noticed already, this site is a simple Slim 3 app, with Twig for templating. The production site is currently served on a fairly standard cPanel-powered server with PHP 5.6 and apache. 

Even though the site is about MODX, there's not actually any MODX (or a database) involved in the site. What you see is what you get.

