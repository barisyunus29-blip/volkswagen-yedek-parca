<?php
$host = "localhost";
$db = "volkswagenyp";
$user = "root";
$pass = "";
$dsn = "mysql:host=$host;dbname=$db;charset=utf8";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}

$kategoriler = [
    "Volkswagen" => ["Arteon","Bora","EOS","Golf","Jetta","Passat","Passat Alltrack","Passat Variant","Lupo","Polo","Scirocco","VW CC"],
    "Audi"       => ["A1","A3","A4","A5","A6","A7","A8","R8","RS3","RS4","RS5","RS6","RS7","S3","S4","S5","S6","S7","S8","TT","TTS","80 Serisi","90 Serisi","100 Serisi","Cabrio"],
    "Seat"       => ["Leon","Ibiza","Altea","Cordoba","Toledo"],
    "Skoda"      => ["Octavia","Superb","Fabia","Favorit","Felicia","Forman","Rapid","Roomster","Scala"]
];

$sonuc = "";
$urunler = [];
$aramayapildi = false;

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $aramayapildi = true;
    $marka    = $_POST["marka"] ?? "";
    $model    = $_POST["model"] ?? "";
    $yil      = $_POST["yil"] ?? "";
    $parca    = trim($_POST["parca"] ?? "");

    $query = "SELECT * FROM stok WHERE 1=1";
    $params = [];

    if($marka)  { $query .= " AND `Marka` = ?";      $params[] = $marka; }
    if($model)  { $query .= " AND `Model` = ?";      $params[] = $model; }
    if($yil)    { $query .= " AND `Yil` = ?";        $params[] = $yil; }
    if($parca)  { $query .= " AND `Aciklama` LIKE ?"; $params[] = "%$parca%"; }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($urunler){
        $sonuc = "<div class='alert success'>✅ " . count($urunler) . " ürün bulundu.</div>";
    } else {
        $sonuc = "<div class='alert error'>❌ Stokta ürün bulunamadı!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Stok Arama</title>
    <style>
        body {
            margin:0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0f1116;
            color: #fff;
            text-align: center;
        }
        header {
            background: linear-gradient(90deg,#212529,#343a40);
            color: white;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        header h1 { margin:0; font-size:2.2em; letter-spacing:1px; }

        form {
            background: #1c1f29;
            display: inline-block;
            margin-top: 40px;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.4);
            text-align: left;
            width: 350px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #aaa;
        }
        select, input[type=text] {
            width: 100%;
            padding: 12px;
            margin-bottom: 18px;
            border: 1px solid #333;
            border-radius: 8px;
            font-size: 1em;
            background: #2b2e3b;
            color: white;
            box-sizing: border-box;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: bold;
            width: 100%;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover { background: #0056b3; }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin: 20px auto;
            width: 60%;
            font-weight: bold;
            text-align: center;
        }
        .alert.success { background: #d4edda; color: #155724; }
        .alert.error   { background: #f8d7da; color: #721c24; }

        /* Sonuç tablosu */
        .sonuclar {
            max-width: 1000px;
            margin: 30px auto;
            text-align: left;
        }
        .urun-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border: 1px solid #333;
            padding: 15px 20px;
            margin: 12px 0;
            border-radius: 10px;
            background: #1c1f29;
        }
        .urun-bilgi { width: 55%; }
        .urun-bilgi p { margin: 6px 0; }
        .urun-aciklama {
            width: 35%;
            padding-left: 20px;
            border-left: 2px solid #007bff;
        }
        .urun-aciklama h4 { margin-top: 0; color: #00c6ff; }
        .urun-resim { width: 10%; text-align: center; }
        .urun-resim img {
            max-width: 70px;
            border-radius: 8px;
        }

        a {
            display: inline-block;
            margin-top: 25px;
            text-decoration: none;
            color: #007bff;
            font-weight: 600;
        }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <header>
        <h1>🔍 Stok Arama</h1>
    </header>

    <form method="post">
        <label for="marka">Marka</label>
        <select name="marka" id="marka">
            <option value="">Seçiniz</option>
            <?php foreach ($kategoriler as $marka => $modeller): ?>
                <option value="<?= $marka ?>" <?= (($_POST['marka'] ?? '') == $marka) ? 'selected' : '' ?>>
                    <?= $marka ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="model">Model</label>
        <select name="model" id="model">
            <option value="">Önce marka seçiniz</option>
            <?php
            // POST sonrası seçili modelleri koru
            $secilenMarka = $_POST['marka'] ?? '';
            if ($secilenMarka && isset($kategoriler[$secilenMarka])) {
                foreach ($kategoriler[$secilenMarka] as $m) {
                    $sel = (($_POST['model'] ?? '') == $m) ? 'selected' : '';
                    echo "<option value='$m' $sel>$m</option>";
                }
            }
            ?>
        </select>

        <label for="yil">Model Yılı</label>
        <select name="yil" id="yil">
            <option value="">Seçiniz</option>
            <?php for ($y=2000; $y<=2025; $y++): ?>
                <option value="<?= $y ?>" <?= (($_POST['yil'] ?? '') == $y) ? 'selected' : '' ?>>
                    <?= $y ?>
                </option>
            <?php endfor; ?>
        </select>

        <label for="parca">Parça Adı</label>
        <input type="text" name="parca" id="parca"
               placeholder="Örn: Fren Balatası"
               value="<?= htmlspecialchars($_POST['parca'] ?? '') ?>">

        <button type="submit">Ara</button>
    </form>

    <a href="index.php">← Ana Sayfa</a>

    <!-- Sonuçlar -->
    <?php if ($aramayapildi): ?>
        <?= $sonuc ?>
        <?php if (!empty($urunler)): ?>
            <div class="sonuclar">
                <?php foreach ($urunler as $u): ?>
                    <div class="urun-container">
                        <div class="urun-bilgi">
                            <p><strong>Stok Kodu:</strong> <?= htmlspecialchars($u['Stok_Kodu']) ?></p>
                            <p><strong>Marka:</strong>     <?= htmlspecialchars($u['Marka']) ?></p>
                            <p><strong>Model:</strong>     <?= htmlspecialchars($u['Model']) ?></p>
                            <p><strong>Yıl:</strong>       <?= htmlspecialchars($u['Yil']) ?></p>
                            <p><strong>Fiyat:</strong>     <?= htmlspecialchars($u['Fiyat']) ?> ₺</p>
                        </div>
                        <div class="urun-aciklama">
                            <h4>Açıklama</h4>
                            <p><?= htmlspecialchars($u['Aciklama']) ?></p>
                        </div>
                        <div class="urun-resim">
                            <?php if (!empty($u['fotograf']) && file_exists(__DIR__ . "/" . $u['fotograf'])): ?>
                                <img src="<?= htmlspecialchars($u['fotograf']) ?>" alt="Görsel">
                            <?php else: ?>
                                <span>📷</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <script>
        const kategoriler = <?= json_encode($kategoriler) ?>;
        const markaSelect = document.getElementById("marka");
        const modelSelect = document.getElementById("model");

        markaSelect.addEventListener("change", function() {
            const secilenMarka = this.value;
            modelSelect.innerHTML = "<option value=''>Seçiniz</option>";
            if (kategoriler[secilenMarka]) {
                kategoriler[secilenMarka].forEach(model => {
                    const opt = document.createElement("option");
                    opt.value = model;
                    opt.textContent = model;
                    modelSelect.appendChild(opt);
                });
            }
        });
    </script>
</body>
</html>