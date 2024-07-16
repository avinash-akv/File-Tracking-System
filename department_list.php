<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary " href="./index.php?page=new_department"><i class="fa fa-plus"></i> Add New</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Department Id</th>
						<th>Name</th>
						<th>Contact</th>
						<th>Email</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT * FROM departments WHERE active = 1 order by id asc ");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td class=""><b><?php echo $row['id'] ?></b></td>
						<td><b><?php echo ucwords($row['name']) ?></b></td>
						<td><b><?php echo ucwords($row['contact'])?></b></td>
						<td><b><?php echo ucwords($row['email']) ?></b></td>
						<td class="text-center">
		                    <div class="btn-group">
								<a href="index.php?page=file_list_viewDept&id=<?php echo $row['id'] ?>" class="btn btn-info btn-flat view_file" data-toggle="tooltip" title="Show Associated Files">
									<i class="fa-solid fa-file"></i>
								</a>
		                        <a href="index.php?page=edit_department&id=<?php echo $row['id'] ?>" class="btn btn-primary btn-flat " data-toggle="tooltip" title="Edit">
		                          <i class="fas fa-edit"></i>
		                        </a>
		                        <button type="button" class="btn btn-danger btn-flat delete_branch" data-id="<?php echo $row['id'] ?>" data-toggle="tooltip" title="Delete">
		                          <i class="fas fa-trash"></i>
		                        </button>
	                      </div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<style>
	table td{
		vertical-align: middle !important;
	}
</style>
<script>
	$(document).ready(function(){
		
		$('#list').dataTable()
		
		
		//delete department
		$('.delete_branch').click(function(){
			// _conf("Are you sure to delete this department?","delete_dept",[$(this).attr('data-id')])
			var id = $(this).attr('data-id');
        	var confirmDelete = confirm("Are you sure to delete this department?");
        	if (confirmDelete) {
           		delete_dept(id);
        	}
		})

		$('[data-toggle="tooltip"]').tooltip();
	})

	function delete_dept($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_dept',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>