# Getting started with deGov development

### Requirements

The development workflow uses [ddev](https://ddev.readthedocs.io/en/stable/). Please refer to ddev docs for requirements. In short you will need to use:

* [ddev](https://ddev.readthedocs.io/en/stable/)
* [docker](https://docs.docker.com/get-docker/)
* [git](https://git-scm.com/docs)
* [PHP composer](https://getcomposer.org/doc/00-intro.md)
* An editor of your choice. For advanced development we recommend using [PHP Storm](https://www.jetbrains.com/de-de/phpstorm/).
* Drupal [drush](https://github.com/drush-ops/drush)
* xdebug for step debugging ([using xdebug with ddev](https://ddev.readthedocs.io/en/stable/users/step-debugging/))

## Creating a project

```
git clone git@bitbucket.org:publicplan/degov_project.git
```

Now would be a good time to rename your project according to your needs. In this example we will rename the project to `degov_project_91`:

```
mv degov_project degov_project_91
```

**Install PHP dependencies**

This will download deGov and its dependencies.

```
cd degov_project_91
composer install # or ddev composer install
```

### Start ddev environment

Ddev uses Docker images for your environment. Which are a webserver, a Maria-DB server, and in some cases a Solr search server.

You can run multiple instances of ddev if you adapt the port settings.

**Choose the ports (edge case)**

You should change the following variables in `.ddev/config.yaml`. In this example we will use

```
name: degov
router_http_port: "8091"
router_https_port: "8092"
```

Now you should be able to run

```
cd degov_project_91
ddev start

[...]

Successfully started deGov
Project can be reached at https://degov.ddev.site:8092 https://127.0.0.1:62045
```

**Note:**
To stop all ddev development environments and free used resources, you can run `ddev poweroff`

**Common problem**

In case your ports are already busy with something else you might see a message like

```
Failed to start deGov:
Unable to listen on required ports, port 80 is already in use
```

In this case change port numbers as described above.

## Installing deGov

With ddev running you could just visit the Drupal Install page on the ddev webserver by opening https://degov.ddev.site:8092 in your browser. As this is a common Drupal standard, we will not cover it here in detail.

### Installing deGov with demo content

For development or a quick deGov overview we recommend to use the demo content Drupal module (degov_demo_content).

For reproducible development we provide an custom ddev command called kickstart.

**Running kickstart WILL DELETE ALL YOUR FILES AND DATABASE** in the current project. Roughly it will do the following:

* Install dependencies
* Create a local.settings.php
* Delete your database (drush sql-drop)
* Delete and recreate `sites/default/files`
* Import the current deGov stable database
* Run Drupal database and locale updates

See degov_project_91/.ddev/commands/web/kickstart for details.

```
cd degov_project_91
# Asuming you did a composer install and ddev start.
ddev kickstart
```

Now you should see a page with all teasers when browsing to https://degov.ddev.site:8092

## Login as Admin

The recommended way to login to your deGov demo page is to use `drush user-login` ([drush uli](uli)). Using ddev you would do

```
cd degov_project_91
ddev drush uli
```

## ddev quick hints

We recommend reading the [ddev documentation](https://ddev.readthedocs.io/en/stable/). You can run [drush commands](https://drushcommands.com/drush-9x/) and many other things. Some examples:


```
# List all ddev commands in project
ddev

# Get a detailed description of a running ddev project
ddev describe

# run any drush command
ddev drush <drush command>

# login to the (docker) webserver
ddev ssh

# Restart ddev (e.g. after ddev config change)
ddev restart

# Turn off ddev and shut down all its servers
ddev poweroff

# Inspect the database using Sequel Pro (macOS)
ddev sequelpro

# Run xdebug
# See https://ddev.readthedocs.io/en/stable/users/step-debugging
ddev xdebug
```

## Xdebug

Xdebug provides debugging and profiling capabilities. You may need to [configure your IDE](https://ddev.readthedocs.io/en/stable/users/step-debugging).

You may also debug drush commands. E.g:

```
ddev xdebug
ddev exec PHP_IDE_CONFIG=serverName=nrwgov91.ddev.site /var/www/html/vendor/drush/drush/drush updb -y
```

## Final note

We - the deGov developer team - got started a long time ago. We might be blind to this documentation or take things for granted a beginner never even heard about.

**Please help to improve this documentation**

Open source software lives on contributions. Improving docs is the most recommended way to start contributing ;)

Source:
[https://bitbucket.org/publicplan/degov_project/src/HEAD/README.md](https://bitbucket.org/publicplan/degov_project/src/HEAD/README.md)
