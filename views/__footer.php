    <script>
        function sepetIslem(productId, process) {
            $.ajax({
                type: "POST",
                url: "urunu-sepet-islem",
                data: "id=" + productId + "&process=" + process,
                success: function(response){

                    if (response == 1 && process == 1) {
                        $("#piece" + productId).html(parseInt($("#piece" + productId).html()) + 1);
                    }
                    else if (response == 1 && process == 0 && parseInt($("#piece" + productId).html()) > 0) {
                        $("#piece" + productId).html(parseInt($("#piece" + productId).html()) - 1);
                    }
                    else if (response == 5) {
                        alert("Ürün stoğu yetersiz olduğu için sepete eklenemedi.");
                    }

                    <?php if (isset($adetGuncellemeSonrasiSayfayiYenile)) { ?>
                    if (response == 1) {
                        location.reload();
                    }
                    <?php } ?>
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>