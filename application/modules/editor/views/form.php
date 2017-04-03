<!DOCTYPE html>
<html>

<head>
   <title>CRUD Generator</title>

   <meta name="viewport" content="width=device-width, initial-scale=1">

   <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>theme/flat-admin/css/vendor.css">
   <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>theme/flat-admin/css/flat-admin.css">

   <!-- Theme -->
   <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>theme/flat-admin/css/theme/blue-sky.css">
   <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>theme/flat-admin/css/theme/blue.css">
   <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>theme/flat-admin/css/theme/red.css">
   <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>theme/flat-admin/css/theme/yellow.css">

</head>

<body>
   <div class="app app-default">

       <?php $this->load->view('flat-admin-sidebar.php') ?>

           <script type="text/ng-template" id="sidebar-dropdown.tpl.html">
               <div class="dropdown-background">
                   <div class="bg"></div>
               </div>
               <div class="dropdown-container">
                   {{list}}
               </div>
           </script>
           <div class="app-container">

               <div class="row">
                   <div class="col-md-12">
                       <div class="card">
                           <div class="card-body">
                               <div class="row">
                                   <div class="col-md-4">
                                       <div class="form-group">
                                           <label class="col-md-4 control-label">Database</label>
                                           <div class="col-md-8">
                                               <div class="input-group">
                                                   <select class="select" id="db_name" name="db_name" style="width:100%">
                                                   </select>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="col-md-4">
                                       <div class="form-group">
                                           <label class="col-md-4 control-label">Table</label>
                                           <div class="col-md-8">
                                               <div class="input-group">
                                                   <select class="select" id="table_name" name="table_name" style="width:100%">
                                                   </select>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="col-md-4">
                                       <button type="submit" class="btn btn-success" id="btnGenerate" >Generate</button>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>


               <div class="row">
                   <div class="col-md-12">
                       <div class="card">
                           <div class="card-body">
                               <div class="row">
                                   <div class="col-md-12" id="editor">
                                   </div>
                                   </div>
                                   </div>
                                   </div>
                                   </div>
                                   </div>
           </div>
   </div>
   <script type="text/javascript" src="<?php echo base_url() ?>theme/flat-admin/js/vendor.js"></script>
   <script type="text/javascript" src="<?php echo base_url() ?>theme/flat-admin/js/app.js"></script>
   <script type="text/javascript" src="<?php echo base_url() ?>theme/flat-admin/js/jquery-2.2.4.min.js"></script>


</body>
</html>

<script>
// load database kedalam select box
  $(window).load(function() {
    $.ajax({
        type: "POST",
        url: "editor/fill_database",
        data: {},
        dataType: "JSON",
        success: function (resdata) {
            var options = $("#db_name");
            options.append($("<option />").val("-").text(" Select Database "));
            $.each(resdata, function (i) {
                var nama_db = resdata[i]['Database'];
                options.append($("<option />").val(nama_db).text(nama_db));
            });
        },
        error: function (result, status, err) {
            console.log("error", result.responseText);
            console.log("error", status.responseText);
            console.log("error", err.Message);
        }
    });
});

$(document).ready(function () {
  // aksi ketika memilih database
  // update selectbox table
  $("#db_name").change(function () {
      $("#table_name").empty();
      var nama_db = document.getElementById('db_name').value;
      $.ajax({
          type: "POST",
          url: "editor/select_database",
          data: {db_name: nama_db},
          dataType: "JSON",
          success: function (resdata) {
              var options = $("#table_name");
              $.each(resdata, function (i) {
                  var field_name = 'Tables_in_' + nama_db;
                  var nama_table = resdata[i][field_name];
                  options.append($("<option />").val(nama_table).text(nama_table));
              });
          },
          error: function (result, status, err) {
              console.log("error", result.responseText);
              console.log("error", status.responseText);
              console.log("error", err.Message);
          }
      });

      });

      // aksi ketika memilih table
      // tampilkan editor
      $("#table_name").change(function () {
          $("#isinya").empty();
          var nama_db = $("#db_name").val();
          var nama_tabel = $(this).val();
          $.ajax({
              type: "POST",
              url: "editor/generate_editor",
              data: {table_name: nama_tabel, db_name: nama_db, tipe: "field"},
              dataType: "JSON",
              success: function (resdata) {//
                  $('#editor').html(resdata);
              },
              error: function (result, status, err) {
                  console.log("error", result.responseText);
                  console.log("error", status.responseText);
                  console.log("error", err.Message);
              }
          });
      });

      $("#btnGenerate").click(function () {
        var data = $('form').serialize();
          //generate File
          $.ajax({
              type: "POST",
              url: "editor/generate_from_editor",
              data: {data: data},
              dataType: "JSON",
              success: function (resdata) {//
                  //$('#form').html(resdata);
              },
              error: function (result, status, err) {
                  console.log("error", result.responseText);
                  console.log("error", err.Message);
              }
          });
      });

    });

    // function untuk menampilkan options pada saat dipilih type select atau radio
    function getval(sel)
    {
      var id = $(sel).attr("data-id");
      var attr = '#options_'+id;
      var type = $(sel).val();

      if(type == "select"){
        $(attr).show();
      } else if (type == "radio"){
        $(attr).show();
      }  else {
        $(attr).hide();
      }
    }

    // function untuk menambah dan menghapus row options row
    var number = 0;
    function addOptionsRow(button) {
      number = number +1;
            var id = button.id
            var addPhone = '<div class="input-group"><input type="text" name="type_variable['+id+']['+number+'][value]" placeholder="value" class="form-control" style="float: left; width: 50%;" title="Prefix" ><input type="text" name="type_variable['+id+']['+number+'][name]" placeholder="name" class="form-control" style="float: left; width: 50%;" ><span class="input-group-btn"><button style="padding:9px" class="btn btn-default" type="button" onclick="removePhoneRow(this);" ><span class="glyphicon glyphicon-minus"><\/span><\/button><\/span><\/div>' + " \n";
            $(addPhone).insertBefore($(button));
        }
        function removeOptionsRow(button) {
            $(button).parent().parent().remove();
        }
</script>
