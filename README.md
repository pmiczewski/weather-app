# Weather App

## Requirements

- Docker
- A valid OpenWeather API Key (You can get it from [OpenWeather](https://openweathermap.org/))

## Setup Instructions

1. **Clone the repository**

   Clone the repository to your local machine:

   ```bash
   git clone https://github.com/pmiczewski/weather-app.git
   cd weather-app
   ```

2. **Set up your environment**

   Copy the .env.example file to .env:

   ```bash
   cp .env.example .env
   ```

3. **Configure the OpenWeather API Key**

   Open the .env file and add your OpenWeather API key:

   ```text
   OPEN_WEATHER_API_KEY=your_api_key_here
   ```

4. **Run the application**

   Install dependencies:

   ```bash
   docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
    ```

   Start Docker containers using Laravel Sail:

   ```bash
   ./vendor/bin/sail up
   ```

   Migrate and seed the database:

   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```
   Once the containers are up and running, build the assets:

   ```bash
   ./vendor/bin/sail npm run dev
   ```

5. **Access the application**

   The application should now be running. Open your browser and go to: localhost

## Additional Notes
- Make sure Docker is running before starting the application.
- If you're encountering any issues, try rebuilding the containers:

   ```bash
   ./vendor/bin/sail down
   ./vendor/bin/sail up --build
   ```

- By default, Sail creates a separate testing database to prevent interference with your main database. Before testing, ensure you migrate the testing database
