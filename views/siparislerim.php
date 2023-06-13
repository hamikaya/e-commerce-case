<?php $pageTitle = "Sepetim"; ?>
<?php include("__header.php"); ?>
<div class="container mb-5">
	<div class="row">
		<div class="col-12 py-5 mb-5">
			<h1 class="text-center">Siparişlerim</h1>
			<div class="text-center mt-5">
				<a href="urunler" class="btn btn-dark">Ürünlere Dön</a>
			</div>
		</div>
	</div>
	<?php if (count($siparisListesi) > 0) { ?>
	<div class="row mx-auto m-0 w-75">
		<?php foreach ($siparisListesi as $siparisListesi_tek) { ?>
		<div class="col-md-4 p-1">
			<div class="border p-3">
				<p class="m-0"><b>Sipariş Numarası:</b> #<?= $siparisListesi_tek["order_no"]; ?></p>
				<p class="m-0"><b>Ürünler:</b> <?= $urunListesi[$siparisListesi_tek["order_no"]]; ?></p>
				<p class="m-0"><b>Toplam Tutar:</b> <?= number_format($siparisListesi_tek["list_price"], 2, ',', '.'); ?> TL</p>
				<p class="m-0"><b>İndirimli Tutar:</b> <?= number_format($siparisListesi_tek["campaign_price"], 2, ',', '.'); ?> TL</p>
				<p class="m-0"><b>Kargo Ücreti:</b> <?= number_format($siparisListesi_tek["cargo_price"], 2, ',', '.'); ?> TL</p>
				<p class="m-0"><b>Sipariş Tarihi:</b> <?= date("d.m.Y H:i", strtotime($siparisListesi_tek["created_at"])); ?></p>
			</div>
		</div>
		<?php } ?>
	</div>
	<?php } else { ?>
		<p class="text-center">Siparişiniz bulunmamaktadır.</p>
	<?php } ?>
</div>
<?php include("__footer.php"); ?>