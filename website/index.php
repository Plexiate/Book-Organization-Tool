<?php

$pdo = new PDO("mysql:host=IP_OR_DOMAIN_HERE;dbname=DATABASE_NAME;charset=utf8mb4", 'USER_HERE', 'PASSWORD_HERE'); //I would suggest making a view only user as I did.

$validWalls = ['E', 'S', 'W'];
$wall = isset($_GET['wall']) && in_array($_GET['wall'], $validWalls) ? $_GET['wall'] : 'E';
$stmt = $pdo->prepare("SELECT * FROM books WHERE shelf_code LIKE ? ORDER BY shelf_code ASC");
$stmt->execute([$wall . '%']);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

function buildShelfMap($books, $wall) {
    $map = [];
    foreach ($books as $book) {
        if (preg_match('/^' . $wall . '([A-C])(\d+)-(\d)$/', $book['shelf_code'], $m)) {
            $row = $m[1]; $col = (int)$m[2]; $slot = (int)$m[3];
            $map[$row][$col][$slot] = $book;
        }
    }
    return $map;
}

$shelfMap = buildShelfMap($books, $wall);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Library</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            background: #f4f4f4;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: #333;
        }

        h2 {
            margin-top: 0;
            color: #555;
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        select {
            font-size: 1rem;
            padding: 5px 10px;
        }

        .shelf-row {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .shelf-slot {
            width: 100px;
            height: 100px;
            background: #fff;
            border: 1px solid #ccc;
            padding: 5px;
            font-size: 0.75rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            position: relative;
        }

        .shelf-label {
            font-weight: bold;
            color: #444;
            margin-bottom: 5px;
        }

        .book-entry {
            color: #3498db;
            text-align: center;
            margin: 2px 0;
            position: relative;
            cursor: pointer;
        }

        .book-entry .tooltip {
            visibility: hidden;
            opacity: 0;
            position: absolute;
            bottom: 120%;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: #fff;
            padding: 6px 10px;
            border-radius: 6px;
            white-space: nowrap;
            font-size: 0.75rem;
            z-index: 10;
            pointer-events: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transition: opacity 0.15s ease-in-out;
        }

        .book-entry:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }

        .footer {
            margin-top: 40px;
            font-size: 0.85rem;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>📚 Book Library</h1>
    <h2><?= htmlspecialchars($wall) ?> Wall View</h2>

    <form method="GET">
        <select name="wall" onchange="this.form.submit()">
            <?php foreach ($validWalls as $dir): ?>
                <option value="<?= $dir ?>" <?= $dir === $wall ? 'selected' : '' ?>><?= $dir ?> Wall</option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if (empty($books)): ?>
        <p style="text-align:center; color:#888;">No books on this wall yet.</p>
    <?php else: ?>
        <?php foreach (['A', 'B', 'C'] as $row): ?>
            <div class="shelf-row">
                <?php for ($col = 1; $col <= 11; $col++): ?>
                    <div class="shelf-slot">
                        <div class="shelf-label"><?= $row . $col ?></div>
                        <?php if (isset($shelfMap[$row][$col])): ?>
                            <?php foreach ($shelfMap[$row][$col] as $slot => $book): ?>
                                <div class="book-entry">
                                    <?= htmlspecialchars($book['title']) ?>
                                    <span class="tooltip">
                                        <strong><?= htmlspecialchars($book['title']) ?></strong><br>
                                        Author: <?= htmlspecialchars($book['author']) ?><br>
                                        Slot: <?= $slot ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="footer">
        <br>© plexiate.com
    </div>
</body>
</html>
