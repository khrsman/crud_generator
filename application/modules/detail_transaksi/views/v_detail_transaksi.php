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
<a href="add" class="btn btn-primary" style="margin:10px">Tambah data</a>
<hr>
<table id="datatable" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0"
       width="100%">
      <thead>
					<th>No transaksi</th>
					<th>Id produk</th>
					<th>Service time produk</th>
					<th>Harga</th>
					<th>Jumlah</th>
					<th>Status terkirim</th>
					<th>Action</th>
      <thead>
      <tbody>
				<?php foreach($table_content as $key => $value){ ?>
					<tr>
						<td><?php echo $value["no_transaksi"] ?></td>
						<td><?php echo $value["id_produk"] ?></td>
						<td><?php echo $value["service_time_produk"] ?></td>
						<td><?php echo $value["harga"] ?></td>
						<td><?php echo $value["jumlah"] ?></td>
						<td><?php echo $value["status_terkirim"] ?></td>
						<td>edit - hapus</td>
					</tr>
				<?php } ?>
      <tbody>
</table>
