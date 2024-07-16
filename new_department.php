<?php if(!isset($conn)){ 
  include 'db_connect.php'; 
  } ?>

<style>
  textarea{
    resize: none;
  }
</style>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-body">
			<form action="" id="manage-dept">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="row">
          <div class="col-md-12">
            <div id="msg" class=""></div>

            <div class="row">
              <div class="col-sm-6 form-group ">
                <label for="id" class="control-label">Department ID</label>
                <input type="text" name="id" id="id" class="form-control" value="<?php echo isset($id) ? $id : '' ?>" required>
              </div>
              <div class="col-sm-6 form-group ">
                <label for="name" class="control-label">Department Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($name) ? $name : '' ?>" required>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6 form-group ">
                <label for="contact" class="control-label">Contact No.</label>
                <input type="phone" name="contact" id="contact" class="form-control" value="<?php echo isset($contact) ? $contact : '' ?>" required>
              </div>
              <div class="col-sm-6 form-group ">
                <label for="email" class="control-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo isset($email) ? $email : '' ?>" required>
              </div>
            </div>

          </div>
        </div>
      </form>
  	</div>
  	<div class="card-footer border-top border-info">
  		<div class="d-flex w-100 justify-content-center align-items-center">
  			<button class="btn btn-flat  bg-gradient-primary mx-2" form="manage-dept">Save</button>
  			<a class="btn btn-flat bg-gradient-secondary mx-2" href="./index.php?page=department_list">Cancel</a>
  		</div>
  	</div>
	</div>
</div>
<script>
	$('#manage-dept').submit(function(e){
		e.preventDefault()
		start_load()
    // console.log(new FormData($(this)[0]));
		$.ajax({
			url:'ajax.php?action=save_dept',
			data: new FormData($(this)[0]),
		  cache: false,
		  contentType: false,
		  processData: false,
		  method: 'POST',
		  type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved',"success");
					setTimeout(function(){
              location.href = 'index.php?page=department_list'
					},2000)
				}
			}
		})
	})

</script>