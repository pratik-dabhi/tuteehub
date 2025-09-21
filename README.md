## Project Setup

Follow these steps to get the project running locally:

1. **Clone the repository**
   ```bash
   git clone https://github.com/pratik-dabhi/tuteehub.git
   cd tuteehub

2. **Install the dependencies**
   ```bash
   composer install

3. **Copy the example environment file**
   ```bash
   cp .env.example .env

4. **Generate the application key**
   ```bash
   php artisan key:generate

5. **Run the migrations**
   ```bash
   php artisan migrate

6. **Seed the default data**
   ```bash
   php artisan db:seed

7. **Run the test cases**
   ```bash
   php artisan test

8. **Run the project**
   ```bash
   php artisan serve

