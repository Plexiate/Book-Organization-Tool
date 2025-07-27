# 📚 Book Organization Tool

A simple and flexible CLI-based tool for cataloging and managing books in a MySQL database(s).  
Designed to support any shelf or identifier system, whether you're organizing a library in-game or even a physical collection.

---

## Features

- Add, view, and delete books from a database.
- Categorize books by type: Original, Copy, Copy of Copy, Tattered.
- Use custom shelf codes (e.g., `EA4-2`, `SB6-3`) for location tracking.

---

## 🚀 Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/Plexiate/Book-Organization-Tool.git
cd Book-Organization-Tool
```

### 2. Set Up Python Environment

Make sure you have Python 3.7+ installed.

Install dependencies:

```bash
pip install -r requirements.txt
```

### 3. Create a `.env` File

In the root of the project, create a `.env` file with your database credentials:

```env
DB_HOST=your-database-host
DB_USER=your-username
DB_PASS=your-password
DB_NAME=your-database-name
```

> Example:
> ```
> DB_HOST=127.0.0.1
> DB_USER=library-user
> DB_PASS=kasprrisstinky404
> DB_NAME=library
> ```

---

## 🖥️ Running the Tool

Once everything is set up, launch the program:

```bash
python main.py
```

You’ll see a menu with a few options:

- Add a book
- Delete a book
- View all books
- Exit the tool

---

## My Example Shelf System

This is the system I use personally.

- `EA2-5`: East wall, Top row, 2nd shelf from left, 5th book
- `WB3-6`: West wall, Second from top, 3rd shelf from left, 6th book

Using the first example: 
E indicates the wall it's against, in this case it's the Eastern wall, A indicates top shelf, and 2 would be two from the left. The number after - is regarding it's position on the shelf 1-6.

---

## 📦 Dependencies

All dependencies are listed in `requirements.txt`:

```txt
python-dotenv
mysql-connector-python
```

---

## 💡 Tip

Make sure your MySQL database is running and accessible with the login details in `.env`.
