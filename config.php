<?php
	$currentFileName = "config.php";
    if (substr($_SERVER["REQUEST_URI"], -strlen($currentFileName)) == $currentFileName) {
        echo 404;
        exit();
    }
    
	session_start();
	ob_start();

	// veritabanına bağlan
	$baglan = new mysqli('localhost', 'DB_USERNAME', 'DB_PASSWORD');
	
	// hata varsa uyar ve çalışmayı durdur
	if($baglan->connect_errno) {
	    echo 'Mysqli bağlantı hatası: ' . $baglan->connect_errno;
	    exit;
	}
	// deneme isimli veritabanını seç

	$baglan->select_db('DB_NAME');
	$baglan->set_charset("utf8");
	date_default_timezone_set('Europe/Istanbul');
?>