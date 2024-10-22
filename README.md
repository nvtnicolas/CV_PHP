# CV_PHP Project

## Overview

This project is a web application built with PHP that allows users to create and manage their CVs. Users can log in, update their profiles, and view their CVs.

## Features

- User authentication (login/logout)
- Profile management
- CV creation and editing
- Display CV details
- Dark mode

## Requirements

- Docker
- Docker Compose

## Installation

1. **Clone the repository:**
    ```sh
    git clone https://github.com/nvtnicolas/CV_PHP.git
    cd CV_PHP
    ```

2. **Set up the database:**
    - Create a database and import the `schema.sql` file.
    - Update the database configuration in `config.php`.

3. **Build and run the Docker containers:**
    ```sh
    docker-compose up --build
    ```

## Configuration

Update the `config.php` file with your database credentials:
```php
<?php
define('DB_HOST', 'db');
define('DB_NAME', 'cv_db');
define('DB_USER', 'root');
define('DB_PASS', 'root');
?>