<script src="//code.jquery.com/jquery-2.1.0.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<!-- table - datatable -->
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.1.0/js/responsive.bootstrap.min.js"></script>
<link href="https://cdn.datatables.net/responsive/2.1.0/css/responsive.bootstrap.min.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet">
<!--  end table - datatable -->


<script>

    $(document).ready(function () {
        //$('#example').DataTable();
    });
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

        //$('#example').DataTable();
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
            $.ajax({
                type: "POST",
                url: "editor/generate_table",
                data: {table_name: nama_tabel, db_name: nama_db, tipe: "field"},
                dataType: "JSON",
                success: function (resdata) {
//                    $.each(resdata, function (i) {
//                        var column = resdata[i]['column_name']
//                        var label_name = capitalizeFirstLetter(column.replace("_", " "));
//                        var label = '<label class="col-sm-2 control-label">' + label_name + '</label>';
//                        var textbox = '<input class="form-control" type=text class="input" id=tb_' + column + ' ' +
//                            ' name=tb_' + column + '/>';
//                        var input = '<div class="col-sm-10">' + textbox + "</div/>";
//                        var input_container = document.createElement('div');
//                        input_container.className = "form-group";
//                        $(input_container).append(label);
//                        $(input_container).append(input);
//                        $(container).append(input_container);
//                    });
                    //console.log(resdata);
                    $('#main').html(resdata);
//                    $("#main").load("http://localhost/db_data_editor/index.php/editor/generated_table");

                },
                error: function (result, status, err) {
                    console.log("error", result.responseText);
                    console.log("error", status.responseText);
                    console.log("error", err.Message);
                }
            });
        });

        $("#btnGenerateCode").click(function () {
            $("#isinya").empty();
            var nama_db = document.getElementById('db_name').value;
            var nama_tabel = document.getElementById('table_name').value;
            $.ajax({
                type: "POST",
                url: "editor/generate_table",
                data: {table_name: nama_tabel, db_name: nama_db, tipe: "plain"},
                dataType: "JSON",
                success: function (resdata) {//
                    console.log(resdata);
                    //$('#main').html(resdata);
                   // $('#example').DataTable();
//                    $("#main").load("http://localhost/db_data_editor/index.php/editor/generated_table");
                },
                error: function (result, status, err) {
                    console.log("error", result.responseText);
                    console.log("error", status.responseText);
                    console.log("error", err.Message);
                }
            });
        });

        $("#preview").click(function () {
            $( "#selector" ).toggle( "slow", "linear", function() {
                // Animation complete.
            });
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
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-info " id="selector">
                        <div class="panel-heading"> Database & Table</div>
                        <div class="panel-body">
                            <!--form action="editor/generate_field" method="POST"-->
                            <div class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">
                                        Database
                                    </label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="db_name" name="db_name">
                                            <?php echo $data_table_name; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-2 control-label">
                                        Table
                                    </label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="table_name" name="table_name">
                                            <?php echo $data_table_name; ?>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn pull-right btn-primary" id="btnGenerate">
                                    Generate
                                </button>
                                <button type="submit" class="btn pull-right btn-warning" id="btnGenerateCode">
                                    Download
                                </button>
                            </div>
                            <!--/form-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="form-horizontal" role="form">
                        <div class="panel panel-warning">
                            <div class="panel-body">
                                <div id="main">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1"></div>
            </div>
        </div>
    </div>

</div>
</div>









