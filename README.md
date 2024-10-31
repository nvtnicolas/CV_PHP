## CV_PHP Project

## Overview

The CV_PHP Project is a web application built using PHP that allows users to create, manage, and display their CVs online. The application supports user authentication, profile management, and CV customization. It also includes a dark mode feature for an improved user experience.

## Features

- **User Authentication**: Users can sign in and out securely.
- **Profile Management**: Users can update their personal information and profiles.
- **CV Creation and Editing**: Users can create and modify their CVs.
- **CV Display**: View a detailed, formatted CV in a clean user interface.
- **Dark Mode**: Switch between light and dark themes for better visibility.
- **Profile Image**: Users can upload a profile image to be included in their CV.
- **Download CV**: Users can download their CV as a PDF file.

## Prerequisites

Before running the application, make sure you have the following installed:

- Docker
- GIT

## Installation

Follow these steps to set up and run the project locally:

1. **Clone the repository:**
    ```sh
    git clone https://github.com/nvtnicolas/CV_PHP.git
    cd CV_PHP
    ```

2. **Run the Docker containers:**
    ```sh
    cd Docker
    docker-compose up --build
    ```

3. **Access the application:**
   Once the containers are running, open your browser and go to:
    ```sh
    http://127.0.0.1/
    ```

## Project Structure

- `/Docker`: Contains the Docker configuration files.
- `/app`: Contains the PHP source code for the application.
- `/assets`: Holds the public assets like CSS, JS, and images.
- `/auth` : Contains the authentication files.
- `/db`: Contains the database configuration files.
- `/user`: Contains the user profile files.
- `/uploads`: Contains the uploaded files (e.g., user profile pictures).
- `/admin`: Contains the admin files.

## Usage

1. **Sign Up / Log In**: Create an account or log in if you already have one.
2. **Create Your CV**: Navigate to the CV creation page and enter your information (work experience, education, skills, etc.).
3. **View Your CV**: See a preview of your CV and edit it as needed.
4. **Upload Profile Image**: Add a profile image to be included in your CV.
5. **Download CV**: Download your CV as a PDF file.
6. **Toggle Dark Mode**: Switch between light and dark modes for improved readability.

## Admin database
There is an admin account that you can use to access the admin page
username : admin1
password : password123

## Database
username : root
password : root
database : cv_php
http://127.0.0.1:8080/

## Contributions

Contributions are welcome! Feel free to submit a pull request or open an issue for any improvements or bug fixes.

## Contact

For any questions or support, please contact [nicolas.nguyenvanthnah@ynov.com](mailto:nicolas.nguyenvanthnah@ynov.com).