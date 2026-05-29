# Department Contact Tracing Application

A lightweight, CRUD-based web application designed for the Department of Computer Engineering to monitor and log the entry and exit of students, faculty, and guests. This project utilizes a decoupled architecture, separating the HTML/JS frontend from the PHP/MySQL backend API.

## Features
* **Self-Service Kiosk:** Users can quickly sign in and sign out using only their ID number.
* **Automated State Handling:** The system automatically determines if an existing user is signing in or signing out based on their active session.
* **New User Registration:** First-time users are automatically routed to a registration form to input their complete demographic data before signing in.
* **Admin Dashboard:** A secured portal for administrators to view real-time logs and filter records by Name, ID, Location (City/Barangay/Province), and Date.

## Prerequisites
To run this application locally, you will need a local web server environment.
* **XAMPP** (Recommended), WAMP, or MAMP installed on your machine.
* A modern web browser (Chrome, Firefox, Edge, Safari).

## Installation & Setup

### 1. File Placement
1. Extract or clone the project folder.
2. Rename the folder to `CONTACT_TRACING` (if it isn't already).
3. Move the entire `CONTACT_TRACING` folder into your local server's root web directory:
   * **For XAMPP:** `C:\xampp\htdocs\CONTACT_TRACING`
   * **For WAMP:** `C:\wamp\www\CONTACT_TRACING`

### 2. Database Configuration
1. Open the **XAMPP Control Panel**.
2. Start the **Apache** and **MySQL** modules.
3. Open your web browser and navigate to `http://localhost/phpmyadmin/`.
4. Create a new database named `contact_tracing_db` (Optional: The SQL script will create it automatically if it doesn't exist).
5. Select the `contact_tracing_db` database, go to the **Import** tab.
6. Choose the `database.sql` file located in the root of this project and click **Import** to build the tables and inject the default admin credentials.

## Usage Guide

### Accessing the Kiosk (Public Facing)
Open your browser and navigate to:
`http://localhost/CONTACT_TRACING/`

* **New Users:** Enter an ID number. The system will recognize the ID is unregistered and redirect to the registration page.
* **Returning Users:** Enter the registered ID number. The system will automatically log the `time_in` or `time_out` depending on the user's current status.

### Accessing the Admin Dashboard
Open your browser and navigate to:
`http://localhost/CONTACT_TRACING/admin.html`

**Default Administrator Credentials:**
* **Username:** `admin`
* **Password:** `password123`

*Note: The admin dashboard relies on custom HTTP headers (`X-Admin-User`, `X-Admin-Pass`) to bypass default local server security configurations that strip standard authentication headers.*

## Directory Structure
```text
CONTACT_TRACING/
│
├── api/                        # Backend PHP endpoints
│   ├── admin_records.php       # Handles secure data retrieval for the dashboard
│   ├── db.php                  # MySQL connection string
│   ├── process_kiosk.php       # Logic for ID verification and time toggling
│   └── register_user.php       # Logic for writing new user profiles to the database
│
├── css/                        # Stylesheets
│   └── style.css               # Centralized styling for all UI views
│
├── admin.html                  # Admin dashboard and search interface
├── database.sql                # SQL schema dump and default data
├── index.html                  # The primary Kiosk UI
├── README.md                   # Project documentation
└── register.html               # Registration UI for new users
