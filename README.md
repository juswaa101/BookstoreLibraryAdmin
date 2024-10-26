<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

## Setup Instructions for Bookstore Library Admin

### Book Management Features
- Import/Export CSV of Books Lists
- Searching, Sorting, Pagination, Filtering of Books
- Add, Edit, Update, Delete and View of Books (CRUD)

### Prerequisites

Before you begin, ensure you have met the following requirements:
- You have installed [XAMPP](https://www.apachefriends.org/index.html).
- You have a basic understanding of PHP and MySQL.
- You have installed [Composer](https://getcomposer.org/).

### Installation

1. **Clone the Repository:**
    ```sh
    git clone https://github.com/yourusername/BookstoreLibraryAdmin.git
    ```
2. **Navigate to the Project Directory:**
    ```sh
    cd /C:/xampp/htdocs/BookstoreLibraryAdmin
    ```
3. **Install Dependencies:**
    ```sh
    composer install
    ```
4. **Copy the `.env.example` file to `.env`:**
    ```sh
    cp .env.example .env
    ```
5. **Generate an Application Key:**
    ```sh
    php artisan key:generate
    ```
6. **Set Up Your Database:**
    - Open the `.env` file and update the following lines with your database information:
      ```
      DB_DATABASE=your_database_name
      DB_USERNAME=your_database_user
      DB_PASSWORD=your_database_password
      ```
    - Create the database in MySQL:
      ```sql
      CREATE DATABASE your_database_name;
      ```
7. **Run Migrations:**
    ```sh
    php artisan migrate
    ```

### Running the Application

1. **Start XAMPP:**
    - Open XAMPP Control Panel.
    - Start Apache and MySQL.

2. **Serve the Application:**
    ```sh
    php artisan serve
    ```

3. **Access the Application:**
    - Open your web browser and go to `http://localhost:8000`.

### Additional Commands

- **Running Tests:**
  ```sh
  php artisan test
  ```

- **Clearing Cache:**
  ```sh
  php artisan cache:clear
  ```

### Contributing

Thank you for considering contributing to the Bookstore Library Admin project! Please read the [contribution guide](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

### License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

### Seeding Test Records

1. **Run the Seeder:**
    ```sh
    php artisan db:seed
    ```

This command will populate your database with test records defined in your seeders.

### Importing/Exporting CSV Data

1. **Import the CSV File:**
    - Ensure the `Bookstore Library Admin CSV Import.csv` file is located in the root of the application.
    - Upload it in the system for testing of Import Book Data.

2. **Export the CSV File:**
    - Use the export functionality to download the list of books in CSV format.

