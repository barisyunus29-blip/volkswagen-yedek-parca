<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Çıkış işlemi
if(isset($_GET['cikis'])){
    session_destroy();
    header("Location: yonetim.php");
    exit;
}

// Eğer zaten giriş yapılmışsa yntm.php'ye yönlendir
if(isset($_SESSION['admin'])){
    header("Location: yntm.php");
    exit;
}

// Kullanıcı adı ve şifre
$kullanici_adi = "yunus";
$sifre = "yunus123";
$hata = "";

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $girdi_kullanici = $_POST['kullanici_adi'] ?? "";
    $girdi_sifre = $_POST['sifre'] ?? "";

    if($girdi_kullanici === $kullanici_adi && $girdi_sifre === $sifre){
        $_SESSION['admin'] = true;
        header("Location: yntm.php");
        exit;
    } else {
        $hata = "❌ Kullanıcı adı veya şifre yanlış!";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Yönetim Girişi</title>
<style>
body { margin:0; font-family:'Poppins', sans-serif; background:#0f1116; color:#fff; display:flex; justify-content:center; align-items:center; height:100vh; }
.login-box { background:#1c1f29; padding:40px; border-radius:15px; width:350px; }
h2 { text-align:center; color:#00c6ff; margin-bottom:20px; }
input { width:100%; padding:10px; margin:10px 0; border:none; border-radius:8px; background:#2b2e3b; color:white; }
button { width:100%; padding:12px; border:none; border-radius:8px; background:#28a745; color:white; font-weight:600; cursor:pointer; }
button:hover { background:#218838; }
.alert { padding:10px; border-radius:8px; text-align:center; margin-bottom:10px; }
.alert.error { background:#f8d7da; color:#721c24; }
</style>
</head>
<body>

<div class="login-box">
    <h2>Yönetim Girişi</h2>
    <?php if($hata): ?>
        <div class="alert error"><?= $hata ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="kullanici_adi" placeholder="Kullanıcı Adı" required>
        <input type="password" name="sifre" placeholder="Şifre" required>
        <button type="submit">Giriş Yap</button>
    </form>
</div>

</body>
</html>