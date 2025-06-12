
# 🚀 Development Setup

You can quickly get started using the provided setup and run scripts.  
Manual steps are also available below if you prefer doing it step by step.
[🎥 Google Drive preview](https://drive.google.com/file/d/1GvXdg_7ZyRuLIsHN1SLxTdG9xBrHK5GE/view?usp=sharing)



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
