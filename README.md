## CV_PHP Project

## Overview

The CV_PHP Project is a web application built using PHP that allows users to create, manage, and display their CVs online. The application supports user authentication, profile management, and CV customization. It also includes a dark mode feature for an improved user experience.

## Features

- **User Authentication**: Users can sign in and out securely.
- **Profile Management**: Users can update their personal information and profiles.
- **CV Creation and Editing**: Users can create and modify their CVs.
- **CV Display**: View a detailed, formatted CV in a clean user interface.
- **Dark Mode**: Switch between light and dark themes for better visibility.

## Prerequisites

Before running the application, make sure you have the following installed:

- Docker
- Docker Compose

## Installation

Follow these steps to set up and run the project locally:

1. **Clone the repository:**
    ```sh
    git clone https://github.com/nvtnicolas/CV_PHP.git
    cd CV_PHP
    ```

2. **Build the Docker image:**
    ```sh
    cd Docker
    docker build -t cv_php .
    ```

3. **Run the Docker containers:**
    ```sh
    docker-compose up --build
    ```

4. **Access the application:**
   Once the containers are running, open your browser and go to:
    ```sh
    http://localhost:8000
    ```

## Project Structure

- `/Docker`: Contains the Docker configuration files.
- `/app`: Contains the PHP source code for the application.
- `/public`: Holds the public assets like CSS, JS, and images.

## Usage

1. **Sign Up / Log In**: Create an account or log in if you already have one.
2. **Create Your CV**: Navigate to the CV creation page and enter your information (work experience, education, skills, etc.).
3. **View Your CV**: See a preview of your CV and edit it as needed.
4. **Toggle Dark Mode**: Switch between light and dark modes for improved readability.

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.

## Contributions

Contributions are welcome! Feel free to submit a pull request or open an issue for any improvements or bug fixes.

## Contact

For any questions or support, please contact [nicolas.nguyenvanthnah@ynov.com](mailto:nicolas.nguyenvanthnah@ynov.com).
