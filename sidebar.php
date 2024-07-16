  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="dropdown">
   	<a href="./" class="brand-link">
        <?php if($_SESSION['login_type'] == 1): ?>
        <h3 class="text-center p-0 m-0"><b>ADMIN</b></h3>
        <?php else: ?>
        <h3 class="text-center p-0 m-0"><b>STAFF</b></h3>
        <?php endif; ?>
    </a>
      
    </div>
    <div class="sidebar pb-4 mb-4">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item dropdown">
            <a href="./" class="nav-link nav-home">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>     
          <?php if($_SESSION['login_type'] == 1): ?>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_department">
              <i class="nav-icon fas fa-building"></i>
              <p>
                Department
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_department" class="nav-link nav-new_department tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=department_list" class="nav-link nav-department_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_staff">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Staff
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_staff" class="nav-link nav-new_staff tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=staff_list" class="nav-link nav-staff_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif; ?>

        <!-- common for admin and staff -->
        <li class="nav-item">
            <a href="#" class="nav-link nav-edit_file">
              <i class="nav-icon fas fa-file"></i>
              <p>
                Files
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_file" class="nav-link nav-new_file tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=file_list&admin=false" class="nav-link nav-file_list tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Track Files</p>
                </a>
              </li>
              <?php if($_SESSION['login_type'] == 1):?>
              <li class="nav-item">
                <a href="./index.php?page=file_list&admin=true" class="nav-link nav-file_alllist tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Track All Files</p>
                </a>
              </li>
              <?php endif; ?>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link nav-transaction">
            <i class="nav-icon fa-solid fa-arrow-right-arrow-left"></i>
              <p>
                Transactions
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=send_file" class="nav-link nav-send_file tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Send Files</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=receive_file" class="nav-link nav-incoming_file tree-item">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Incoming Files</p>
                </a>
              </li>
            </ul>
          </li>
          
          <li class="nav-item">
            <a href="./index.php?page=about-us" class="nav-link nav-transaction">
            <i class="nav-icon fa-solid fa-circle-info"></i>
              <p>
                About Us

              </p>
            </a>
          </li>
      
        </ul>
      </nav>
    </div>
  </aside>
  <script>
  	$(document).ready(function(){
      var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
  		var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
      if(s!='')
        page = page+'_'+s;
  		if($('.nav-link.nav-'+page).length > 0){
             $('.nav-link.nav-'+page).addClass('active')
  			if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
  				$('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
  			}
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }

  		}
     
  	})
  </script>