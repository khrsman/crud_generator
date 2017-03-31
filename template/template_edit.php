<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Edit Data</title>
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
            url: "#url_edit_ajax#",
            data: {data: data},
            //dataType: "JSON",
            success: function (resdata) {
              alert("Berhasil diupdate");
              window.location.href = '#controller#';
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
                        #form_input#
                    </form>
                    <button type="button" id="btnSave" class="btn btn-primary"  style="float: right">Edit</button>
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
