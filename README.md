# modx.nl

This repository contains the site hosted on [modx.nl](https://modx.nl) for the Dutch MODX community portal. 

## Contributing

Contributions are welcome! You can fork the repository, make changes in your own clone, and then send a pull request to this repository. When approved, it will deploy automatically. 

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

