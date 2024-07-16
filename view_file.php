<?php
include 'db_connect.php';

//file details
$sql = "SELECT 
		f.id AS file_id,
		f.file_name AS file_name,
		f.creator_id as creator_id,
		CONCAT(u.firstname,
				' ',
				u.lastname
				) AS creator_name,
		d.id as dept_id,
		d.name as dept_name,
		f.file_desc as file_desc,
		f.date_created as date_created
		FROM
		files AS f
			JOIN
		users AS u ON f.creator_id = u.id
			JOIN
		departments AS d ON u.dept_id = d.id
		WHERE
		f.active = 1 AND f.id = '" . $_GET['id'] ."'";

$file_details = $conn->query($sql)->fetch_assoc();   

//transaction details
$sql2 = "SELECT 
			t.reference_number as reference_number,
			t.sender_id as sender_id,
			CONCAT(sender.firstname,' ',sender.lastname) as sender_name,
			sender_dept.name as sender_dept,
			t.recipient_id as receiver_id,
			CONCAT(recipient.firstname,' ',recipient.lastname) as receiver_name,
			recipient_dept.name as receiver_dept,
			t.status AS current_status,
			t.date_created AS sender_timestamp,
			coalesce(t.receiver_timestamp,'N/A') AS receiver_timestamp,
			t.sender_notes AS sender_notes,
			t.receiver_notes AS receiver_notes
			FROM
			files AS f
			JOIN
			users AS u ON f.creator_id = u.id
				JOIN
			departments AS d ON u.dept_id = d.id
				JOIN
			transactions AS t ON t.file_id = f.id
				JOIN
			users AS sender ON t.sender_id = sender.id
				JOIN
			departments AS sender_dept ON sender.dept_id = sender_dept.id
				JOIN
			users AS recipient ON t.recipient_id = recipient.id
				JOIN
			departments AS recipient_dept ON recipient.dept_id = recipient_dept.id
			WHERE
			f.active = 1 AND f.id = '".$_GET['id']."'
			ORDER BY UNIX_TIMESTAMP(t.date_created) DESC";

$list_transactions = $conn->query($sql2);
?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-lg-12 text-right">
				<button type="button" class="btn btn-secondary mb-3" data-dismiss="modal">Close</button>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="callout callout-info">
					<dl>
						<dt>Tracking Number (File Id):</dt>
						<dd> <h4><b><?php echo $file_details['file_id'] ?></b></h4></dd>
					</dl>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="callout callout-info">
					<b class="border-bottom border-primary">File Information</b>
					<dl>
						<dt>File Name :</dt>
						<dd><?php echo ucwords($file_details['file_name']) ?></dd>
						<dt>File Creator's Id :</dt>
						<dd><?php echo ucwords($file_details['creator_id']) ?></dd>
						<dt>Creator's Name :</dt>
						<dd><?php echo ucwords($file_details['creator_name']) ?></dd>
						<dt>Originating Department Id :</dt>
						<dd><?php echo ucwords($file_details['dept_id']) ?></dd>
						<dt>Department Name :</dt>
						<dd><?php echo ucwords($file_details['dept_name']) ?></dd>
						<dt>Date Created :</dt>
						<dd><?php echo $file_details['date_created'] ?></dd>
					</dl>
				</div>
			</div>
			<div class="col-md-6">
				<div class="callout callout-info">
					<b class="border-bottom border-primary">File Description</b>
					<dl>
						<dt>Initial Remarks by creator :</dt>
						<dd><?php echo ucwords($file_details['file_desc']) ?></dd>
						<dt>Current Location :</dt>
						<dd><?php echo $_GET['cur_loc'] ?></dd>
						<dt>Current Status :</dt>
						<dd><?php 
								switch ($_GET['status']) {
									case '1':
										echo "<span class='badge badge-pill badge-info'> Sent</span>";
										break;
									case '2':
										echo "<span class='badge badge-pill badge-success'>Accepted</span>";
										break;
									default:
										echo "<span class='badge badge-pill badge-dark'> File Created</span>";
								}
						?></dd>
					</dl>
				</div>
			</div>
		</div>

		
		<?php $i = 1;while($transactions = $list_transactions->fetch_assoc()): ?>
			<div id="accordion">
				<div class="cardd callout callout-info">
					<div class="card-headerr " id="headingOne">
					<h5 class="mb-0">
						<button class="btn" data-toggle="collapse" data-target="#collapse<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse<?php echo $i; ?>">
						<b class="w-50 h-50 me-4">
							<!-- <?php echo $i;?> -->
					</b>
						<span>
							<b>From:</b>  <?php echo $transactions['sender_name'] ?>

						</span>	
						<span>
						<b>To:</b>  <?php echo $transactions['receiver_name'] ?>
							
						</span>	
						</button>
					</h5>
					</div>

					<div id="collapse<?php echo $i; ?>" class="collapse" aria-labelledby="heading<?php echo $i; ?>" data-parent="#accordion">
					<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div>
							<!-- <div class="callout callout-info"> -->
								<b class="border-bottom border-primary">Reference Number : </b>
								<dl>
									<dt></dt>
									<dd><?php echo $transactions['reference_number'] ?></dd>
									<dt>Sender Id :</dt>
									<dd><?php echo $transactions['sender_id'] ?></dd>
									<dt>Sender's Name :</dt>
									<dd><?php echo ucwords($transactions['sender_name']) ?></dd>
									<dt>Sender Department :</dt>
									<dd><?php echo ucwords($transactions['sender_dept']) ?></dd>
									<dt>Time-stamp :</dt>
									<dd><?php echo $transactions['sender_timestamp'] ?></dd>
									<details>
										<summary>Sender's Notes</summary>
										<p><?php echo $transactions['sender_notes'] ?></p>
									</details>
								</dl>
							</div>
						</div>
						<div class="col-md-6">
						<!-- <div class="callout callout-info"> -->
							<div>
								<b class="border-bottom border-primary">Current Status : </b>
								<dl>
									<dt></dt>
									<dd><?php echo $transactions['current_status'] ?></dd>
									<dt>Receiver Id :</dt>
									<dd><?php echo $transactions['receiver_id'] ?></dd>
									<dt>receiver's Name :</dt>
									<dd><?php echo ucwords($transactions['receiver_name']) ?></dd>
									<dt>Receiver Department :</dt>
									<dd><?php echo ucwords($transactions['receiver_dept']) ?></dd>
									<dt>Time-stamp :</dt>
									<dd><?php echo $transactions['receiver_timestamp'] ?></dd>
									<details>
										<summary>Receiver's Notes</summary>
										<p><?php echo $transactions['receiver_notes']; $i++ ?></p>
									</details>
								</dl>
							</div>
						</div>
					</div>
					</div>
					</div>
				</div>
			</div>

		<?php endwhile; ?>
	</div>
</div>
<div class="modal-footer display p-0 m-0">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
<style>
	#uni_modal .modal-footer{
		display: none
	}
	#uni_modal .modal-footer.display{
		display: flex
	}
</style>
<noscript>
	<style>
		table.table{
			width:100%;
			border-collapse: collapse;
		}
		table.table tr,table.table th, table.table td{
			border:1px solid;
		}
		.text-cnter{
			text-align: center;
		}
	</style>
	<h3 class="text-center"><b>Student Result</b></h3>
</noscript>
<!-- <script>
	$('#update_status').click(function(){
		uni_modal("Update Status of: <?php echo $reference_number ?>","manage_parcel_status.php?id=<?php echo $id ?>&cs=<?php echo $status ?>","")
	})
</script> -->