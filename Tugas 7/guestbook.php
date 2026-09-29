<?php

session_start();

require __DIR__ . '/GuestBook.php';

$host = '127.0.0.1';
$dbname = 'e_library';
$username = 'root';
$password = getenv('DB_PASSWORD') ?: '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die('Koneksi database gagal.');
}

$guestBook = new GuestBook($pdo);

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        $errors[] = 'Token CSRF tidak valid.';
    }

    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pesan = trim($_POST['pesan'] ?? '');

    if ($nama === '') {
        $errors[] = 'Nama wajib diisi.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email tidak valid.';
    }

    if (mb_strlen($pesan) < 5) {
        $errors[] = 'Pesan minimal 5 karakter.';
    }

    if (empty($errors)) {
        $guestBook->addMessage($nama, $email, $pesan);

        header('Location: guestbook.php?success=1');
        exit;
    }
}

$messages = $guestBook->getMessages();

function e(string $value): string
{
    return htmlspecialchars(
        $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu E-Library</title>
</head>
<body>

    <h1>Buku Tamu E-Library</h1>

    <?php if (isset($_GET['success'])): ?>
    <p>Pesan berhasil dikirim.</p>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <div>
        <strong>Terjadi kesalahan:</strong>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="guestbook.php">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= e($_SESSION['csrf_token']) ?>"
    >

    <div>
        <label for="nama">Nama</label><br>
        <input
            type="text"
            id="nama"
            name="nama"
            value="<?= e($_POST['nama'] ?? '') ?>"
            required
        >
    </div>

    <br>

    <div>
        <label for="email">Email</label><br>
        <input
            type="email"
            id="email"
            name="email"
            value="<?= e($_POST['email'] ?? '') ?>"
            required
        >
    </div>

    <br>

    <div>
        <label for="pesan">Pesan</label><br>
        <textarea
            id="pesan"
            name="pesan"
            rows="5"
            required
        ><?= e($_POST['pesan'] ?? '') ?></textarea>
    </div>

    <br>

    <button type="submit">Kirim Pesan</button>

</form>

<hr>

<h2>Daftar Buku Tamu</h2>

<?php if (empty($messages)): ?>

    <p>Belum ada pesan.</p>

<?php else: ?>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Pesan</th>
                <th>Tanggal Kirim</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($messages as $index => $message): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= e($message['nama']) ?></td>
                    <td><?= e($message['email']) ?></td>
                    <td><?= nl2br(e($message['pesan'])) ?></td>
                    <td><?= e($message['tanggal_kirim']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>

</body>
</html>