# 🚀 Development Setup

Follow the steps below to run the project in your local development environment.

---

## 📦 Backend (Laravel API)

1. Navigate to the backend directory:

    ```bash
    cd api
    ```

2. Install dependencies (if not already installed):

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

1. Navigate to the frontend directory from the root of the project:

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
