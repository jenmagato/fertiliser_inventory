<!-- ABOUT THE PROJECT -->
## About The Project
Laravel application that helps a user understand how much quantity of a product is available for use.
The application should display an interface with a button and a single input that represents the requested quantity of a product.

### Built With
* [Laravel 9](laravel.com/docs/9.x/releases)
* PHP 8.0 - above

<!-- GETTING STARTED -->
## Getting Started

### Prerequisites
* [Composer](https://getcomposer.org/)
* [Npm](npmjs.com)
* Apache/Nginx Server
* Git
* [Database](laravel.com/docs/9.x/database)

### Installation

1. Clone the repo
   ```sh
   HTTPS: git clone https://github.com/jenmagato/figured_fertiliser_inventory.git 
   ```
2. Go inside the folder
   ```sh
   cd figured_fertiliser_inventory
   ```
3. create .env file and do not forget to set your DB_PASSWORD
   ```sh
   cp .env.example .env
   ```
4. Run Composer Install.
   ```sh
   composer install
   ```
5. Serve the project.
   ```sh
   php artisan serve
   ```
6. Create app key
   ```sh
   php artisan key:generate
   ```
7. build CSS and JS assets
   ```sh
   npm install && npm run build
   ```
8. Clear cache if needed
   ```sh
   php artisan config:cache
   php artisan route:clear
   ```
   
### Database Setup
1. Make sure to complete env setup above
2. Run migration to create the inventory table
   ```sh
   php artisan migrate
   ```
3. Run seeder to populate the inventory table.
   ```sh
   php artisan db:seed
   ```

## Usage 
1. Go to http://localhost/figured_fertiliser_inventory/public in order to see the application running. 

## Testing 
1. Run below command . Note : Kindly follow Database Setup again after testing
   ```sh
   php artisan test
   ```
   
## Folders / Important files 
- `/storage/Fertiliser_inventory_movements.csv` - was used to populate the inventory table
- `app/Http/Controllers` - Contains all controllers
- `app/Http/Requests` - Contains all form requests
- `app/Http/Services` - Contains all files implementing services
- `database/migrations` - Contains all the database migrations
- `database/seeders` - Contains all the database seeders
- `resources` - Contains all the resources that are to be compiled
- `routes` - Contains all routes
- `tests/Unit` - Contains all unit tests

<!-- CONTACT -->
## Contact
Jennalyn Magat - https://github.com/jenmagato
