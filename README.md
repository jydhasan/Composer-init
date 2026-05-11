# 🚀 PHP MVC Framework (Lightweight)

A simple, lightweight MVC framework built from scratch in PHP 8.2 — no heavy dependencies, just clean structure.

---

## 📋 Requirements

- PHP >= 8.2
- Composer
- MySQL
- Git

---

## ⚙️ Installation

### 1. Clone the repository

```bash
git clone https://github.com/your-username/test-app.git
cd test-app
```

### 2. Install dependencies

```bash
composer install
composer dump-autoload
```

### 3. Configure Database

Open `config/database.php` and update your credentials:

```php
return [
    'host'     => 'localhost',
    'port'     => 3306,
    'database' => 'your_database_name',
    'username' => 'your_username',
    'password' => 'your_password',
    'charset'  => 'utf8mb4',
];
```

> ⚠️ **Important:** `config/database.php` is listed in `.gitignore`. Never commit your real credentials.

### 4. Run the development server

```bash
php -S localhost:8000
```

Visit: [http://localhost:8000](http://localhost:8000)

---

## 📁 Project Structure

```
test-app/
├── config/
│   └── database.php          # Database credentials (gitignored)
├── Core/
│   ├── Database.php          # PDO connection (Singleton)
│   ├── Model.php             # Base Active Record model
│   ├── SimpleRouter.php      # HTTP router
│   └── View.php              # View renderer
├── src/
│   ├── Controllers/
│   │   └── HomeController.php
│   └── Models/
│       └── User.php          # Example model
├── views/
│   └── home.php              # HTML templates
├── vendor/                   # Composer autoload (gitignored)
├── composer.json
├── index.php                 # Application entry point
└── routes.php                # All route definitions
```

---

## 🔀 Routing

All routes are defined in `routes.php`:

```php
$router->get('/', [HomeController::class, 'index']);
$router->get('/about', function() {
    echo "<h1>About Page</h1>";
});
$router->post('/submit', [FormController::class, 'store']);

// 404 handler
$router->notFound(function() {
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
});
```

---

## 🗄️ Models (Active Record)

### Creating a new Model

**Step 1 — Create the table in MySQL:**

```sql
CREATE TABLE posts (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    user_id    INT,
    title      VARCHAR(200),
    body       TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

**Step 2 — Create `src/Models/Post.php`:**

```php
<?php

namespace App\Models;

use Core\Model;

class Post extends Model
{
    protected static string $table = 'posts';

    // Add custom methods below
    public static function findByUser(int $userId): array
    {
        return static::where(['user_id' => $userId]);
    }
}
```

**Step 3 — Use in your Controller:**

```php
use App\Models\Post;

// Get all posts
Post::all();

// Find by ID
Post::find(1);

// Find with condition
Post::where(['user_id' => 1]);

// Get first match
Post::first(['title' => 'Hello']);

// Create new record
Post::create([
    'user_id' => 1,
    'title'   => 'My First Post',
    'body'    => 'Hello World!',
]);

// Update
Post::update(1, ['title' => 'Updated Title']);

// Delete
Post::delete(1);

// Count all rows
Post::count();

// Raw SQL
Post::raw("SELECT * FROM posts WHERE created_at > :date", [':date' => '2024-01-01']);
```

---

## 🎮 Controllers

Create controllers inside `src/Controllers/`:

```php
<?php

namespace App\Controllers;

use Core\View;
use App\Models\Post;

class PostController
{
    public function index()
    {
        $posts = Post::all();
        View::render('posts/index', ['posts' => $posts]);
    }

    public function show()
    {
        $id   = $_GET['id'] ?? 1;
        $post = Post::find($id);
        View::render('posts/show', ['post' => $post]);
    }
}
```

Register the route in `routes.php`:

```php
$router->get('/posts', [PostController::class, 'index']);
$router->get('/posts/show', [PostController::class, 'show']);
```

---

## 🖼️ Views

Views live in the `views/` folder. Pass data from controller like this:

```php
// Controller
View::render('posts/index', ['posts' => $posts]);
```

```php
<!-- views/posts/index.php -->
<?php foreach ($posts as $post): ?>
    <h2><?= htmlspecialchars($post['title']) ?></h2>
    <p><?= htmlspecialchars($post['body']) ?></p>
<?php endforeach; ?>
```

---

## 🌐 Namespaces

| Namespace | Folder | Example |
|-----------|--------|---------|
| `Core\`   | `Core/` | `Core\Model`, `Core\SimpleRouter` |
| `App\Controllers\` | `src/Controllers/` | `App\Controllers\HomeController` |
| `App\Models\` | `src/Models/` | `App\Models\User` |

> **PSR-4 Rule:** Class name must exactly match the filename.
> `class UserProfile` → file must be `UserProfile.php`

---

## 🔒 .gitignore Recommendations

Make sure your `.gitignore` includes:

```
/vendor
/config/database.php
.env
*.log
```

---

## 📌 Common Issues

| Error | Cause | Fix |
|-------|-------|-----|
| `Class "X" not found` | File name ≠ class name | Rename file to match class name exactly |
| `Class "X" not found` | Autoload not updated | Run `composer dump-autoload` |
| `Database connection failed` | Wrong credentials | Check `config/database.php` |
| `500 Internal Server Error` | PHP syntax error | Check error log in terminal |

---

## 📜 License

MIT License — free to use and modify.
