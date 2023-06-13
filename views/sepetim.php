<?php $pageTitle = "Ürünler"; ?>
<?php include("__header.php"); ?>
<div class="container mb-5">
	<div class="row">
		<div class="col-12 py-5 mb-5">
			<h1 class="text-center">Sepetim</h1>
			<p class="text-center m-0">Sepetinizde bulunan ürün sayısı: <span class="sepetInfo"><?= $sepetUrunSayi; ?></span></p>
			<div class="text-center mt-5">
				<a href="urunler" class="btn btn-dark">Ürünlere Dön</a>
				<a href="siparislerim" class="btn btn-dark">Siparişlerim</a>
			</div>
		</div>
	</div>
	<?php if ($sepetUrunSayi > 0) { ?>
	<div class="row mx-auto m-0 w-50">
		<div class="col-md-8">
			<?php
				foreach ($sepetUrunlerListe as $sepetUrunlerListe_tek) {
			?>
			<div class="p-3 border">
				<div>
					<h5><?= $sepetUrunlerListe_tek["title"]; ?></h5>
					<p class="m-0"><?= $sepetUrunlerListe_tek["author"]; ?> / <?= $sepetUrunlerListe_tek["category"]; ?> / Adet: <?= $sepetUrunlerListe_tek["piece"]; ?></p>
					<p class="m-0"><?= number_format($sepetUrunlerListe_tek["listPrice"], 2, ',', '.'); ?> <small>Birim Fiyat: <?= number_format($sepetUrunlerListe_tek["listPrice"] / $sepetUrunlerListe_tek["piece"], 2, ',', '.'); ?> TL</small></p>
				</div>
				<div>
					<span class="btn btn-secondary btn-sm" onclick="sepetIslem(<?= $sepetUrunlerListe_tek["productId"]; ?>, 0)">-</span>
					<span class="btn btn-default btn-sm" id="piece<?= $sepetUrunlerListe_tek["productId"] ?>"><?= ($_SESSION["sepet"][$sepetUrunlerListe_tek["productId"]][1] > 0) ? $_SESSION["sepet"][$sepetUrunlerListe_tek["productId"]][1] : 0; ?></span>
					<span class="btn btn-secondary btn-sm" onclick="sepetIslem(<?= $sepetUrunlerListe_tek["productId"]; ?>, 1)">+</span>
				</div>
			</div>
			<?php } ?>
		</div>
		<div class="col-md-4">
			<p class="m-0">Ürünlerin Tutarı: <?= number_format($sepetTutarDetay["sepetTutar"], 2, ',','.'); ?> TL</p>
			<?php if (isset($sepetTutarDetay["yeniSepetTutar"])) { ?>
			<p class="m-0 text-success font-weight-bold">İndirimli Tutar: <?= number_format($sepetTutarDetay["yeniSepetTutar"], 2, ',','.'); ?> TL</p>
			<p class="m-0 mt-1 mb-3 py-1 px-2 alert alert-success" style="font-size: 12px;">İndirim Açıklaması: <?= $sepetTutarDetay["kampanyaAciklama"]; ?></p>
			<?php } ?>
			<p class="m-0">Kargo Ücreti: <?= number_format($sepetTutarDetay["kargoTutar"], 2, ',', '.'); ?> TL <?= ($sepetTutarDetay["kargoTutar"] == 0) ? '(200 TL ve üzeri bedava)' : null; ?></p>
			<?php
				$mevcutTutar = (isset($sepetTutarDetay["yeniSepetTutar"])) ? number_format($sepetTutarDetay["yeniSepetTutar"], 2, ',','.'): $sepetTutarDetay["sepetTutar"];
				if ($sepetTutarDetay["kargoTutar"] > 0 && $mevcutTutar < 200) {
			?>
			<p class="m-0 mt-1 mb-3 py-1 px-2 alert alert-warning" style="font-size: 12px;">Sepetinize <?= number_format(200 - $mevcutTutar, 2, ',', '.'); ?> TL tutarında ürün eklerseniz kargo ücretsiz olur.</p>
			<?php } ?>
			<hr>
			Toplam Tutar: <?= (isset($sepetTutarDetay["yeniSepetTutar"])) ? number_format($sepetTutarDetay["yeniSepetTutar"] + $sepetTutarDetay["kargoTutar"], 2, ',', '.') : number_format($sepetTutarDetay["sepetTutar"] + $sepetTutarDetay["kargoTutar"], 2, ',', '.'); ?> TL 
			<a href="siparisi-tamamla" class="btn btn-success btn-sm w-100 mt-2">Siparişi Tamamla</a>
		</div>
	</div>
	<?php } else { ?>
		<p class="text-center">Sepetinizde ürün bulunmamaktadır.</p>
	<?php } ?>
</div>
<?php include("__footer.php"); ?>