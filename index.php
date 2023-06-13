<?php
    include("config.php");
    // İstek yönlendirme işlemlerini yapmak için bir router sınıfı oluşturun
    class Router {
        private $routes = [];

        public function addRoute($method, $path, $callback) {
            $this->routes[] = [
                'method' => $method,
                'path' => $path,
                'callback' => $callback
            ];
        }

        public function handleRequest() {
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            $requestUri = $_SERVER['REQUEST_URI'];

            foreach ($this->routes as $route) {
                if ($route['method'] === $requestMethod && $route['path'] === $requestUri) {
                    $callback = $route['callback'];
                    $callback();
                    return;
                }
            }

            //Eşleşen bir route bulunamazsa 404 hatası döndürün
            //http_response_code(404);
            header("Location:urunler");
            exit();
        }
    }

    //Router
    $router = new Router();

    //Ürünler sayfası için endpoint
    $router->addRoute('GET', 'urunler', function() {

        $urunListesi = 'https://'.$_SERVER["HTTP_HOST"].'urun-listesi-al';
        $urunListesi = json_decode(file_get_contents($urunListesi), true);
        $sepetUrunSayi = count(array_keys($_SESSION["sepet"]));

        include("views/urunler.php");
    });

    //Sepetim sayfası için endpoint
    $router->addRoute('GET', 'sepetim', function() {

        $sepetUrunSayi = count(array_keys($_SESSION["sepet"]));
        $sepetUrunlerListe = sepetUrunlerDetay();
        $sepetTutarDetay = sepetTutarHesapla();
        $adetGuncellemeSonrasiSayfayiYenile = 1;

        include("views/sepetim.php");
    });

    //Siparişlerim sayfası için endpoint
    $router->addRoute('GET', 'siparislerim', function() {

        $siparisListesi = 'https://'.$_SERVER["HTTP_HOST"].'siparis-listesi-al';
        $siparisListesi = json_decode(file_get_contents($siparisListesi), true);

        $urunListesi = null;
        foreach ($siparisListesi as $siparisListesi_tek) {
            foreach ($siparisListesi_tek["urunBilgi"] as $siparisListesi_urunBilgi_tek) {
                $urunListesi[$siparisListesi_tek["order_no"]] .= ','.$siparisListesi_urunBilgi_tek["title"];
            }
            $urunListesi[$siparisListesi_tek["order_no"]] = trim($urunListesi[$siparisListesi_tek["order_no"]], ',');
        }

        include("views/siparislerim.php");
    });

    //Ürün listesini çekmek için endpoint
    $router->addRoute('GET', 'urun-listesi-al', function() {
        global $baglan;

        $kategoriListesi = array();
        $kategoriListesiCek = $baglan->query("SELECT * FROM categories ORDER BY id ASC");

        while ($kategoriListesiCek_tek = $kategoriListesiCek->fetch_assoc()) {
            $kategoriListesi[$kategoriListesiCek_tek["id"]] = $kategoriListesiCek_tek["title"];
        }

        $urunListesi = array();
        $urunListesiCek = $baglan->query("SELECT * FROM products ORDER BY id DESC");

        while ($urunListesiCek_tek = $urunListesiCek->fetch_assoc()) {
            $urunListesiCek_tek["category_name"] = $kategoriListesi[$urunListesiCek_tek["category_id"]];
            array_push($urunListesi, $urunListesiCek_tek);
        }
        echo json_encode($urunListesi);
    });

    //Kampanya listesini çekmek için endpoint
    $router->addRoute('GET', 'kampanya-listesi-al', function() {
        global $baglan;

        $kampanyaListesi = array();
        $kampanyaListesiCek = $baglan->query("SELECT * FROM campaigns ORDER BY id DESC");

        while ($kampanyaListesiCek_tek = $kampanyaListesiCek->fetch_assoc()) {
            array_push($kampanyaListesi, $kampanyaListesiCek_tek);
        }
        echo json_encode($kampanyaListesi);
    });

    //Sipariş listesini çekmek için endpoint
    $router->addRoute('GET', 'siparis-listesi-al', function() {
        global $baglan;

        $siparisListesi = array();
        $siparisListesiCek = $baglan->query("SELECT * FROM orders ORDER BY id DESC");

        while ($siparisListesiCek_tek = $siparisListesiCek->fetch_assoc()) {
            $siparisListesiCek_tek["urunBilgi"] = array();

            $siparisListesiCek_tek_urunIdListe = explode(",", $siparisListesiCek_tek["product_id_list"]);
            foreach ($siparisListesiCek_tek_urunIdListe as $siparisListesiCek_tek_urunIdListe_tek) {
                $urunBilgiAl = $baglan->query("SELECT * FROM products WHERE id = '$siparisListesiCek_tek_urunIdListe_tek'")->fetch_assoc();

                $urunKategoriBilgiAl = $baglan->query("SELECT * FROM products WHERE id = '{$urunBilgiAl["category_id"]}'")->fetch_assoc();
                $urunBilgiAl["category_name"] = $urunKategoriBilgiAl["title"];

                $siparisListesiCek_tek["urunBilgi"][$siparisListesiCek_tek_urunIdListe_tek] = $urunBilgiAl;
            }

            array_push($siparisListesi, $siparisListesiCek_tek);
        }
        echo json_encode($siparisListesi);
    });

    //Sipariş oluşturma
    $router->addRoute('GET', 'siparisi-tamamla', function() {
        global $baglan;

        $sepetUrunlerDetay = sepetUrunlerDetay();
        $urunIdListe = null;

        //Stok yetersiz olan ürünleri sepetten çıkaralım
        foreach ($sepetUrunlerDetay as $sepetUrunlerDetay_tek) {
            if ($baglan->query("SELECT * FROM products WHERE id = '{$sepetUrunlerDetay_tek["productId"]}' AND stock_quantity >= '{$sepetUrunlerDetay_tek["piece"]}'")->num_rows == 1) {
                $urunIdListe .= ','.$sepetUrunlerDetay_tek["productId"];
            }
            else {
                sepetGuncelle($sepetUrunlerDetay_tek["productId"], 0);
            }
        }
        $urunIdListe = trim($urunIdListe, ',');

        $sepetUrunlerDetay = sepetUrunlerDetay();
        $sepetTutarHesapla = sepetTutarHesapla();
        $siparisTarih = date("Y-m-d H:i:s");
        $siparisNo = strtotime(date("Y-m-d H:i:s")).rand(101,999);
        $siparisOlustur = $baglan->query("INSERT INTO orders (order_no, product_id_list, campaign_id, list_price, campaign_price, cargo_price, created_at) VALUES ('$siparisNo','$urunIdListe','{$sepetTutarHesapla["kampanyaId"]}','{$sepetTutarHesapla["sepetTutar"]}','{$sepetTutarHesapla["yeniSepetTutar"]}','{$sepetTutarHesapla["kargoTutar"]}','$siparisTarih')");

        if ($siparisOlustur) {
            //echo json_encode(['success' => true, 'message' => 'Sipariş başarıyla oluşturuldu']);
            $siparisiTamamla['message'] = "Sipariş başarıyla oluşturuldu";
            unset($_SESSION["sepet"]);
        }
        else {
            //echo json_encode(['success' => false, 'message' => 'Sipariş oluşturulamadı']);
            $siparisiTamamla['message'] = "Sipariş oluşturulamadı";
        }

        include("views/siparis-sonuc.php");
    });

    //
    $router->addRoute('POST', 'urunu-sepet-islem', function() {
        /*
            Sepet sonucu çıktı kodları ve anlamları
            1 => Ürün sepete eklendi
            2 => Ürün sepete eklenemedi
            3 => Ürün sepetten çıkarıldı
            4 => Ürün sepetten çıkarılamadı
            5 => Ürün stokta yok (Stoğu biten ürünlerin arayüz kısmında sepete ekleme butonu görünmüyor fakat yine de tekrar güncel stok kontrolü yapılıyor)
        */

        if (isset($_POST["id"]) && !empty(addslashes(strip_tags($_POST["id"]))) && is_numeric(addslashes(strip_tags($_POST["id"])))) {
            $productId = addslashes(strip_tags($_POST["id"]));
            $process = addslashes(strip_tags($_POST["process"]));

            echo sepetGuncelle($productId, $process);
        }
    });

    function sepetGuncelle($productId, $process) {
        global $baglan;

        if (isset($_SESSION["sepet"]) && is_array($_SESSION["sepet"])) {
            // Sepet zaten var, ürünü çıkarın (eğer varsa)
            foreach ($_SESSION["sepet"] as $key) {
                if ($key[0] == $productId) {
                    if ($process == 1) { //Sepete adet ekleme işlemi
                        if ($baglan->query("SELECT * FROM products WHERE id = '$productId' AND stock_quantity > $key[1]")->num_rows == 1) {
                            $key[1]++;
                            $sonuc = 1;
                        }
                        else {
                            $sonuc = 5;
                        }
                    }
                    else if ($process == 0) { //Sepetten adet eksiltme işlemi
                        $key[1]--;
                        $sonuc = 1;
                    }

                    $_SESSION["sepet"][$productId] = [$productId, $key[1]];

                    if ($key[1] <= 0) {
                        //Adeti sıfır olan ürünü sepetten kaldırma işlemi
                        unset($_SESSION["sepet"][$productId]);
                    }

                    return $sonuc;
                }
            }
        } else {
            $_SESSION["sepet"] = array(); // Sepet dizi olarak tanımlanır
        }

        if ($process == 1 && $baglan->query("SELECT * FROM products WHERE id = '$productId' AND stock_quantity > 0")->num_rows == 1) {
            //array_push($_SESSION["sepet"], $productId);
            $_SESSION["sepet"][$productId] = [$productId, 1];
            return 1;
        }
        else if ($process == 1) {
            return 5;
        }
    }

    function sepetUrunlerDetay() {
        global $baglan;

        $sepetUrunListe = array_keys($_SESSION["sepet"]);
        $sepetUrunListeDetay = array();

        $urunListesiCek = $baglan->query("SELECT * FROM products ORDER BY id DESC");
        $urunListesi = array();

        while ($urunListesiCek_tek = $urunListesiCek->fetch_assoc()) {
            $urunListesi[$urunListesiCek_tek["id"]] = $urunListesiCek_tek;
        }

        $kategoriListesiCek = $baglan->query("SELECT * FROM categories ORDER BY id DESC");
        $kategoriListesi = array();

        while ($kategoriListesiCek_tek = $kategoriListesiCek->fetch_assoc()) {
            $kategoriListesi[$kategoriListesiCek_tek["id"]] = $kategoriListesiCek_tek["title"];
        }

        foreach ($_SESSION["sepet"] as $key => $value) {
            array_push($sepetUrunListeDetay, ['productId' => $key, 'title' => $urunListesi[$key]["title"], 'categoryId' => $urunListesi[$key]["category_id"], 'category' => $kategoriListesi[$urunListesi[$key]["category_id"]], 'author' => $urunListesi[$key]["author"], 'listPrice' => $urunListesi[$key]["list_price"] * $_SESSION["sepet"][$key][1], 'piece' => $_SESSION["sepet"][$key][1]]);
        }

        return $sepetUrunListeDetay;
    }

    function sepetTutarHesapla() {
        global $baglan;

        $sepetTutar = 0;
        $uygulananKampanyaId = 0;
        $sepetListe = sepetUrunlerDetay();
        foreach ($sepetListe as $sepetListe_tek) {
            $sepetTutar += $sepetListe_tek["listPrice"];
        }

        $kampanyalar = $baglan->query("SELECT * FROM campaigns WHERE durum = '1'");

        $kampanyaListesi = 'https://'.$_SERVER["HTTP_HOST"].'kampanya-listesi-al';
        $kampanyaListesi = json_decode(file_get_contents($kampanyaListesi), true);

        while ($kampanya = $kampanyalar->fetch_assoc()) {
            if ($kampanya["title"] == "sepet_yuzde_indirim") {
                if ($sepetTutar >= 100) {
                    $yeniSepetTutar = $sepetTutar - ($sepetTutar * $kampanya["yuzde"] / 100);
                    $uygulananKampanyaId = $kampanya["id"];
                    $uygulananKampanyaAciklama = $kampanya["description"];
                }
            }
            else if ($kampanya["title"] == "yazar_kategori_indirim") {
                $yeniSepetTutar = (isset($yeniSepetTutar)) ? $yeniSepetTutar : $sepetTutar;
                $eslesenUrun = 0;
                $kampanyaLimit = 1;
                $eslesenUrunFiyatlar = array();
                foreach ($sepetListe as $sepetListe_tek) {
                    if ($sepetListe_tek["author"] == $kampanya["author"] && $sepetListe_tek["categoryId"] == $kampanya["category_id"]) {
                        $eslesenUrunAdet += 1 * $sepetListe_tek["piece"];

                        array_push($eslesenUrunFiyatlar, $sepetListe_tek["listPrice"] / $sepetListe_tek["piece"]); //Kampanya ile eşleşen ürünlerin farklı fiyata sahip olma ihtimaline karşın en yüksek fiyatlı ürünü sepet tutarından düşebilmek için kampanya ile eşleşen ürünlerin liste fiyatını bir diziye aktarıyoruz (Sabahattin Ali'nin birden fazla roman kitabı var ve fiyatları farklı)
                    }
                }

                if ($eslesenUrunAdet >= $kampanya["min_adet"]) {
                    if ($yeniSepetTutar > $sepetTutar - max($eslesenUrunFiyatlar)) {
                        $yeniSepetTutar = $sepetTutar - max($eslesenUrunFiyatlar);
                        $uygulananKampanyaId = $kampanya["id"];
                        $uygulananKampanyaAciklama = $kampanya["description"];
                    }
                }
            }
        }

        if (isset($yeniSepetTutar) && $yeniSepetTutar != $sepetTutar) {
            $kargoTutar = ($yeniSepetTutar < 200) ? 75 : 0;
            return ['sepetTutar' => $sepetTutar, 'yeniSepetTutar' => $yeniSepetTutar, 'kampanyaId' => $uygulananKampanyaId, 'kampanyaAciklama' => $uygulananKampanyaAciklama, 'kargoTutar' => $kargoTutar];
        }
        else {
            $kargoTutar = ($sepetTutar < 200) ? 75 : 0;
            return ['sepetTutar' => $sepetTutar, 'kargoTutar' => $kargoTutar];
        }
    }

    $router->handleRequest();
?>