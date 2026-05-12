<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: yonetim.php");
    exit;
}

$dsn = "mysql:host=localhost;dbname=volkswagenyp;charset=utf8";
$user = "root";
$pass = "";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// --- Silme ---
if (isset($_GET['sil'])) {
    $silID = $_GET['sil'];
    $sil = $pdo->prepare("DELETE FROM stok WHERE `Stok_Kodu`=?");
    $sil->execute([$silID]);
    header("Location: yntm.php?durum=silindi");
    exit;
}

// --- Güncelleme ---
if (isset($_POST['guncelle'])) {
    $stok     = $_POST['Stok_Kodu'];
    $marka    = $_POST['Marka'];
    $model    = $_POST['Model'];
    $yil      = $_POST['Yil'];
    $aciklama = $_POST['Aciklama'];
    $fiyat    = $_POST['Fiyat'];
    $fotograf = $_POST['fotograf'];

    $guncelle = $pdo->prepare("UPDATE stok SET `Marka`=?, `Model`=?, `Yil`=?, `Aciklama`=?, `Fiyat`=?, `fotograf`=? WHERE `Stok_Kodu`=?");
    $guncelle->execute([$marka, $model, $yil, $aciklama, $fiyat, $fotograf, $stok]);
    header("Location: yntm.php?durum=guncellendi");
    exit;
}

// --- Ekleme ---
if (isset($_POST['ekle'])) {
    $stok     = $_POST['Stok_Kodu'];
    $marka    = $_POST['Marka'];
    $model    = $_POST['Model'];
    $yil      = $_POST['Yil'];
    $aciklama = $_POST['Aciklama'];
    $fiyat    = $_POST['Fiyat'];
    $fotograf = $_POST['fotograf'];

    $ekle = $pdo->prepare("INSERT INTO stok (`Stok_Kodu`, `Marka`, `Model`, `Yil`, `Aciklama`, `Fiyat`, `fotograf`) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $ekle->execute([$stok, $marka, $model, $yil, $aciklama, $fiyat, $fotograf]);
    header("Location: yntm.php?durum=eklendi");
    exit;
}

// --- Verileri Çek ---
$veriler = $pdo->query("SELECT * FROM stok")->fetchAll(PDO::FETCH_ASSOC);

// --- Fotoğrafları Al ---
$fotolar = [];
$klasor = __DIR__ . "/stok_foto";
if (is_dir($klasor)) {
    $dosyalar = scandir($klasor);
    foreach ($dosyalar as $dosya) {
        if (preg_match("/\.(jpg|jpeg|png|gif|webp)$/i", $dosya)) {
            $fotolar[] = "stok_foto/" . $dosya;
        }
    }
}

// --- Mesaj ---
$mesaj = "";
if (isset($_GET['durum'])) {
    if ($_GET['durum'] == "guncellendi") $mesaj = "✅ Bilgi başarıyla güncellendi!";
    if ($_GET['durum'] == "silindi")     $mesaj = "🗑️ Kayıt silindi!";
    if ($_GET['durum'] == "eklendi")     $mesaj = "🆕 Yeni ürün başarıyla eklendi!";
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Volkswagen Yönetim Paneli</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
    body { font-family:'Poppins',sans-serif; background:#0f1116; color:white; margin:0; padding:0; }
    header { background:linear-gradient(90deg,#007bff,#00c6ff,#ff0080); padding:20px 0; text-align:center; position:relative; }
    .logout-btn { position:absolute; right:20px; top:30px; color:white; text-decoration:none; border:1px solid white; padding:5px 15px; border-radius:5px; }
    .mesaj { text-align:center; margin:20px auto; width:60%; padding:12px; border-radius:10px; font-weight:600; }
    .success { background:#00c851; color:white; }
    .delete  { background:#ff4444; color:white; }
    .add     { background:#007bff; color:white; }
    table { width:95%; margin:20px auto; border-collapse:collapse; background:#151821; border-radius:12px; overflow:hidden; }
    th,td { padding:12px 15px; text-align:center; border-bottom:1px solid #222; }
    th { background:linear-gradient(90deg,#007bff,#00c6ff); color:white; }
    img.foto { width:60px; height:60px; border-radius:8px; object-fit:cover; }
    input[type=text], select { width:100px; padding:6px; border-radius:5px; border:1px solid #333; background:#222; color:white; }
    button { padding:8px 12px; border:none; border-radius:8px; cursor:pointer; font-weight:600; }
    .guncelle { background:#00c6ff; color:black; }
    .sil { background:#ff3366; color:white; text-decoration:none; padding:7px 12px; border-radius:8px; font-size:13px; }
    .ekle-btn { background:#00c851; color:white; width:150px; height:40px; }
    form.add-form { display:flex; flex-wrap:wrap; justify-content:center; gap:10px; margin:30px 0; background:#1c1f29; padding:20px; }
</style>
</head>
<body>

<header>
    <h1>🚗 Stok Yönetim Paneli</h1>
    <a href="yonetim.php?cikis=1" class="logout-btn">Güvenli Çıkış</a>
</header>

<?php if ($mesaj): ?>
    <div class="mesaj <?php
        if ($_GET['durum'] == 'guncellendi') echo 'success';
        elseif ($_GET['durum'] == 'silindi') echo 'delete';
        else echo 'add';
    ?>">
        <?php echo $mesaj; ?>
    </div>
<?php endif; ?>

<form method="post" class="add-form">
    <input type="text" name="Stok_Kodu" placeholder="Stok Kodu" required>
    <select name="Marka" id="marka" onchange="modelDoldur()" required>
        <option value="">-- Marka --</option>
        <option value="Volkswagen">Volkswagen</option>
        <option value="Audi">Audi</option>
        <option value="Seat">Seat</option>
        <option value="Skoda">Skoda</option>
    </select>
    <select name="Model" id="model" required>
        <option value="">-- Model --</option>
    </select>
    <input type="text" name="Yil" placeholder="Yıl">
    <input type="text" name="Aciklama" placeholder="Açıklama">
    <input type="text" name="Fiyat" placeholder="Fiyat">
    <select name="fotograf" required>
        <option value="">-- Foto Seç --</option>
        <?php foreach ($fotolar as $foto): ?>
            <option value="<?php echo htmlspecialchars($foto); ?>"><?php echo basename($foto); ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="ekle" class="ekle-btn">Ürün Ekle</button>
</form>

<table>
    <tr>
        <th>Foto</th>
        <th>Stok Kodu</th>
        <th>Marka</th>
        <th>Model</th>
        <th>Yıl</th>
        <th>Açıklama</th>
        <th>Fiyat</th>
        <th>İşlemler</th>
    </tr>
    <?php foreach ($veriler as $v): ?>
    <tr>
        <form method="post">
            <td><img class="foto" src="<?php echo htmlspecialchars($v['fotograf'] ?? ''); ?>" alt="Foto"></td>
            <td><input type="text" name="Stok_Kodu" value="<?php echo htmlspecialchars($v['Stok_Kodu'] ?? ''); ?>" readonly></td>
            <td><input type="text" name="Marka"     value="<?php echo htmlspecialchars($v['Marka']     ?? ''); ?>"></td>
            <td><input type="text" name="Model"     value="<?php echo htmlspecialchars($v['Model']     ?? ''); ?>"></td>
            <td><input type="text" name="Yil"       value="<?php echo htmlspecialchars($v['Yil']       ?? ''); ?>"></td>
            <td><input type="text" name="Aciklama"  value="<?php echo htmlspecialchars($v['Aciklama']  ?? ''); ?>"></td>
            <td><input type="text" name="Fiyat"     value="<?php echo htmlspecialchars($v['Fiyat']     ?? ''); ?>"></td>
            <td>
                <input type="hidden" name="fotograf" value="<?php echo htmlspecialchars($v['fotograf'] ?? ''); ?>">
                <button type="submit" name="guncelle" class="guncelle">Güncelle</button>
                <a href="?sil=<?php echo urlencode($v['Stok_Kodu']); ?>" class="sil" onclick="return confirm('Silinsin mi?')">Sil</a>
            </td>
        </form>
    </tr>
    <?php endforeach; ?>
</table>

<script>
const araclar = {
    "Volkswagen": ["Arteon","Bora","EOS","Golf","Jetta","Passat","Passat Alltrack","Passat Variant","Lupo","Polo","Scirocco","VW CC"],
    "Audi":  ["A1","A3","A4","A5","A6","A7","A8","R8","RS3","RS4","RS5","RS6","RS7","S3","S4","S5","S6","S7","S8","TT","TTS","80 Serisi","90 Serisi","100 Serisi","Cabrio"],
    "Seat":  ["Leon","Ibiza","Altea","Cordoba","Toledo"],
    "Skoda": ["Octavia","Superb","Fabia","Favorit","Felicia","Forman","Rapid","Roomster","Scala"]
};
function modelDoldur() {
    const marka = document.getElementById("marka").value;
    const modelSelect = document.getElementById("model");
    modelSelect.innerHTML = "<option value=''>-- Model --</option>";
    if (marka && araclar[marka]) {
        araclar[marka].forEach(m => {
            let opt = document.createElement("option");
            opt.value = m; opt.textContent = m;
            modelSelect.appendChild(opt);
        });
    }
}
</script>
</body>
</html>