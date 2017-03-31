<script src="//code.jquery.com/jquery-2.1.0.min.js"></script>
<!-- table - datatable -->
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" rel="stylesheet">
<!--  end table - datatable -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>
$(document).ready(function () {
$("#datatable").DataTable();
});
</script>
<hr>
<a href="gerai_operator/add" class="btn btn-primary" style="margin:10px">Tambah data</a>
<hr>
<table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
       width="100%">
      <thead>
					<th>Kode operator</th>
					<th>Nama operator</th>
					<th>Keterangan</th>
					<th>Service user</th>
					<th>Service time</th>
					<th>Service action</th>
					<th>Action</th>
      <thead>
      <tbody>
        <?php foreach($table_content as $key => $value){ ?>
          <tr>
            
						<td><?php echo $value["kode_operator"] ?></td>
						<td><?php echo $value["nama_operator"] ?></td>
						<td><?php echo $value["keterangan"] ?></td>
						<td><?php echo $value["service_user"] ?></td>
						<td><?php echo $value["service_time"] ?></td>
						<td><?php echo $value["service_action"] ?></td>
						<td> <a href="gerai_operator/edit?id=" >Edit </a> - <a href="gerai_operator/delete?id=" >Hapus </a></td>
          </tr>
        <?php } ?>
      <tbody>
</table>
