<?php $pageTitle = "Ürünler"; ?>
<?php include("__header.php"); ?>
<div class="container mb-5">
	<div class="row">
		<div class="col-12 py-5 mb-5">
			<h1 class="text-center">Ürünler</h1>
			<p class="text-center m-0">Bu sayfa aracılığıyla ürünlere göz atabilirsiniz.</p>
			<div class="text-center mt-5">
				<a href="siparislerim" class="btn btn-dark">Siparişlerim</a>
				<a href="sepetim" class="btn btn-dark">Sepete Git (<span id="sepetInfo"><?= $sepetUrunSayi; ?></span> ürün)</a>
			</div>
		</div>
	</div>
	<div class="row mx-auto m-0">
		<?php foreach ($urunListesi as $urunListesi_tek) { ?>
		<div class="col-md-4 col-lg-3 col-6 p-1">
			<div class="p-2" style="border: 1px solid #dadada; background-color: #f3f3f3;">
				<div class="text-center">
					<img src="https://www.seekpng.com/png/small/123-1236781_blank-book-cover-png-paper-product.png" style="max-height: 250px;" class="img-fluid">
				</div>
				<div class="text-center border-top my-3 py-3">
					<div style="min-height: 75px;">
						<h4><?= $urunListesi_tek["title"]; ?></h4>
					</div>
					<span><?= $urunListesi_tek["author"]; ?> / <?= $urunListesi_tek["category_name"]; ?></span>
				</div>
				<div class="row m-0">
					<div class="col-6 text-center">
						<h3 class="text-danger font-weight-bold text-center"><?= number_format($urunListesi_tek["list_price"], 2, ',', '.'); ?> TL</h3>
					</div>
					<div class="col-6 text-center">
						<?php if ($urunListesi_tek["stock_quantity"] > 0) { ?>
						<span class="btn btn-secondary" onclick="sepetIslem(<?= $urunListesi_tek["id"]; ?>, 0)">-</span>
						<span class="btn btn-default" id="piece<?= $urunListesi_tek["id"] ?>"><?= ($_SESSION["sepet"][$urunListesi_tek["id"]][1] > 0) ? $_SESSION["sepet"][$urunListesi_tek["id"]][1] : 0; ?></span>
						<span class="btn btn-secondary" onclick="sepetIslem(<?= $urunListesi_tek["id"]; ?>, 1)">+</span>
						<?php } else { ?>
						<span class="text-center text-danger">STOKTA YOKTUR</span>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<?php include("__footer.php"); ?>