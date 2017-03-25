<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Tambah Data</title>
    <!------------------- javascript semua disini ------------------------------>
    <script
  			  src="https://code.jquery.com/jquery-3.1.1.min.js"
  			  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  			  crossorigin="anonymous"></script>

  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script>
  $(document).ready(function () {
      $("#btnSave").click(function () {
        var data = $('form').serialize();
        $.ajax({
            type: "POST",
            url: "detail_transaksi/add",
            data: {data: data},
            //dataType: "JSON",
            success: function (resdata) {
              alert("Berhasil ditambahkan");
              window.location.href = 'detail_transaksi';
            },
            error: function (jqXHR, exception) {
                alert("Terjadi kesalahan");
            }
        });
        });
        });
  </script>
  <!------------------- javascript end ------------------------------>
  </script>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
          <div class="row" style="padding-top: 20px">
              <div class="col-md-6" id="form_panel">
                  <div id="form">
                    <form class="form-horizontal" role="form">
                        
												<div class="input-group">
                          <span class="input-group-addon" id="basic-addon1">No transaksi</span>
                          <input type="text" class="form-control" placeholder="" name="no_transaksi" id="no_transaksi" aria-describedby="basic-addon1">
                        </div>
												<div class="input-group">
                          <span class="input-group-addon" id="basic-addon1">Id produk</span>
                          <input type="text" class="form-control" placeholder="" name="id_produk" id="id_produk" aria-describedby="basic-addon1">
                        </div>
												<div class="input-group">
                          <span class="input-group-addon" id="basic-addon1">Service time produk</span>
                          <input type="text" class="form-control" placeholder="" name="service_time_produk" id="service_time_produk" aria-describedby="basic-addon1">
                        </div>
												<div class="input-group">
                          <span class="input-group-addon" id="basic-addon1">Harga</span>
                          <input type="text" class="form-control" placeholder="" name="harga" id="harga" aria-describedby="basic-addon1">
                        </div>
												<div class="input-group">
                          <span class="input-group-addon" id="basic-addon1">Jumlah</span>
                          <input type="text" class="form-control" placeholder="" name="jumlah" id="jumlah" aria-describedby="basic-addon1">
                        </div>
												<div class="input-group">
                          <span class="input-group-addon" id="basic-addon1">Status terkirim</span>
                          <input type="text" class="form-control" placeholder="" name="status_terkirim" id="status_terkirim" aria-describedby="basic-addon1">
                        </div>
                    </form>
                    <button type="button" id="btnSave" class="btn btn-primary"  style="float: right">Simpan</button>
                  </div>
              </div>
          </div>
        </div>
    </div>
</div>
</body>
</html>

<style>
.input-group-addon {
   min-width:150px;// if you want width please write here //
   text-align:left;
}
.input-group{
 padding: 5px;
}
</style>';
