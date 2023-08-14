# YeetBin
Ever wanted to share code, links or files? [Yeet](https://youtu.be/qzN_oOAPmOI) it into YeetBin and you're done!

![YeetBin screenshot](.github/screenshot.png)

## Features
_See our [roadmap](https://github.com/principis/YeetBin/milestone/1) for upcoming features._
- Paste and share text
- Optional syntax highlighting (192 languages)
- Raw view
- Download as file
- Light and dark theme

## Installation
### Manual
#### Requirements:
- composer >= 2
- yarn >= 1
- Node.js (tested with v18)

Node.js and yarn are only needed to compile the web assets, you can safely remove them and the `node_modules` directory afterwards.

#### Install dependencies
```bash
composer install --classmap-authoritative --no-dev
yarn install
```
#### OPTIONAL subdirectory installation

Edit `webpack.config.js`. Change `.setPublicPath('/assets/dist/')` and uncomment `.setManifestKeyPrefix('build/')`.

For example for `example.com/yeetbin`:
```javascript
.setPublicPath('/yeetbin/assets/dist/')
.setManifestKeyPrefix('build/')
```

#### Compile assets
```bash
yarn encore prod
# cleanup
rm -rf node_modules
```

### Docker
The container uses Apache on port 80 and a SQLite database in `/var/www/html/var/sqlite.db`.

The database and other configuration can be changed using enviroment variables, see [.env.example](.env.example).

The `var` directory (which stores files and images) can be mounted as a volume if you wish to store it somewhere else. The UserID of this directory can be set using the `DOCKER_USER` variable.

## Configuration
Configuration is loaded from the `config/config.php` file or, if not available, environment variables. Note that `.env` files are not used and only there as reference.

### Authentication
There are 2 modes:
- **basic**: HTTP basic authentication
- **form**: A login form

Authentication is only requested for routes protected by the firewall (see [routes.php](config/routes.php)). It is recommended to protect the `add_file` and `add_image` routes to avoid malware and other unwanted content.

### Database
YeetBin should work with most databases and is tested with MySQL and SQLite. It uses PHP `PDO` and configuration values are passed to the PDO constructor. The available PDO drivers can be found [here](https://www.php.net/manual/en/pdo.drivers.php).

Some examples:
```PHP
// SQLite
'database' => [
    'dsn' => 'sqlite:'.__DIR__.'/db.sqlite.db',
],
// MySQL
'database' => [
    'dsn' => 'mysql:host=localhost;dbname=yeetbin',
    'username' => 'root',
    'password' => 'my_password', // Set to null or omit if no password
    'options' => [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
],
```
