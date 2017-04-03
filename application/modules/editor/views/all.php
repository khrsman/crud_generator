<script src="//code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- table - datatable -->
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
<script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.0/js/responsive.bootstrap.min.js"></script>
<link href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet">

<!--  end table - datatable -->

<link href="<?php echo base_url() ?>css/bootstrap.min.css" rel="stylesheet">
<script src="<?php echo base_url() ?>js/bootstrap.min.js"></script>
<script>
    window.onload = isi_database();
    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

// function untuk tambah option pada select / radio
var number = 0;

function get_select(){
  var uid = $(this).attr('data-id');
  console.log("kaharisman");
  console.log(uid);
}

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

function addPhoneRow(button) {
  number = number +1;
        var id = button.id
        var addPhone = '<div class="input-group"><input type="text" name="type_variable['+id+']['+number+'][value]" placeholder="value" class="form-control" style="float: left; width: 50%;" title="Prefix" ><input type="text" name="type_variable['+id+']['+number+'][name]" placeholder="name" class="form-control" style="float: left; width: 50%;" ><span class="input-group-btn"><button style="padding:9px" class="btn btn-default" type="button" onclick="removePhoneRow(this);" ><span class="glyphicon glyphicon-minus"><\/span><\/button><\/span><\/div>' + " \n";
        $(addPhone).insertBefore($(button));
    }
    function removePhoneRow(button) {
        $(button).parent().parent().remove();
    }

    function isi_database() {
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
    }

    $(document).ready(function () {
        var kolom = 0;
        // CREATE A "DIV" ELEMENT AND DESIGN IT USING jQuery ".css()" CLASS.
        var container = document.createElement('div');
        //container.className = "form-group";
        container.id = "isinya";
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

        $("#btnGenerate").click(function () {
            $("#isinya").empty();
            var nama_db = document.getElementById('db_name').value;
            var nama_tabel = document.getElementById('table_name').value;

            //generate editor view
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
            //generate code
            $('#editor_panel').show();
            $('#list_panel').hide();
            $('#form_panel').hide();
            $('#code_panel').hide();
            $('#editor_view').addClass('active');
        });

        $("#btnGenerateFile").click(function () {
            $("#isinya").empty();
            var nama_db = document.getElementById('db_name').value;
            var nama_tabel = document.getElementById('table_name').value;
            //generate File
            $.ajax({
                type: "POST",
                url: "editor/generate_file",
                data: {table_name: nama_tabel, db_name: nama_db, tipe: "field"},
                dataType: "JSON",
                success: function (resdata) {//
                    $('#form').html(resdata);
                },
                error: function (result, status, err) {
                    console.log("error", result.responseText);
                    console.log("error", err.Message);
                }
            });
        });
        $("#btnGenerateEditor").click(function () {
          var data = $('form').serialize();
            //generate File
            $.ajax({
                type: "POST",
                url: "editor/generate_from_editor",
                data: {data: data},
                dataType: "JSON",
                success: function (resdata) {//
                    $('#form').html(resdata);
                },
                error: function (result, status, err) {
                    console.log("error", result.responseText);
                    console.log("error", err.Message);
                }
            });
        });

        $("#preview").click(function () {
            $("#selector").toggle("slow", "linear", function () {
                // Animation complete.
            });
        });

    });
</script>


<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row" style="background-color: #daffd3">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Database</th>
                        <th>Table</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <select class="form-control" id="db_name" name="db_name">

                            </select>
                        </td>
                        <td>
                            <select class="form-control" id="table_name" name="table_name">
                                
                            </select>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary" id="btnGenerate">
                                Preview
                            </button>
                            <button type="submit" class="btn btn-primary" id="btnGenerateFile">
                                Generate File
                            </button>
                            <button type="submit" class="btn btn-primary" id="btnGenerateEditor">
                                Generate From editor
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row" style="padding-top: 20px">
      <div class="col-md-12" id="editor_panel">
        <div class="col-md-1"></div>
                  <div class="col-md-10" id="editor">
                  </div>
      <div class="col-md-1"></div>
      </div>
    </div>

</div>
</div>

<style>
.panel {
    border: 0;
    padding: 0;
}
</style>

<footer>
    <div class="navbar navbar-inverse navbar-fixed-bottom">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#footer-body">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <ul class="footer-bar-btns visible-xs">
                    <li><a href="#" class="btn" title="History"><i class="fa fa-2x fa-clock-o blue-text"></i></a></li>
                    <li><a href="#" class="btn" title="Favourites"><i class="fa fa-2x fa-star yellow-text"></i></a></li>
                    <li><a href="#" class="btn" title="Subscriptions"><i class="fa fa-2x fa-rss-square orange-text"></i></a></li>
                </ul>
            </div>
            <div class="navbar-collapse collapse" id="footer-body">
                <ul class="nav navbar-nav">
                    <li><a href="#">Browse Our Library</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">Our Partners</a></li>
                    <li><a href="#">User Review</a></li>
                    <li><a href="#">Terms &amp; Conditions</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
