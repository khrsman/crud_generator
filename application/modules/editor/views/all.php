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

<script>
    window.onload = isi_database();

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
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

            //generate form add
            $.ajax({
                type: "POST",
                url: "editor/generate_view_add",
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

            //generate list view
            $.ajax({
                type: "POST",
                url: "editor/generate_view_list",
                data: {table_name: nama_tabel, db_name: nama_db, tipe: "field"},
                dataType: "JSON",
                success: function (resdata) {//
                    $('#list').html(resdata);
                },
                error: function (result, status, err) {
                    console.log("error", result.responseText);
                    console.log("error", status.responseText);
                    console.log("error", err.Message);
                }
            });
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
            $('#list_panel').show();
            $('#form_panel').hide();
            $('#code_panel').hide();
              $("#list_view").addClass('active');
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

        $("#preview").click(function () {
            $("#selector").toggle("slow", "linear", function () {
                // Animation complete.
            });
        });
        var selector = '.nav li';
        $(selector).on('click', function () {
            $(selector).removeClass('active');
            $(this).addClass('active');
            var data = $(this).attr('id');
            if (data == 'list_view') {
                $('#list_panel').show();
                $('#form_panel').hide();
                $('#editor_panel').hide();
            } else if (data == 'form_view')
            {
                $('#list_panel').hide();
                $('#form_panel').show();
                $('#editor_panel').hide();
            }
            else if  (data == 'editor_view')
            {
                $('#list_panel').hide();
                $('#form_panel').hide();
                $('#editor_panel').show();
            }

        });


    });
</script>
<link href="<?php echo base_url() ?>css/bootstrap.min.css" rel="stylesheet">
<script src="<?php echo base_url() ?>js/bootstrap.min.js"></script>

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">FormCreator</a>
        </div>
        <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#" id="preview">Preview</a></li>
            <li><a href="#">Page 2</a></li>
        </ul>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row" style="background-color: #ffff99">
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
                                <?php echo $data_table_name; ?>
                            </select>
                        </td>
                        <td>
                            <select class="form-control" id="table_name" name="table_name">
                                <?php echo $data_table_name; ?>
                            </select>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary" id="btnGenerate">
                                Preview
                            </button>
                            <button type="submit" class="btn btn-primary" id="btnGenerateFile">
                                Generate File
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <ul class="nav nav-tabs nav-justified" style="background-color: #ffff99">
            <li id="list_view"><a href="#">List</a></li>
            <li id="form_view"><a href="#">Form</a></li>
            <li id="editor_view"><a href="#">Editor</a></li>
        </ul>
    </div>
    <div class="row" style="padding-top: 20px">
        <div class="col-md-12" id="list_panel">
                    <div id="list">
                    </div>
        </div>
        <div class="col-md-6" id="form_panel">
                    <div id="form">
                    </div>
        </div>
        <div class="col-md-6" id="editor_panel">
                    <div id="editor">
                    </div>
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
