<?php include('db_connect.php') ?>
<?php
$twhere = "";
if ($_SESSION['login_type'] != 1)
  $twhere = "  ";



$sql = "SELECT 
      f.id as file_id
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


$staff_sql = "SELECT DISTINCT f.id AS file_id
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

$staff_qry = $conn->query($staff_sql);

// Count the intersecting rows
$intersecting_rows = 0;
while ($row = $qry->fetch_assoc()) {
  // Check if the file_id exists in the result set of staff_sql
  $staff_qry->data_seek(0); // Reset the staff_qry pointer
  while ($staff_row = $staff_qry->fetch_assoc()) {
    if ($row['file_id'] == $staff_row['file_id']) {
      $intersecting_rows++;
      break; // Break the inner loop since we found a match
    }
  }
}


$sql = "SELECT * from (
  SELECT 
    f.id AS file_id,
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
$incoming_files = $conn->query($sql)->num_rows;

$sql = "SELECT * from (
  SELECT 
    f.id AS file_id,
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
WHERE (curr_sender = '".$_SESSION['login_id']."' and current_status = 1)";	
$outgoing_files = $conn->query($sql)->num_rows;		
?>
<!-- Info boxes -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        Welcome <?php echo $_SESSION['login_name']." | Dept. ID : ".$_SESSION['login_dept_id']?>!
      </div>
    </div>
  </div>
</div>


<?php if ($_SESSION['login_type'] == 1) : ?>
  <div class="row">
    <div class="col-12 col-sm-6 col-md-4">
      <a href="./index.php?page=department_list">
        <div class="small-box bg-light shadow-sm border">
          <div class="inner">
            <h3><?php echo $conn->query("SELECT * FROM departments where active = 1")->num_rows; ?></h3>

            <p>Total Departments</p>
          </div>
          <div class="icon">
            <i class="fa fa-building"></i>
          </div>
        </div>
      </a>
    </div>

    <div class="col-12 col-sm-6 col-md-4">
      <a href="./index.php?page=file_list&admin=true">
        <div class="small-box bg-light shadow-sm border">
          <div class="inner">
            <h3><?php echo $conn->query("SELECT * FROM files where active = 1")->num_rows; ?></h3>

            <p>Total Files</p>
          </div>
          <div class="icon">
            <i class="fa fa-boxes"></i>
          </div>
        </div>
      </a>
    </div>

    <div class="col-12 col-sm-6 col-md-4">
      <a href="./index.php?page=staff_list">
        <div class="small-box bg-light shadow-sm border">
          <div class="inner">
            <h3><?php echo $conn->query("SELECT * FROM users where type != 1 and active = 1")->num_rows; ?></h3>

            <p>Total Staff</p>
          </div>
          <div class="icon">
            <i class="fa fa-users"></i>
          </div>
        </div>
      </a>
    </div>

    <div class="col-12 col-sm-6 col-md-4">
      <a href="./index.php?page=file_list&admin=true">
        <div class="small-box bg-light shadow-sm border">
          <div class="inner">
            <h3><?php echo $conn->query("SELECT * FROM transactions")->num_rows; ?></h3>

            <p>Total Transactions</p>
          </div>
          <div class="icon">
            <i class="fa fa-building"></i>
          </div>
        </div>
      </a>
    </div>

    <div class="col-12 col-sm-6 col-md-4">
      <a href="./index.php?page=file_list&admin=true">
        <div class="small-box bg-light shadow-sm border">
          <div class="inner">
            <h3><?php echo $conn->query("SELECT * FROM transactions where status = 1")->num_rows; ?></h3>

            <p>Files in transit</p>
          </div>
          <div class="icon">
            <i class="fa fa-building"></i>
          </div>
        </div>
      </a>
    </div>

    <div class="col-12 col-sm-6 col-md-4">
      <a href="./index.php?page=file_list&admin=true">
        <div class="small-box bg-light shadow-sm border">
          <div class="inner">
            <h3><?php echo $conn->query("SELECT * FROM transactions where status = 2")->num_rows; ?></h3>

            <p>Received</p>
          </div>
          <div class="icon">
            <i class="fa fa-building"></i>
          </div>
        </div>
      </a>
    </div>
  </div>
  <hr class="border-primary">
<?php endif; ?>

<?php if($_SESSION['login_type'] == 1): ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        Admin's Personal Dashboard
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="row">
  <div class="col-12 col-sm-6 col-md-4">
    <a href="./index.php?page=file_list&admin=false">
      <div class="small-box bg-light shadow-sm border">
        <div class="inner">
          <h3><?php echo $intersecting_rows; ?></h3>

          <p>Your Files</p>
        </div>
        <div class="icon">
          <i class="fa fa-building"></i>
        </div>
      </div>
    </a>
  </div>
  <div class="col-12 col-sm-6 col-md-4">
    <a href="./index.php?page=receive_file">
      <div class="small-box bg-light shadow-sm border">
        <div class="inner">
          <h3><?php echo $incoming_files; ?></h3>

          <p>Incoming Files</p>
        </div>
        <div class="icon">
          <i class="fa fa-boxes"></i>
        </div>
      </div>
    </a>
  </div>

  <div class="col-12 col-sm-6 col-md-4">
    <a href="./index.php?page=file_list&admin=false">
      <div class="small-box bg-light shadow-sm border">
        <div class="inner">
          <h3><?php echo $outgoing_files; ?></h3>

          <p>Outgoing Files</p>
        </div>
        <div class="icon">
          <i class="fa fa-users"></i>
        </div>
      </div>
    </a>
  </div>
</div>