<?php
include 'db_connect.php';
?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-12">
				<div class="callout callout-info">
					<dl>
						<dt>Tracking Number (File Id):</dt>
						<dd> <h4><b><?php echo $_GET['id'] ?></b></h4></dd>
					</dl>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="callout callout-info">
					<dl>
                    <dt>Receiver's Notes : </dt>
                    <form></form>
					<dd> <textarea name="receiver_notes" class="form-control" id="receiver_notes" cols="30" rows="10" style="resize: none;" required></textarea></dd>
					</dl>
				</div>
			</div>
		</div>
    </div>
</div>
	
<div class="modal-footer display p-0 m-0">
        <button type="button" class="btn btn-primary accept_file_modal" data-dismiss="modal" data-id =<?php echo $_GET['ref']?>>Accept File</button>
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
</noscript>

<script>
$('.accept_file_modal').click(function(){
	var ref = $(this).attr('data-id');
	var rec = $('#receiver_notes').val();
    accept_file(ref,rec);
})
</script>
