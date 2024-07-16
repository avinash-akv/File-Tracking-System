<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary " href="./index.php?page=new_file"><i class="fa fa-plus"></i> Add New</a>
			</div>
		</div>
		
		<div class="card-body" style="overflow-x:scroll">
			<table class="table tabe-hover table-bordered" id="list" >
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>File Id</th>
						<th>File Name</th>
						<th>Creator's Name</th>
						<th>Department</th>
						<th>Current Location</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$staff_sql = "";
					$staff_qry;
					
					// if(isset($_GET['s'])){
					// 	$where = " where status = {$_GET['s']} ";
					// }
					

					//query to select all files
					$sql = "SELECT 
								f.id as file_id,
								f.file_name as file_name,
								CONCAT(u.firstname, ' ', u.lastname) AS creator_name,
								d.name as dept_name,
								COALESCE( 	CONCAT(sender.firstname,' ',sender.lastname,' [',LEFT(sender_dept.name,3),']',
											'-->',
											recipient.firstname,' ',recipient.lastname,' [',LEFT(recipient_dept.name,3),']') ,
											CONCAT(u.firstname,' ',u.lastname) 
										) AS current_loc,
								COALESCE(t.status, 0) AS current_status,
								t.date_created AS date_created,
								t.receiver_timestamp AS receiver_timestamp
							FROM 
								files as f
							LEFT JOIN 
								users as u ON f.creator_id = u.id
							LEFT JOIN
								departments as d ON u.dept_id = d.id
							LEFT JOIN 
								(
        							SELECT 
										*
									FROM 
										transactions
									WHERE 
										(file_id, date_created) IN (
											SELECT 
												file_id,
												MAX(date_created) AS max_date_created
											FROM 
												transactions
											GROUP BY 
												file_id
										)
								) AS t ON t.file_id = f.id
							LEFT JOIN 
								users AS sender ON t.sender_id = sender.id
							LEFT JOIN 
    							departments AS sender_dept ON sender.dept_id = sender_dept.id
							LEFT JOIN 
								users AS recipient ON t.recipient_id = recipient.id
							LEFT JOIN 
    							departments AS recipient_dept ON recipient.dept_id = recipient_dept.id
							WHERE f.active = 1 
							ORDER BY  unix_timestamp(t.date_created) DESC";

					$qry = $conn->query($sql);
					

					if($_SESSION['login_type'] != 1 || ($_SESSION['login_type'] == 1 && $_GET['admin'] == "false") ){
						//staff will only be able to see files linked to their department
						$staff_sql .= "SELECT DISTINCT f.id AS file_id
										FROM files AS f
										LEFT JOIN users AS u ON f.creator_id = u.id
										LEFT JOIN transactions AS t ON f.id = t.file_id
										WHERE 
											u.dept_id = (
												SELECT dept_id
												FROM users
												WHERE id = '" . $_SESSION['login_id'] . "'
											)
											OR t.sender_id IN (
												SELECT id
												FROM users
												WHERE dept_id = (
													SELECT dept_id
													FROM users
													WHERE id = '" . $_SESSION['login_id'] . "'
												)
											)
											OR t.recipient_id IN (
												SELECT id
												FROM users
												WHERE dept_id = (
													SELECT dept_id
													FROM users
													WHERE id = '" . $_SESSION['login_id'] . "'
												)
											)
											AND f.active = 1";
						
											$staff_qry = ($conn->query($staff_sql))->fetch_all(MYSQLI_ASSOC); //to array
											
					}
					
					while($row= $qry->fetch_assoc()):
					?>

					<?php
						if($_SESSION['login_type'] != 1 || ($_SESSION['login_type'] == 1 && $_GET['admin'] == "false")){
							//login as staff
							$file_id = $row['file_id'];

							// Check if the file_id is present in $staff_qry
							$found = false;
							foreach ($staff_qry as $staff_row) {
								if ($staff_row['file_id'] == $file_id) {
									$found = true;
									break;
								}
							}

							if($found == false){
								continue;
							}
						}


						
						// Get the timestamp of date_created + 3 days
						$dateCreatedPlus3Days = strtotime('+3 days', strtotime($row['date_created']));

						// Get the timestamp of receiver_timestamp + 3 days
						$receiverTimestampPlus3Days = strtotime('+3 days', strtotime($row['receiver_timestamp']));

						// Get today's timestamp
						$todayTimestamp = strtotime('today');

						// Determine the color based on conditions
						$rowColor = '';
						if ($row['current_status'] == 1 && $dateCreatedPlus3Days < $todayTimestamp) {
							$rowColor = 'red'; // Change row color to red
						} elseif ($row['current_status'] == 2 && $receiverTimestampPlus3Days < $todayTimestamp) {
							$rowColor = 'gray'; // Change row color to gray
						}

					?>
					<tr style="background-color: <?php echo $rowColor; ?>">
						<td class="text-center"><?php echo $i++ ?></td>
						<td><b><?php echo ($row['file_id']) ?></b></td>
						<td><b><?php echo ucwords($row['file_name'])?></b></td>
						<td><b><?php echo ucwords($row['creator_name'])?></b></td>
						<td><b><?php echo ucwords($row['dept_name']) ?></b></td>
						<!-- <td><b><?php echo $dateCreatedPlus3Days ?></b></td>
						<td><b><?php echo $receiverTimestampPlus3Days ?></b></td>
						<td><b><?php echo $todayTimestamp ?></b></td> -->
						<td><b><?php echo ucwords($row['current_loc']) ?></b></td>
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
		                    	<button type="button" class="btn btn-info btn-flat view_file" data-id="<?php echo $row['file_id'].'&cur_loc='.$row['current_loc'].'&status='.$row['current_status']  ?>" data-toggle="tooltip" title="View File">
		                          <i class="fas fa-eye"></i>
		                        </button>
								<?php if($_SESSION['login_type'] == 1): ?>
		                        <!-- <a href="index.php?page=edit_file&id=<?php echo $row['file_id'] ?>" class="btn btn-primary btn-flat ">
		                          <i class="fas fa-edit"></i>
		                        </a> -->
		                        <button type="button" class="btn btn-danger btn-flat delete_file" data-id="<?php echo $row['file_id'] ?>" data-toggle="tooltip" title="Delete File">
		                          <i class="fas fa-trash"></i>
		                        </button>
								<?php endif;?>
	                      </div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
			<div class="row mt-3">
				<div class="col-lg-12">
					<div class="alert alert-light" role="alert">
						<strong>Color Legend:</strong>
						<ul>
							<li><span style="color: red;"><strong>Red:</strong> Indicates files that have not been accepted within 3 days of sending.</span></li>
							<li><span style="color: gray;"><strong>Gray:</strong> Indicates files that have been stationed for more than 3 days.</span></li>
						</ul>
					</div>
				</div>
			</div>
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

		$('.delete_file').click(function(){
			// _conf("Are you sure to delete this parcel?","delete_parcel",[$(this).attr('data-id')])
			var id = $(this).attr('data-id');
			var confirmDelete = confirm("Are you sure to delete this file?");
        	if (confirmDelete) {
           		delete_file(id);
        	}
		})

		$('[data-toggle="tooltip"]').tooltip();
	})
	function delete_file($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_file',
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