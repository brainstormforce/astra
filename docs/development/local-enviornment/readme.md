# Local Enviornment

### Using custom environment

You can use your preferred WordPress development enviornments, for eg - [Local by Flywheel](https://localwp.com/), [XAMPP](https://www.apachefriends.org/index.html), [Lando](https://docs.lando.dev/config/wordpress.html)

Just setup your local enviornment of your choice and clone this repo into WordPress's theme's directory.

```
cd wp-content/themes && git clone https://github.com/brainstormforce/astra.git astra
cd astra
```

Then install the packages:

```
composer install
npm install
```

### Using integrated local enviornment

Astra Theme also ships with an integrated local enviornment based on Docker and Docker Compose. This uses [@wordpress/env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/)

First step would be to install [Docker](https://www.docker.com/products/docker-desktop) and [Docker Compose](https://docs.docker.com/compose/install/) by following the instructions on their website.

You can then clone this project somewhere on your computer:

```
git clone https://github.com/brainstormforce/astra.git astra
cd astra
```

After that, run a script to set up the local environment. It will automatically verify whether Docker, Composer and Node.js are configured properly and start the local WordPress instance. You may need to run this script multiple times if prompted.

```
npm run env:start
```

The WordPress installation should be available at `http://localhost:8890` (Username: `admin`, Password: `password`).

To later turn off the local environment, you can run:

```
npm run env:stop
```

To bring it back later, run:

```
npm run env:start
```

Also, if you need to reset the local environment's database, you can run:

```
npm run env:reset-site
```
