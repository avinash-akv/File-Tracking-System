<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary " href="./index.php?page=new_staff"><i class="fa fa-plus"></i> Add New</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Id</th>
						<th>Name</th>
						<th>Email</th>
						<th>Department</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT u.id as id,concat(u.firstname,' ',u.lastname) as name,u.email as email,d.name as dept
						FROM users u inner join departments d on d.id = u.dept_id where u.type = 2 and u.active = 1 order by u.id asc ");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td><b><?php echo ($row['id']) ?></b></td>
						<td><b><?php echo ucwords($row['name']) ?></b></td>
						<td><b><?php echo ($row['email']) ?></b></td>
						<td><b><?php echo ucwords($row['dept']) ?></b></td>
						<td class="text-center">
		                    <div class="btn-group">
								<a href="index.php?page=file_list_view&id=<?php echo $row['id'] ?>" class="btn btn-info btn-flat view_file" data-toggle="tooltip" title="Show Associated Files">
									<i class="fa-solid fa-file"></i>
								</a>
		                        <a href="index.php?page=edit_staff&id=<?php echo $row['id'] ?>" class="btn btn-primary btn-flat " data-toggle="tooltip" title="Edit User" >
		                          <i class="fas fa-edit"></i>
		                        </a>
		                        <button type="button" class="btn btn-danger btn-flat delete_staff" data-id="<?php echo $row['id'] ?>" data-toggle="tooltip" title="Delete User">
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

		//delete staff
		$('.delete_staff').click(function(){
			// _conf("Are you sure to delete this staff?","delete_staff",[$(this).attr('data-id')])
			var id = $(this).attr('data-id');
			var confirmDelete = confirm("Are you sure to delete this user?");
        		if (confirmDelete) {
           			delete_staff(id);
        		}
		})
		$('[data-toggle="tooltip"]').tooltip();
	})

	function delete_staff($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_user',
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