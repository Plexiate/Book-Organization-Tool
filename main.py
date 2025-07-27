from dotenv import load_dotenv
import mysql.connector
import os
import sys

load_dotenv()

DB_CONFIG = {
    "host": os.getenv("DB_HOST"),
    "user": os.getenv("DB_USER"),
    "password": os.getenv("DB_PASS"),
    "database": os.getenv("DB_NAME")
}

required_keys = ["host", "user", "database"]
missing = [k for k in required_keys if not DB_CONFIG.get(k)]
if missing:
    print(f"Missing DB config values: {', '.join(missing)}")
    sys.exit(1)

def connect():
    return mysql.connector.connect(**DB_CONFIG)

def ensure_table():
    conn = connect()
    cur = conn.cursor()
    cur.execute("""
        CREATE TABLE IF NOT EXISTS books (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            author VARCHAR(255),
            type ENUM('Original', 'Copy', 'Copy of Copy', 'Tattered') NOT NULL,
            shelf_code VARCHAR(10) NOT NULL
        )
    """)
    conn.commit()
    cur.close()
    conn.close()

def add_book():
    title = input("Title: ").strip()
    author = input("Author: ").strip()
    print("Type:\n1. Original\n2. Copy\n3. Copy of Copy\n4. Tattered")
    type_choice = input("Enter number: ").strip()
    type_map = {'1': 'Original', '2': 'Copy', '3': 'Copy of Copy', '4': 'Tattered'}
    book_type = type_map.get(type_choice)
    if not book_type:
        print("Invalid type.")
        return
    shelf_code = input("Shelf Code (e.g., EA4-2): ").strip()

    conn = connect()
    cur = conn.cursor()
    cur.execute("INSERT INTO books (title, author, type, shelf_code) VALUES (%s, %s, %s, %s)",
                (title, author, book_type, shelf_code))
    conn.commit()
    cur.close()
    conn.close()
    print("Book added!")

def delete_book():
    view_books()
    book_id = input("Enter ID of book to delete: ").strip()
    conn = connect()
    cur = conn.cursor()
    cur.execute("DELETE FROM books WHERE id = %s", (book_id,))
    conn.commit()
    cur.close()
    conn.close()
    print("Book deleted!")

def view_books():
    conn = connect()
    cur = conn.cursor()
    cur.execute("SELECT id, title, author, type, shelf_code FROM books ORDER BY shelf_code")
    rows = cur.fetchall()
    print("\nCatalog:\n" + "-"*60)
    for row in rows:
        print(f"[{row[0]}] {row[1]} by {row[2]} — {row[3]} @ {row[4]}")
    if not rows:
        print("No books in catalog.")
    print("-"*60)
    cur.close()
    conn.close()

def menu():
    ensure_table()
    while True:
        print("\nMenu:")
        print("1. Add Book")
        print("2. Delete Book")
        print("3. View Books")
        print("4. Exit")
        choice = input("Select: ").strip()
        if choice == '1':
            add_book()
        elif choice == '2':
            delete_book()
        elif choice == '3':
            view_books()
        elif choice == '4':
            print("Goodbye!")
            sys.exit()
        else:
            print("Invalid choice.")

if __name__ == "__main__":
    menu()
