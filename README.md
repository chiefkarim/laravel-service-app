# Live preview

[Front-end](https://service.karimmenna.com)
[Back-end](https://api.karimmenna.com)
[🎥 Google Drive preview](https://drive.google.com/file/d/1GvXdg_7ZyRuLIsHN1SLTdTdG9xBrHK5GE/view?usp=sharing)

# 🚀 Development Setup

You can quickly get started using the provided setup and run scripts.  
Manual steps are also available below if you prefer doing it step by step.
[🎥 Google Drive preview](https://drive.google.com/file/d/1GvXdg_7ZyRuLIsHN1SLTdTdG9xBrHK5GE/view?usp=sharing)

---

## ⚡ Quick Start

### 1. Run the setup script

This will:

- Install backend (`composer`) and frontend (`pnpm`) dependencies
- Copy the Laravel `.env` file
- Generate the Laravel app key

```bash
./setup.sh
```

### 2. Start the development servers

This will run both:

- Laravel development server (API)
- Vite development server (Vue frontend)

```bash
./run.sh
```

---

## 🛠️ Manual Setup

If you'd rather set up each part manually, follow the steps below.

---

## 📦 Backend (Laravel API)

1. Navigate to the backend directory:

    ```bash
    cd api
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Copy the `.env` file and generate the application key:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Start the development server:

    ```bash
    composer run dev
    ```

    > This command may use Laravel Sail or a custom script depending on your setup.

---

## 🖥️ Frontend (Vue SPA)

1. Navigate to the frontend directory:

    ```bash
    cd client
    ```

2. Install dependencies:

    ```bash
    pnpm install
    ```

3. Run the development server:

    ```bash
    pnpm run dev
    ```

    > This will start the Vite development server on [http://localhost:5173](http://localhost:5173) (or your configured port).

---

## ✨ Features

This project is a full-stack application built with a Laravel API backend and a Vue.js Single Page Application (SPA) frontend, designed for managing services and service requests with robust user authentication and a fine-grained permission system.

### Core Functionalities:

*   **User Authentication & Authorization:**
    *   Secure user login and session management.
    *   Implements a comprehensive Role-Based Access Control (RBAC) system, allowing granular permissions (create, read, update, delete) on various resources (services, service requests, users, permissions).
    *   Frontend routes are dynamically protected based on the authenticated user's assigned permissions, ensuring secure access to features.

*   **Service Management:**
    *   **CRUD Operations:** Full Create, Read, Update, and Delete (CRUD) capabilities for managing different types of services offered.
    *   **Public Listing:** Services can be publicly viewed without authentication, allowing potential users to browse available offerings.

*   **Service Request Management:**
    *   **Public Submission:** Users can submit new service requests without needing to log in.
    *   **Authenticated Management:** Authenticated administrators or authorized personnel can view, update (change status, add replies), and delete service requests.
    *   **Automated Notifications:** The system sends automated email notifications for new service requests and updates to existing requests, keeping all parties informed.

*   **User Management:**
    *   **Account Administration:** Authenticated users with appropriate permissions can manage (create, view, update, delete) other user accounts within the system.

*   **Permission Management:**
    *   **Dynamic Control:** Authenticated users with administrative privileges can dynamically manage (create, view, delete) permissions and assign them to specific user accounts, providing flexible access control.

### Technical Stack:

*   **Backend:** Laravel (PHP Framework)
*   **Frontend:** Vue.js with TypeScript (JavaScript Framework) with Vite
*   **Database:** SQLite (for development, configurable for production)
*   **Authentication:** Laravel Sanctum (API Token Authentication)
*   **Package Management:** Composer (PHP), pnpm (JavaScript)
