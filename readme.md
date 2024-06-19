# Wordpress Alt Text Plugin for Wordpress

Wordpress Alt Text is a WordPress plugin that can auto generate al text for image and is built using TypeScript, ANTD design, and React Query. 

## Installation

1. Clone the repository:

	```bash
	git clone https://github.com/Not-Just-Web/njw-alt-text-plugin.git
	```
2. Prepare Git Hook

	```bash
	npm run prepare
	```

3. Install the dependencies:

	```bash
	npm install
	```
4. Build the Scripts

	``` bash
		npm run build
	```
	
**Note:** The code snippet below builds the necessary code that will be enqueued by the plugin on activation. All the scripts are generated in the `/dist` folder.


## Usage
- Using Wordpress Alt Text plugin, you can generate alt text for the image using GPT4Vision or custom AI.

### Available Scripts

All available scripts on this repo : 

- `preinstall`: Runs `composer install` to install the PHP dependencies before the package itself is installed. This script is executed automatically when running `npm install`.
- `prepare`: Sets the Git hooks path to the `./git-hooks` directory.
- `test`: Runs all test scripts in parallel.
- `test:unit-php`: Runs the PHP unit tests using Composer.
- `test:unit-js`: Runs the JavaScript unit tests using `wp-scripts`.
- `lint`: Runs all lint scripts in parallel.
- `lint:css`: Lints the CSS files using `wp-scripts`.
- `lint:php`: Lints the PHP files using Composer.
- `lint:ts`: Lints the TypeScript files using `wp-scripts`.
- `format`: Runs all format scripts in parallel.
- `format:css`: Formats the CSS files using the CSS lint script with the `--fix` option.
- `format:ts`: Formats the TypeScript files using the TypeScript lint script with the `--fix` option.
- `format:php`: Formats the PHP files using Composer.
- `php`: Alias for `format:php`.
- `start`: Starts the development server using `wp-scripts`.
- `build`: Builds the project for production using `wp-scripts`.
- `create:block`: Creates a new block in the `./src/blocks` directory using `@wordpress/create-block`.
- `sync:install`: it installs `fwatch` using brew on mac that helps to synchronize this folder with targeted plugin directory.
- `sync` : Example : `npm run sync -- /path/to/plugin/folder` executes `.sync.sh` file to create symbolic link to targeted wordpress plugin folder.

