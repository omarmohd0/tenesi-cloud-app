<?php
require_once 'db.php';

$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    try {
        $stmt = $pdo->prepare("INSERT INTO customers (full_name, phone_number, email, address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$full_name, $phone_number, $email, $address]);
        $success_message = "Mteja amesajiliwa mafanikio makubwa kwenye mfumo wa Tenesiconstruct!";
    } catch (\PDOException $e) {
        $success_message = "Imeshindikana kusajili: " . $e->getMessage();
    }
}

// Chukua Orodha ya Wateja
$stmt = $pdo->query("SELECT * FROM customers ORDER BY id DESC");
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenesiconstruct - Mfumo wa Usimamizi wa Ujenzi</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .alert { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f8f9fa; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tenesiconstruct - Usajili wa Wateja na Miradi</h2>
        
        <?php if (!empty($success_message)): ?>
            <div class="alert"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Jina Kamili la Mteja:</label>
                <input type="text" name="full_name" required>
            </div>
            <div class="form-group">
                <label>Namba ya Simu:</label>
                <input type="text" name="phone_number" required>
            </div>
            <div class="form-group">
                <label>Barua Pepe (Email):</label>
                <input type="email" name="email">
            </div>
            <div class="form-group">
                <label>Anwani / Eneo:</label>
                <textarea name="address"></textarea>
            </div>
            <button type="submit">Hifadhi Taarifa za Mteja</button>
        </form>

        <h3 style="margin-top: 40px;">Orodha ya Wateja Waliosajiliwa</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Jina Kamili</th>
                <th>Simu</th>
                <th>Barua Pepe</th>
                <th>Eneo</th>
            </tr>
            <?php if (count($customers) > 0): ?>
                <?php foreach ($customers as $c): ?>
                <tr>
                    <td><?php echo $c['id']; ?></td>
                    <td><?php echo htmlspecialchars($c['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($c['phone_number']); ?></td>
                    <td><?php echo htmlspecialchars($c['email']); ?></td>
                    <td><?php echo htmlspecialchars($c['address']); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5" style="text-align: center;">Hakuna wateja waliosajiliwa bado.</td></tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
