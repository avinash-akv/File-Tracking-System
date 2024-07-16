<?php
include 'db_connect.php';
?>
<style>
    textarea{
        resize: none;
    }
</style>

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
                        <div class="row">
                            <div class="col-sm-6 form-group ">
                                <label for="" class="control-label">Recipient Department</label>
                                <select name="dept_id" id="dept_id" class="form-control input-sm" required>
                                <option value=""></option>
                                <?php
                                    $departments = $conn->query("SELECT name,id FROM departments where active = 1");
                                    while($row = $departments->fetch_assoc()):
                                ?>
                                <option value="<?php echo $row['id'] ?>" <?php echo isset($dept_id) && $dept_id == $row['id'] ? "selected":'' ?>><?php echo $row['id'].' | '.$row['name'] ?></option>
                                <?php endwhile; ?>
                                </select>

                                <label for="" class="control-label">Receiving Remarks</label>
                                <textarea  rows = 10 name="rec_remarks" id="rec_remarks" class="form-control form-control-sm" value="<?php echo isset($rec_remarks) ? $rec_remarks : '' ?>"  required></textarea>
                            </div>
                            <div class="col-sm-6 form-group ">
                                <label for="" class="control-label">Recipient User</label>
                                <select name="user_id" id="user_id" class="form-control input-sm" required></select>

                                <label for="" class="control-label">Sending Remarks</label>
                                <textarea  rows = 10 name="sen_remarks" id="sen_remarks" class="form-control form-control-sm" value="<?php echo isset($sen_remarks) ? $sen_remarks : '' ?>"  required></textarea>
                            </div>
                        </div>
                </div>
            </div>
        </div>
  	</div>
</div>

<div class="modal-footer display p-0 m-0">
        <button type="button" class="btn btn-primary forward_file_modal" data-dismiss="modal" data-id =<?php echo $_GET['ref']?>>Forward File</button>
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

    $(document).ready(function () {
        $('#dept_id').change(function () {
            var deptId = $(this).val();
            
            $.ajax({
                url: 'ajax.php?action=get_users',
                method: 'POST',
                data: {dept_id: deptId},
                success: function (data) {
                    // Update the user dropdown with the retrieved options
                    $('#user_id').html(data);
                }
            });
        });
    });

$('.forward_file_modal').click(function(){
	var ref = $(this).attr('data-id');
    var rec_id = $('#user_id').val();
	var rec = $('#rec_remarks').val();
    var sen = $('#sen_remarks').val();
    
    forward_file(ref,rec_id,rec,sen);
})
</script>
