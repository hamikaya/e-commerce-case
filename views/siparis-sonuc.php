<?php $pageTitle = "Sipariş"; ?>
<?php include("__header.php"); ?>
<div class="container mb-5">
	<div class="row">
		<div class="col-12 py-5 mb-5">
			<h1 class="text-center">Sipariş</h1>
			<p class="text-center m-0"><?= $siparisiTamamla["message"]; ?></p>
			<div class="text-center mt-5">
				<a href="urunler" class="btn btn-dark">Ürünlere Dön</a>
			</div>
		</div>
	</div>
</div>
<?php include("__footer.php"); ?>