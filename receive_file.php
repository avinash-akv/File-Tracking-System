<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>File Id</th>
						<th>File Name</th>
						<th>Sender id</th>
						<th>Sender's Name and department</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$sql = "SELECT * from (
								SELECT 
									f.id AS file_id,
									t.reference_number AS ref_no,
									f.file_name AS file_name,
									u.id AS creator_id,
									sender.id as curr_sender,
									recipient.id as curr_receiver,
									CONCAT(sender.firstname,' ',sender.lastname,' , ',sender_dept.name) as sender_name_dept,
									COALESCE(t.status, 0) AS current_status,
									COALESCE(CONCAT(sender.firstname,' ',sender.lastname,' [',LEFT(sender_dept.name, 3),']','-->',
													recipient.firstname,' ',recipient.lastname,' [',LEFT(recipient_dept.name, 3),']'),
											CONCAT(u.firstname, ' ', u.lastname)) AS current_loc
								FROM
									files AS f
								LEFT JOIN
									users AS u ON f.creator_id = u.id
								LEFT JOIN
									departments AS d ON u.dept_id = d.id
								LEFT JOIN
									(SELECT 
										*
									FROM
										transactions
									WHERE
										(file_id , date_created) IN (
											SELECT 
												file_id, MAX(date_created) AS max_date_created
											FROM
												transactions
											GROUP BY file_id)) AS t ON t.file_id = f.id
								LEFT JOIN
									users AS sender ON t.sender_id = sender.id
								LEFT JOIN
									departments AS sender_dept ON sender.dept_id = sender_dept.id
								LEFT JOIN
									users AS recipient ON t.recipient_id = recipient.id
								LEFT JOIN
									departments AS recipient_dept ON recipient.dept_id = recipient_dept.id
								WHERE
									f.active = 1) as subquery
							WHERE (curr_receiver = '".$_SESSION['login_id']."' and current_status = 1)";			
					$qry = $conn->query($sql);
					

					while($row= $qry->fetch_assoc()):
					?>

					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td><b><?php echo ($row['file_id']) ?></b></td>
						<td><b><?php echo ucwords($row['file_name']) ?></b></td>
						<td><b><?php echo $row['curr_sender'] ?></b></td>
						<td><b><?php echo ucwords($row['sender_name_dept']) ?></b></td>
						<td class="text-center">
							<?php 
							switch ($row['current_status']) {
								case '1':
									echo "<span class='badge badge-pill badge-info'> Sent</span>";
									break;
								case '2':
									echo "<span class='badge badge-pill badge-success'>Accepted</span>";
									break;
								default:
									echo "<span class='badge badge-pill badge-dark'> File Created</span>";
							}
							?>
						</td>
						<td class="text-center">
		                    <div class="btn-group">
		                    	<button type="button" class="btn btn-info btn-flat view_file" data-toggle="tooltip" title="View File" data-id="<?php echo $row['file_id'].'&cur_loc='.$row['current_loc'].'&status='.$row['current_status']  ?>">
		                        	<i class="fas fa-eye"></i>
		                        </button>
								
								<button type="button" class="btn btn-danger btn-flat return_file" data-toggle="tooltip" title="Return File" data-id="<?php echo $row['ref_no'].'&id='.$row['file_id'] ?>">
									<i class="fa-solid fa-arrow-left"></i>
								</button>
								<button type="button" class="btn btn-primary btn-flat accept_file" data-toggle="tooltip" title="Accept File" data-id="<?php echo $row['ref_no'].'&id='.$row['file_id'] ?>">
									<i class="fa-solid fa-check"></i>
		                        </button>
								<button type="button" class="btn btn-primary btn-flat forward_file" data-toggle="tooltip" title="Forward File" data-id="<?php echo $row['ref_no'].'&id='.$row['file_id'] ?>">
									<i class="fa-solid fa-share-from-square"></i>
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
		$('.view_file').click(function(){
			uni_modal("File's Details","view_file.php?id="+$(this).attr('data-id'),"large");
			// console.log("view_file.php?id="+$(this).attr('data-id'));
		})

		$('.accept_file').click(function(){
			uni_modal("Accept File","status_accept.php?ref="+$(this).attr('data-id'),"small");
		})
		$('.return_file').click(function(){
			uni_modal("Return File","status_return.php?ref="+$(this).attr('data-id'),"small");
		})
		$('.forward_file').click(function(){
			uni_modal("Forward File","status_forward.php?ref="+$(this).attr('data-id'),"large");
		})

		$('[data-toggle="tooltip"]').tooltip();
	})

	function accept_file($id,$rec){
		start_load()
		$.ajax({
			url:'ajax.php?action=accept_file',
			method:'POST',
			data:{ref:$id,rec_note:$rec},
			success:function(resp){
				if(resp==1){
					alert_toast("File Successfully Accepted",'success')
					setTimeout(function(){
						location.reload()
					},1000)
				}
			}
		})
	}
	function return_file($id,$rec){
		start_load()
		$.ajax({
			url:'ajax.php?action=return_file',
			method:'POST',
			data:{ref:$id,rec_note:$rec},
			success:function(resp){
				if(resp==1){
					alert_toast("File Successfully Returned",'success')
					setTimeout(function(){
						location.reload()
					},1000)
				}
			}
		})
	}
	function forward_file($ref,$rec_id,$rec,$sen){
		start_load()
		$.ajax({
			url:'ajax.php?action=forward_file',
			method:'POST',
			data:{ref:$ref,rec_id:$rec_id,rec:$rec,sen:$sen},
			success:function(resp){
				console.log(resp);
				if(resp==1){
					alert_toast("File Successfully forwarded",'success')
					setTimeout(function(){
						location.reload()
					},1000)
				}
			}
		})
	}
</script>