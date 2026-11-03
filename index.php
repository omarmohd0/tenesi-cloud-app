<?php
require_once 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add_customer') {
            $stmt = $pdo->prepare("INSERT INTO customers (full_name, phone_number, email, address) VALUES (?, ?, ?, ?)");
            $stmt->execute([$_POST['full_name'], $_POST['phone_number'], $_POST['email'], $_POST['address']]);
            $message = "Mteja amesajiliwa kwa mafanikio!";
        } elseif ($_POST['action'] == 'add_project') {
            $stmt = $pdo->prepare("INSERT INTO projects (customer_id, project_name, location, budget, start_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['customer_id'], $_POST['project_name'], $_POST['location'], $_POST['budget'], $_POST['start_date']]);
            $message = "Mradi wa ujenzi umeanishwa kwa mafanikio!";
        } elseif ($_POST['action'] == 'add_expense') {
            $stmt = $pdo->prepare("INSERT INTO expenses (project_id, item_name, quantity, unit_price, purchase_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['project_id'], $_POST['item_name'], $_POST['quantity'], $_POST['unit_price'], $_POST['purchase_date']]);
            $message = "Gharama ya vifaa imerekodiwa kwa mafanikio!";
        }
    }
}

$customers = $pdo->query("SELECT * FROM customers ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$projects = $pdo->query("SELECT projects.*, customers.full_name FROM projects JOIN customers ON projects.customer_id = customers.id ORDER BY projects.id DESC")->fetchAll(PDO::FETCH_ASSOC);
$expenses = $pdo->query("SELECT expenses.*, projects.project_name FROM expenses JOIN projects ON expenses.project_id = projects.id ORDER BY expenses.id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenesiconstruct - Mfumo Kamili wa Usimamizi wa Ujenzi</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h2, h3 { color: #0056b3; border-bottom: 2px solid #eee; padding-bottom: 8px; }
        .form-section { background: #f9f9f9; padding: 15px; margin-bottom: 25px; border-radius: 6px; border: 1px solid #ddd; }
        .form-group { margin-bottom: 12px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #28a745; color: white; border: none; padding: 10px 18px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #218838; }
        .alert { background: #d4edda; color: #155724; padding: 12px; margin-bottom: 20px; border-radius: 4px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tenesiconstruct - Mfumo wa Usimamizi wa Ujenzi</h2>
        
        <?php if (!empty($message)): ?>
            <div class="alert"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- 1. Fomu ya Wateja -->
        <div class="form-section">
            <h3>1. Sajili Mteja Mpya</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add_customer">
                <div class="form-group"><label>Jina Kamili:</label><input type="text" name="full_name" required></div>
                <div class="form-group"><label>Namba ya Simu:</label><input type="text" name="phone_number" required></div>
                <div class="form-group"><label>Barua Pepe:</label><input type="email" name="email"></div>
                <div class="form-group"><label>Anwani:</label><textarea name="address"></textarea></div>
                <button type="submit">Hifadhi Mteja</button>
            </form>
        </div>

        <!-- 2. Fomu ya Miradi -->
        <div class="form-section">
            <h3>2. Anzisha Mradi Mpya wa Ujenzi</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add_project">
                <div class="form-group">
                    <label>Chagua Mteja:</label>
                    <select name="customer_id" required>
                        <option value="">-- Chagua Mteja --</option>
                        <?php foreach($customers as $c): ?>
                            <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['full_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Jina la Mradi:</label><input type="text" name="project_name" required></div>
                <div class="form-group"><label>Eneo la Ujenzi:</label><input type="text" name="location" required></div>
                <div class="form-group"><label>Bajeti (TZS):</label><input type="number" step="0.01" name="budget" required></div>
                <div class="form-group"><label>Tarehe ya Kuanza:</label><input type="date" name="start_date" required></div>
                <button type="submit">Anzisha Mradi</button>
            </form>
        </div>

        <!-- 3. Fomu ya Gharama -->
        <div class="form-section">
            <h3>3. Rekodi Gharama za Vifaa / Matumizi</h3>
            <form method="POST">
                <input type="hidden" name="action" value="add_expense">
                <div class="form-group">
                    <label>Chagua Mradi:</label>
                    <select name="project_id" required>
                        <option value="">-- Chagua Mradi --</option>
                        <?php foreach($projects as $p): ?>
                            <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['project_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label>Jina la Kitu / Nyenzo:</label><input type="text" name="item_name" required></div>
                <div class="form-group"><label>Idadi (Quantity):</label><input type="number" step="0.01" name="quantity" required></div>
                <div class="form-group"><label>Bei ya Kimoja (Unit Price):</label><input type="number" step="0.01" name="unit_price" required></div>
                <div class="form-group"><label>Tarehe ya Manunuzi:</label><input type="date" name="purchase_date" required></div>
                <button type="submit">Rekodi Gharama</button>
            </form>
        </div>

        <!-- Orodha ya Taarifa -->
        <h3>Orodha Kuu ya Mfumo</h3>
        
        <h4>Wateja</h4>
        <table>
            <tr><th>Jina</th><th>Simu</th><th>Barua Pepe</th></tr>
            <?php foreach($customers as $c): ?>
            <tr><td><?php echo htmlspecialchars($c['full_name']); ?></td><td><?php echo htmlspecialchars($c['phone_number']); ?></td><td><?php echo htmlspecialchars($c['email']); ?></td></tr>
            <?php endforeach; ?>
        </table>

        <h4>Miradi ya Ujenzi</h4>
        <table>
            <tr><th>Mradi</th><th>Mteja</th><th>Eneo</th><th>Bajeti</th><th>Hali</th></tr>
            <?php foreach($projects as $p): ?>
            <tr><td><?php echo htmlspecialchars($p['project_name']); ?></td><td><?php echo htmlspecialchars($p['full_name']); ?></td><td><?php echo htmlspecialchars($p['location']); ?></td><td><?php echo number_format($p['budget'], 2); ?></td><td><?php echo $p['status']; ?></td></tr>
            <?php endforeach; ?>
        </table>

        <h4>Gharama na Vifaa</h4>
        <table>
            <tr><th>Mradi</th><th>Nyenzo</th><th>Idadi</th><th>Bei Moja</th><th>Jumla</th></tr>
            <?php foreach($expenses as $e): ?>
            <tr><td><?php echo htmlspecialchars($e['project_name']); ?></td><td><?php echo htmlspecialchars($e['item_name']); ?></td><td><?php echo $e['quantity']; ?></td><td><?php echo number_format($e['unit_price'], 2); ?></td><td><?php echo number_format($e['total_cost'], 2); ?></td></tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
