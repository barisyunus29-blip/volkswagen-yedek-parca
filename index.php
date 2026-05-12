<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Volkswagen Group Parça Dünyası</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin:0; 
            font-family:'Poppins', sans-serif;
            background:#0f1116; 
            color:#fff;
        }

        /* Header */
        header {
            background: linear-gradient(135deg,#007bff,#00c6ff,#ff0080);
            color:white; 
            padding:40px 60px;
            box-shadow:0 4px 25px rgba(0,0,0,0.7);
        }

        .header-container {
            display: flex;
            align-items: center; /* dikey ortala */
            justify-content: center; /* yatayda ortala */
            gap: 40px;
            flex-wrap: wrap;
        }

        .logo {
            width:160px; /* daha büyük */
            height:160px;
            border-radius:50%;
            object-fit:contain; /* kaliteyi bozmadan gösterir */
            box-shadow:0 8px 25px rgba(0,0,0,0.6);
            transition: transform 0.3s ease, filter 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
            filter: brightness(110%);
        }

        .header-text {
            text-align: center; /* yazıyı ortaya al */
        }

        .header-text h1 {
            margin:0;
            font-size:3.5em;
            font-family:"anydore";
            letter-spacing:3px;
            text-shadow:2px 2px 12px rgba(0,0,0,0.7);
        }

        .header-text p {
            margin:10px 0 0; 
            font-size:1.6em;
            font-weight:600;
            background: linear-gradient(90deg, red, orange, yellow, lime, cyan, blue, violet, red);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-size: 400% 100%;
            animation: rgbtext 6s linear infinite;
        }

        @keyframes rgbtext {
            0% {background-position:0%;}
            100% {background-position:100%;}
        }

        /* Menü */
        nav {
            background:#111; 
            box-shadow:0 3px 10px rgba(0,0,0,0.6);
        }
        nav ul {
            list-style:none; 
            margin:0; 
            padding:0;
            display:flex; 
            justify-content:center; 
            flex-wrap:wrap;
        }
        nav li { margin:0; }
        nav a {
            display:block; 
            padding:16px 28px;
            color:white; 
            text-decoration:none;
            font-weight:600; 
            letter-spacing:1px;
            transition:all 0.3s;
        }
        nav a:hover {
            background:linear-gradient(90deg,#007bff,#ff00ff);
            color:white; 
            text-shadow:0 0 10px black;
        }

        /* Galeri */
        .gallery {
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
            gap:25px; 
            padding:50px 30px; 
            max-width:1200px; 
            margin:auto;
        }
        .card {
            position:relative; 
            overflow:hidden; 
            cursor:pointer;
            border-radius:15px;
            box-shadow:0 8px 20px rgba(0,0,0,0.6);
            transition:transform 0.4s;
        }
        .card:hover { transform:translateY(-12px) scale(1.03); }
        .card img {
            width:100%; 
            height:230px; 
            object-fit:cover;
            display:block; 
            filter:brightness(85%);
            transition:0.5s;
        }
        .card:hover img { filter:brightness(60%); }
        .card h3 {
            position:absolute; 
            bottom:0; left:0; right:0;
            margin:0; 
            padding:15px; 
            background:rgba(0,0,0,0.7);
            font-size:1.5em; 
            color:#fff; 
            text-align:center;
            letter-spacing:1px;
        }

        /* Ana içerik */
        main { text-align:center; padding:60px 20px; }
        main h2 { font-size:2.5em; margin-bottom:20px; color:#00c6ff; }
        main p { font-size:1.2em; color:#ddd; max-width:900px; margin:auto; }

        /* Buton */
        .btn {
            display:inline-block; 
            margin-top:35px;
            background: #FFD700; /* sarı */
            color:black; 
            padding:15px 32px; 
            border-radius:10px;
            font-size:1.2em; 
            font-weight:600;
            text-decoration:none; 
            transition:all 0.3s;
        }
        .btn:hover { 
            filter:brightness(115%); 
            transform:scale(1.05); 
        }

        /* Responsive */
        @media(max-width:900px){
            .header-container { flex-direction: column; align-items:center; }
            .logo { width:140px; height:140px; }
            .header-text h1 { font-size:2.2em; }
            .header-text p { font-size:1.2em; }
        }
    </style>
</head>
<body>
   <header>
    <div class="header-container">
        <!-- Logo'ya tıklanınca yönetim.php'ye gider -->
        <a href="yonetim.php">
            <img src="stok_foto/logoo.jpg" alt="Logo" class="logo" style="cursor:pointer;">
        </a>
        <div class="header-text">
            <h1>Volkswagen Group Parça Dünyası</h1>
            <p>🚗 Aracınız için doğru parça, güvenilir hizmet!</p>
        </div>
    </div>
</header>


    <nav>
        <ul>
            <li><a href="index.php">Ana Sayfa</a></li>
            <li><a href="stok.php">Stok Arama</a></li>
            <li><a href="#">Hakkımızda</a></li>
            <li><a href="#">İletişim</a></li>
        </ul>
    </nav>

    <!-- Fotoğraf Kartları -->
    <section class="gallery">
        <div class="card" onclick="window.location.href='vw.php'">
            <img src="stok_foto/W1.png" alt="Volkswagen">
            <h3>Volkswagen</h3>
        </div>
        <div class="card" onclick="window.location.href='audi.php'">
            <img src="stok_foto/W2.jpg" alt="Audi">
            <h3>Audi</h3>
        </div>
        <div class="card" onclick="window.location.href='skoda.php'">
            <img src="stok_foto/W3.jpg" alt="Skoda">
            <h3>Skoda</h3>
        </div>
        <div class="card" onclick="window.location.href='seat.php'">
            <img src="stok_foto/W4.jpg" alt="Seat">
            <h3>Seat</h3>
        </div>
    </section>

    <main>
        <h2>✨ Orijinal Yedek Parçalar</h2>
        <p>Volkswagen, Audi, Seat ve Skoda için en kaliteli orijinal yedek parçaları stoklarımızda bulabilirsiniz. 
        Güvenli alışveriş ve hızlı teslimat için bizi tercih edin.</p>
        <a href="stok.php" class="btn">🔍 Stokta Ara</a>
    </main>
</body>
</html>