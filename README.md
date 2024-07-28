# Teacher Portal

## Project Overview

This project is a Teacher Portal application that manages student marks and provides various administrative functions.

## Setup Instructions

### Prerequisites

- PHP version >= 7.4 installed
- Composer installed

### Installation Steps

1. **Clone the Repository**

    ```bash
    git clone <repository-url>
    cd teacher-portal
    ```

2. **Install Dependencies**

    ```bash
    composer install
    ```

3. **Setup Environment Variables**

    - Rename `.env.example` to `.env`
    - Update `.env` with your database credentials and any other necessary configuration variables.

4. **Run Migrations**

    ```bash
    php database/migrations/run_migrations.php
    ```

    This command will create necessary database tables (`students` and `marks`) based on migration files.

5. **Seed Database **

    If you have seeders set up:

    ```bash
    php .\database\seeder\DatabaseSeeder.php
    ```

6. **Create Admin User**

    ```bash
    php config/create_admin_user.php <username> <password>
    ```

    Replace `<username>` and `<password>` with desired admin credentials.

## Features

- **Dashboard Statistics:** Static display of total students, failed students, and teachers.
- **Pie Graphs:** Visual representation of student performance and other metrics.
- **Clickable Class Cards:** Navigate to class details page by clicking on class cards.
- **Class Details Page:** Manage student marks (add, edit, delete) for each class.

## Usage

- **Login:** Navigate to the '/', enter your credentials, and click Login.
- **Dashboard:** View overall statistics and navigate to class details by clicking on class cards.
- **Class Details:** Add, edit, or delete student marks for each class.
