# Wreely - Social (Backend)

We are open-sourcing the backend code for the [Wreely - Workspace Management Platform](http://wreely.com). This backend is constructed with PHP and adheres to the MVVM architecture to ensure the code is clean and maintainable. The API endpoints are developed using the PHP Slim framework, with CodeIgniter serving as the fundamental framework for the backend processes.

## Project Structure
```
├── application         # CodeIgniter application source files
├── assets              # Assets such as CSS, JS, and images
├── bower_components    # Bower components for front-end libraries
├── bower.json          # Bower dependencies file
├── build               # Build scripts and configurations
├── composer.json       # Composer dependencies file
├── composer.lock       # Composer lock file, specifying exact versions of dependencies
├── composer.phar       # Composer PHP archive
├── database            # Database scripts and migrations for setup
├── index.php           # Main entry point for the web application
├── info.php            # PHP information file
├── phpunit.xml         # PHPUnit configuration file for testing
├── README.md           # Documentation for project setup and overview
├── services            # Business logic services
├── system              # CodeIgniter system folder for core files
└── vendor              # Composer vendor folder with installed PHP dependencies
```
## Screenshots

<img src="https://wreely.com/dist/images/web_app.png"  width="100%" height="100%">

## Setup & Installation

1. Clone the repository to your local environment:
   ```
   git clone https://github.com/tirupati17/wreely-backend.git
   ```
2. Change directory into the project folder:
   ```
   cd wreely-backend
   ```
3. Install Composer dependencies:
   ```shell
   composer install
   ```
4. Install Bower dependencies:
   ```shell
   bower install
   ```
5. Set up your database configuration within the CodeIgniter config.
6. Execute any necessary database migrations:
   ```
   php index.php migrate
   ```

## Frameworks and Libraries

- **CodeIgniter**: The main PHP framework for building the backend.
- **PHP Slim Framework**: For designing a sleek and performant API.
- **Bower**: Front-end package management.
- **Composer**: Dependency management in PHP.

## Contributing

As this is an open-source project, contributions are heartily welcomed. Please consult the contributing guide for more details on how you can contribute.

## License

This project is open-sourced under the MIT License. See the LICENSE file for full details.

## Disclaimer

The code provided in this repository is open-sourced as-is under the MIT License. It was developed for a previous version of the Wreely platform and, as such, may not be compatible with current versions of dependencies and the PHP environment. There is no active maintenance, and the codebase is shared for educational and historical reference. Users should not expect it to run out of the box on modern systems without potential modifications and updates.

