<?php if(!isset($conn)){ include 'db_connect.php'; } ?>
<style>
  textarea{
    resize: none;
  }
</style>

<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-body">
			<form action="" id="send-file">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="row">
          <div class="col-md-12">
            <div id="msg" class=""></div>

            <div class="row">
              <div class="col-sm-6 form-group ">
                    <label for="" class="control-label">File</label>
                    <select name="file_id" id="file_id" class="form-control input-sm" required>
                        <option value=""></option>
                        <?php
                            $sql = "select * from (SELECT 
                                      f.id AS file_id,
                                      t.reference_number AS ref_no,
                                      f.file_name AS file_name,
                                      u.id AS creator_id,
                                      sender.id as curr_sender,
                                      recipient.id as curr_receiver,
                                      COALESCE(t.status, 0) AS current_status
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
                                              (file_id , date_created) IN (SELECT 
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
                                      where  (creator_id = '".$_SESSION['login_id']."' and current_status = 0)  or (curr_receiver = '".$_SESSION['login_id']."' and current_status = 2) ";

                            $files = $conn->query($sql);
                            while($row = $files->fetch_assoc()):
                        ?>
                        <option value="<?php echo $row['file_id'] ?>" <?php echo isset($dept_id) && $dept_id == $row['id'] ? "selected":'' ?>><?php echo $row['file_id']." | ".$row['file_name'] ?></option>
                        <?php endwhile; ?>
                    </select>

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

                    <label for="" class="control-label">Recipient User</label>
                    <select name="user_id" id="user_id" class="form-control input-sm" required>
                        
                    </select>
              </div>
              <div class="col-sm-6 form-group ">
                    <label for="" class="control-label">Sender's Remarks</label>
                    <textarea  rows = 10 name="remarks" id="remarks" class="form-control form-control-sm" value="<?php echo isset($remarks) ? $remarks : '' ?>"  required></textarea>
              </div>
            </div>
          </div>
        </div>
      </form>
  	</div>
  	<div class="card-footer border-top border-info">
  		<div class="d-flex w-100 justify-content-center align-items-center">
  			<button class="btn btn-flat  bg-gradient-primary mx-2" form="send-file" >Send</button>
  			<a class="btn btn-flat bg-gradient-secondary mx-2" href="./index.php?page=file_list&admin=false">Cancel</a>
  		</div>
  	</div>
	</div>
</div>
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


	$('#send-file').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=send_file',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('File successfully sent',"success");
					setTimeout(function(){
                    location.href = 'index.php?page=file_list&admin=false'
					},2000)
				}else if(resp == 2){
                    $('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> File not sent.</div>')
                    end_load()
                }
			}
		})
	})
  
</script>