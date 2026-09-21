<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/Transaction.php';

if (!isset($_SESSION['balance'])) {
    $_SESSION['balance'] = 0.0;
}

if (!isset($_SESSION['transactions'])) {
    $_SESSION['transactions'] = [];
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!is_string($csrfToken) || !hash_equals($_SESSION['csrf_token'], $csrfToken)) {
        $message = 'Token CSRF tidak valid.';
    } else {
        $type = $_POST['type'] ?? '';
        $amountInput = $_POST['amount'] ?? '';

        if (!is_string($type) || !is_string($amountInput)) {
            $message = 'Data transaksi tidak valid.';
        } elseif (!is_numeric($amountInput)) {
            $message = 'Jumlah transaksi harus berupa angka.';
        } else {
            $amount = (float) $amountInput;

            if ($amount <= 0 || !is_finite($amount)) {
                $message = 'Jumlah transaksi harus berupa angka positif.';
            } else {
                $transactionType = match ($type) {
                    'deposit' => 'deposit',
                    'withdrawal' => 'withdrawal',
                    default => null,
                };

                if ($transactionType === null) {
                    $message = 'Jenis transaksi tidak valid.';
                } else {
                    $transaction = new Transaction(
                        count($_SESSION['transactions']) + 1,
                        $transactionType,
                        $amount
                    );

                    if ($transaction->process($_SESSION['balance'])) {
                        $_SESSION['transactions'][] = [
                            'id' => count($_SESSION['transactions']) + 1,
                            'type' => $transactionType,
                            'amount' => $amount,
                        ];

                        $message = 'Transaksi berhasil diproses.';
                    } else {
                        $message = 'Penarikan ditolak karena saldo tidak mencukupi.';
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Keuangan</title>
</head>
<body>
    <h1>Sistem Manajemen Keuangan</h1>

    <p>
        Saldo saat ini:
        Rp <?= htmlspecialchars(number_format($_SESSION['balance'], 2, ',', '.'), ENT_QUOTES, 'UTF-8') ?>
    </p>

    <?php if ($message !== ''): ?>
        <p>
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
        >

        <label for="type">Jenis transaksi:</label>
        <select name="type" id="type" required>
            <option value="deposit">Deposit</option>
            <option value="withdrawal">Penarikan</option>
        </select>

        <br><br>

        <label for="amount">Jumlah:</label>
        <input
            type="number"
            name="amount"
            id="amount"
            min="0.01"
            step="0.01"
            required
        >

        <button type="submit">Proses Transaksi</button>
    </form>

    <h2>Riwayat Transaksi</h2>

    <?php if (empty($_SESSION['transactions'])): ?>
        <p>Belum ada transaksi.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($_SESSION['transactions'] as $transaction): ?>
                <li>
                    ID:
                    <?= htmlspecialchars((string) $transaction['id'], ENT_QUOTES, 'UTF-8') ?>
                    -
                    Jenis:
                    <?= htmlspecialchars((string) $transaction['type'], ENT_QUOTES, 'UTF-8') ?>
                    -
                    Jumlah:
                    Rp
                    <?= htmlspecialchars(
                        number_format((float) $transaction['amount'], 2, ',', '.'),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>