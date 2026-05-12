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

$sonuc = "";
$urunler = [];

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $model    = $_POST["model"] ?? "";
    $yil      = $_POST["yil"] ?? "";
    $parca    = trim($_POST["parca"] ?? "");
    $stok_kod = trim($_POST["stok_kod"] ?? "");

    $query = "SELECT * FROM `stok` WHERE `Marka` = 'Volkswagen'";
    $params = [];

    if($model)    { $query .= " AND `Model` = ?";          $params[] = $model; }
    if($yil)      { $query .= " AND `Yil` = ?";            $params[] = $yil; }
    if($parca)    { $query .= " AND `Aciklama` LIKE ?";     $params[] = "%$parca%"; }
    if($stok_kod) { $query .= " AND `Stok_Kodu` = ?";      $params[] = $stok_kod; }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($urunler){
        $sonuc = "<div class='alert success'>✅ ".count($urunler)." ürün bulundu.</div>";
    } else {
        $sonuc = "<div class='alert error'>❌ Stokta ürün bulunamadı!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Volkswagen Yedek Parça | Volkswagen Group Parça Dünyası</title>
<style>
body { margin:0; font-family:'Poppins', sans-serif; background:#0f1116; color:#fff; display:flex; flex-direction:column; }
header { background: linear-gradient(135deg,#007bff,#00c6ff,#ff0080); color:white; padding:60px 20px; text-align:center; }
header h1 { margin:0; font-size:3em; letter-spacing:2px; }
nav { background:#111; }
nav ul { list-style:none; margin:0; padding:0; display:flex; justify-content:center; }
nav a { padding:16px 28px; display:block; color:white; text-decoration:none; }
nav a:hover { background:#007bff; }
.container { display:flex; max-width:1200px; margin:auto; padding:40px 20px; gap:40px; }
.panel { background:#1c1f29; padding:25px; border-radius:15px; width:300px; }
.panel h3 { text-align:center; color:#00c6ff; }
.panel label { display:block; margin:10px 0 5px; }
.panel select, .panel input { width:100%; padding:10px; border-radius:8px; border:none; margin-bottom:15px; background:#2b2e3b; color:white; }
.panel button { width:100%; padding:12px; border:none; border-radius:8px; background:#28a745; color:white; font-weight:600; cursor:pointer; }
.panel button:hover { background:#218838; }
.alert { padding:15px; border-radius:8px; margin-top:15px; text-align:center; font-weight:bold; }
.alert.success { background:#d4edda; color:#155724; }
.alert.error { background:#f8d7da; color:#721c24; }
.content { flex:1; }
.content h2 { font-size:2em; margin-bottom:20px; }
.urun-container { display:flex; justify-content:space-between; align-items:flex-start; border:1px solid #555; padding:15px; margin:15px 0; border-radius:8px; background:#1c1f29; }
.urun-bilgi { width:50%; }
.urun-bilgi p { margin:5px 0; }
.urun-aciklama { width:30%; padding-left:20px; border-left:2px solid #007bff; }
.urun-aciklama h4 { margin-top:0; color:#00c6ff; }
.urun-resim { width:20%; text-align:center; }
.urun-resim img { max-width:100%; border-radius:8px; cursor:pointer; transition:transform 0.3s ease; }
.urun-resim img:hover { transform:scale(1.05); }
.modal { display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.8); justify-content:center; align-items:center; }
.modal img { max-width:80%; max-height:80%; border:3px solid white; border-radius:10px; }
</style>
</head>
<body>
<header>
    <h1>Volkswagen Yedek Parça</h1>
    <p>Aracınız için doğru parça, güvenilir hizmet!</p>
</header>
<nav>
<ul>
    <li><a href="index.php">Ana Sayfa</a></li>
    <li><a href="stok.php">Stok Arama</a></li>
    <li><a href="#">Hakkımızda</a></li>
    <li><a href="#">İletişim</a></li>
</ul>
</nav>
<div class="container">
    <div class="panel">
        <h3>Stok Arama</h3>
        <form method="post">
            <label for="model">Model</label>
            <select name="model" id="model">
                <option value="">Seçiniz</option>
                <?php
                $vwModeller = ["Arteon","Bora","EOS","Golf","Jetta","Passat","Passat Alltrack","Passat Variant","Lupo","Polo","Scirocco","VW CC"];
                foreach($vwModeller as $m): ?>
                    <option value="<?=$m?>"><?=$m?></option>
                <?php endforeach; ?>
            </select>

            <label for="yil">Model Yılı</label>
            <select name="yil" id="yil">
                <option value="">Seçiniz</option>
                <?php for($y=2000;$y<=2025;$y++): ?>
                    <option value="<?=$y?>"><?=$y?></option>
                <?php endfor; ?>
            </select>

            <label for="parca">Parça Adı</label>
            <input type="text" name="parca" id="parca" placeholder="Örn: Fren Balatası">

            <label for="stok_kod">Stok Kodu</label>
            <input type="text" name="stok_kod" id="stok_kod" placeholder="Örn: VW1234">

            <button type="submit">Ara</button>
        </form>
        <div class="sonuc"><?= $sonuc ?></div>
    </div>

    <div class="content">
        <h2>Orijinal Volkswagen Yedek Parçalar</h2>
        <p>Volkswagen araçları için en kaliteli orijinal yedek parçaları stoklarımızda bulabilirsiniz.</p>

        <?php if(!empty($urunler)): ?>
            <?php foreach($urunler as $u): ?>
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
                        <?php
                        $foto = $u['fotograf'] ?? '';
                        if ($foto && file_exists(__DIR__ . "/" . $foto)): ?>
                            <img src="<?= htmlspecialchars($foto) ?>" alt="Ürün Görseli" onclick="openModal('<?= htmlspecialchars($foto) ?>')">
                        <?php else: ?>
                            <p>📷 Görsel Yok</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="modal" id="imgModal" onclick="closeModal()">
    <img id="modalImage" src="" alt="Büyük Görsel">
</div>

<script>
function openModal(src) {
    document.getElementById('modalImage').src = src;
    document.getElementById('imgModal').style.display = 'flex';
}
function closeModal() {
    document.getElementById('imgModal').style.display = 'none';
}
</script>
</body>
</html>