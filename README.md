# Volkswagen Group Yedek Parça Dünyası

Bu proje, Volkswagen, Audi, Seat ve Skoda marka araçlar için yedek parça stok takibi ve yönetimini sağlayan bir web uygulamasıdır.

## Özellikler

- **Ana Sayfa:** Marka bazlı hızlı erişim kartları.
- **Stok Arama:** Marka, model ve yıla göre filtreleme.
- **Yönetim Paneli:** Ürün ekleme, silme ve güncelleme işlemleri.
- **Responsive Tasarım:** Mobil ve masaüstü uyumlu arayüz.

## Kurulum

1. Proje dosyalarını sunucunuza (Apache/PHP destekli) yükleyin.
2. `database.sql` dosyasını MySQL veritabanınıza içe aktarın.
3. `yntm.php` ve diğer dosyalardaki veritabanı bağlantı bilgilerini (`$dsn`, `$user`, `$pass`) kendi ayarlarınıza göre güncelleyin.
4. `stok_foto` klasörünün yazma izinlerini kontrol edin.

## Teknolojiler

- PHP (PDO)
- MySQL
- HTML5 & CSS3 (Flexbox, Grid)
- JavaScript

## Lisans

Bu proje eğitim amaçlıdır.
