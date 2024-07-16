<?php if(!isset($conn)){ include 'db_connect.php'; } ?>
<style>
  textarea{
    resize: none;
  }
</style>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-body">
			<form action="" id="manage-file">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg" class=""></div>
        <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                <label for="" class="control-label">File Id</label>
                <input type="text" name="file_id" id="" class="form-control form-control-sm" value="<?php echo isset($file_id) ? $file_id : '' ?>" required>
              </div>
          </div>
          <div class="col-md-6">
              <div class="form-group">
                <label for="" class="control-label">File Name</label>
                <input type="text"  name="file_name" id="" class="form-control form-control-sm" value="<?php echo isset($file_name) ? $file_name : '' ?>" required>
              </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
              <div class="form-group">
                <label for="" class="control-label">Remarks about the file</label>
                <textarea  rows = 10 name="remarks" id="" class="form-control form-control-sm" value="<?php echo isset($remarks) ? $remarks : '' ?>"  required></textarea>
              </div>
          </div>
        </div>
      </form>
  	</div>
  	<div class="card-footer border-top border-info">
  		<div class="d-flex w-100 justify-content-center align-items-center">
  			<button class="btn btn-flat  bg-gradient-primary mx-2" form="manage-file">Save</button>
  			<a class="btn btn-flat bg-gradient-secondary mx-2" href="./index.php?page=file_list&admin=false">Cancel</a>
  		</div>
  	</div>
	</div>
</div>

<script>
	$('#manage-file').submit(function(e){
		e.preventDefault()
		start_load()
    // console.log(new FormData($(this)[0]));
		$.ajax({
			url:'ajax.php?action=save_file',
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
            location.href = 'index.php?page=file_list&admin=false';
          },2000)
        }
			}
		})
	})
</script>