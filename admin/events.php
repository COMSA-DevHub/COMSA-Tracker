<?php
session_start();
require_once "../functions/config.php"; // DB connection

// Admin check
if(!isset($_SESSION['student_number']) || $_SESSION['is_admin'] != 1){
    header("Location: login.php");
    exit();
}

// Fetch events with printed/signed
$result = $conn->query("
    SELECT e.*,
           ep.sas_f6 AS p_sas, ep.transmittal AS p_trans, ep.invitation AS p_inv, ep.endorsement AS p_end,
           es.sas_f6 AS s_sas, es.transmittal AS s_trans, es.invitation AS s_inv, es.endorsement AS s_end
    FROM events e
    LEFT JOIN events_printed ep ON e.id = ep.event_id
    LEFT JOIN events_signed es ON e.id = es.event_id
    ORDER BY e.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | COMSA - TRACKER</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons (for a cleaner look) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../styles.css">
        <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        /* 1. Remove unnecessary top padding and set background */
        body {
            padding-top: 0;
            padding-bottom: 20px;
            background-color: #f8f9fa;
        }

        /* 2. Main wrapper uses Flexbox for side-by-side layout */
        #wrapper {
            display: flex;
            width: 100%;
        }

        /* 4. Content Area Styles */
        #page-content-wrapper {
            flex-grow: 1;
            width: 100%;
            /* Offset content to make space for the desktop sidebar */
            padding-top: 70px; /* Padding for the fixed top header */
        }

        .top-header {
            width: 100%;
            padding-left: 250px;
            z-index: 1000;
        }
        
        /* Adjust for overall responsiveness */
        .container-fluid {
            max-width: 100%; /* Use full width in the dashboard layout */
        }


      /* Modern Table Styles */
      .table {
            border-radius: 8px; /* Slight rounding for the whole table container */
            overflow: hidden; /* Ensures rounded corners apply correctly */
            margin-top: 15px;
            border-collapse: separate; /* Required for border-spacing if needed */
    }

    /* Header styling */
      .table-light th {
            background-color: #e9ecef; /* Light grey header background */
            color: #495057;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border-bottom: 2px solid #dee2e6;
    }

        /* Row hover effect for better interactivity */
        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
            transition: background-color 0.2s;
        }

          /* Badge styling for status */
          .badge {
              padding: 0.5em 0.8em;
              font-weight: 600;
          }

          /* Custom button width adjustment for columns */
          .table-hover td:nth-child(5), /* Printed column */
          .table-hover td:nth-child(6) { /* Signed column */
              width: 120px; /* Give the buttons fixed space */
          }
    </style>
</head>
<body>
    
    <!-- Overall Layout Wrapper -->
    <div id="wrapper">
        <!-- Navbar -->
<nav class="navbar navbar-light bg-white shadow-sm fixed-top">
  <div class="container-xxl d-flex align-items-center justify-content-between">

    <!-- Left: tracker_logo -->
    <a class="navbar-brand fs-2 fw-bold d-flex align-items-center gap-2" href="#">
      <img src="../img/tracker-logo.png" alt="" class="img-fluid" style="height:60px;">
      <span class="d-lg-inline">COMSA-TRACKER</span>
    </a>



      <!-- Right: Icon buttons -->
    <div class="d-flex align-items-center gap-3 d-none d-lg-flex">

      <a href="admin/admin_dashboard.php" class="btn btn-light rounded-3 d-flex align-items-center justify-content-center"
         style="width:50px; height:50px;">
        <i class="ri-dashboard-line fs-4"></i>
      </a>

      <a href="events.php"
         class="btn btn-active rounded-3 d-flex align-items-center justify-content-center"
         style="width:50px; height:50px;">
        <i class="ri-calendar-schedule-line fs-4"></i>
      </a>

       <a href="tasks.php"
         class="btn btn-light rounded-3 d-flex align-items-center justify-content-center"
         style="width:50px; height:50px;">
        <i class="ri-list-check-2 fs-4"></i>
      </a>

       <a href="users.php"
         class="btn btn-light rounded-3 d-flex align-items-center justify-content-center"
         style="width:50px; height:50px;">
        <i class="ri-user-3-line fs-4"></i>
      </a>

      <a href="settings.php"
         class="btn btn-light rounded-3 d-flex align-items-center justify-content-center"
         style="width:50px; height:50px;">
        <i class="ri-settings-line fs-4"></i>
      </a>

  </div>
</nav>

<!-- /Navbar -->
    

        <!-- Page Content Wrapper -->
        <div id="page-content-wrapper">
            
            <!-- TOP HEADER (for Brand and Mobile Toggle) -->
            <nav class="navbar navbar-expand-lg fixed-top top-header">
                <div class="container-fluid">
                    <!-- Hamburger Toggle Button (HIDES ON LARGE SCREENS AND UP) -->
                    <button class="btn btn-comsa d-lg-none" id="sidebarToggle" aria-label="Open menu">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
            </nav>

 <main class="container-fluid py-5">
    <div class="row g-4 justify-content-center">
        <div class="card shadow-md border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom material-header-top">
                    <h2 class="fw-bold mb-0 text-dark">Events</h2>
                    <button class="btn btn-comsa fw-bold" data-bs-toggle="modal" data-bs-target="#addEventModal">
                        <i class="ri-add-line me-1"></i> Add Event
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-borderless table-hover align-middle material-table">
                        <thead class="material-header-bottom">
                            <tr>
                                <th scope="col" style="width: 10%;">TYPE</th>
                                <th scope="col">TITLE</th>
                                <th scope="col" style="width: 12%;">DUE DATE</th>
                                <th scope="col" style="width: 12%;">STATUS</th>
                                <th scope="col" class="text-center" style="width: 10%;">PRINTED</th>
                                <th scope="col" class="text-center" style="width: 10%;">SIGNED</th>
                                <th scope="col" class="text-center" style="width: 10%;">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($row = $result->fetch_assoc()): 
                            // ... Calculation Logic remains the same ...
                            $printed_total = $row['p_sas'] + $row['p_trans'] + $row['p_inv'] + $row['p_end'];
                            $signed_total  = $row['s_sas'] + $row['s_trans'] + $row['s_inv'] + $row['s_end'];
                            
                            // Status Badge Logic (Green Shades for Monochromatic look)
                            $status_class = match($row['status']) {
                                'Pending' => 'text-warning bg-warning-subtle',   // Light Accent Green/Yellow
                                'Ongoing' => 'text-info bg-info-subtle',         // Dark Green (Info)
                                'Completed' => 'text-success bg-success-subtle', // Bright Green (Success)
                                default => 'text-secondary bg-light-subtle',
                            };
                            
                            // Printed & Signed Button Logic (Dark Green for incomplete, Bright Green for complete)
                            $printed_btn_class = $printed_total == 4 ? 'text-success' : 'text-info';
                            $signed_btn_class = $signed_total == 4 ? 'text-success' : 'text-info';
                        ?>
                        <tr class="align-middle">
                            <td><span class="badge rounded-pill text-secondary bg-light-subtle border border-secondary-subtle"><?= $row['type'] ?></span></td>
                            
                            <td class="fw-medium text-dark"><?= $row['title'] ?></td>
                            
                            <td><?= date('M j, Y', strtotime($row['due_date'])) ?></td>
                            
                            <td><span class="badge rounded-pill fw-medium <?= $status_class ?>"><?= $row['status'] ?></span></td>
                            
                            <td class="text-center">
                                <button class="btn btn-sm btn-link <?= $printed_btn_class ?> printed-btn text-decoration-none"
                                    data-bs-toggle="modal" data-bs-target="#printedModal"
                                    data-id="<?= $row['id'] ?>" data-sas="<?= $row['p_sas'] ?>" data-trans="<?= $row['p_trans'] ?>"
                                    data-inv="<?= $row['p_inv'] ?>" data-end="<?= $row['p_end'] ?>">
                                    <?= $printed_total ?>/4
                                </button>
                            </td>
                            
                            <td class="text-center">
                                <button class="btn btn-sm btn-link <?= $signed_btn_class ?> signed-btn text-decoration-none"
                                    data-bs-toggle="modal" data-bs-target="#signedModal"
                                    data-id="<?= $row['id'] ?>" data-sas="<?= $row['s_sas'] ?>" data-trans="<?= $row['s_trans'] ?>"
                                    data-inv="<?= $row['s_inv'] ?>" data-end="<?= $row['s_end'] ?>">
                                    <?= $signed_total ?>/4
                                </button>
                            </td>
                            
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-icon edit-btn text-primary" 
                                        data-bs-toggle="modal" data-bs-target="#editEventModal" title="Edit"  data-id="<?= $row['id'] ?>"
                    data-type="<?= $row['type'] ?>"
                    data-title="<?= $row['title'] ?>"
                    data-due="<?= $row['due_date'] ?>"
                    data-status="<?= $row['status'] ?>">
                                        <i class="ri-edit-line"></i>
                                    </button>
                                    <button class="btn btn-icon delete-btn text-danger" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal" title="Delete" data-id="<?= $row['id'] ?>">
                                        <i class="ri-delete-bin-line"></i>
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
    </div>
</main>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
<div class="modal-dialog"><div class="modal-content">
<form method="POST" action="../functions/save_events.php">
<div class="modal-header text-dark">
    <h5 class="modal-title" id="addEventModalLabel"><i class="ri-add-line me-2"></i> Add New Event</h5>
    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        <label class="form-label">Type</label>
        <select class="form-select" name="type" required>
            <option value="Off-Campus">Off-Campus</option>
            <option value="On-Campus">On-Campus</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Due Date</label>
        <input type="date" name="due_date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select class="form-select" name="status" required>
            <option value="Pending">Pending</option>
            <option value="Ongoing">Ongoing</option>
            <option value="Completed">Completed</option>
        </select>
    </div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-comsa"><i class="ri-save-line me-1"></i> Save Event</button>
</div>
</form>
</div></div>
</div>

<!-- Edit Event Modal -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
<div class="modal-dialog"><div class="modal-content">
<form method="POST" action="../functions/update_events.php">
<input type="hidden" name="id" id="edit-id">
<div class="modal-header text-dark">
    <h5 class="modal-title" id="editEventModalLabel"><i class="ri-edit-line me-2"></i> Edit Event Details</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3"><label class="form-label">Type</label><input type="text" class="form-control" id="edit-type" name="type" required></div>
    <div class="mb-3"><label class="form-label">Title</label><input type="text" class="form-control" id="edit-title" name="title" required></div>
    <div class="mb-3"><label class="form-label">Due Date</label><input type="date" class="form-control" id="edit-due" name="due_date" required></div>
    <div class="mb-3">
        <label class="form-label">Status</label>
        <select class="form-select" id="edit-status" name="status" required>
            <option value="Pending">Pending</option>
            <option value="Ongoing">Ongoing</option>
            <option value="Completed">Completed</option>
        </select>
    </div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-comsa"><i class="ri-save-line me-1"></i> Save Changes</button>
</div>
</form>
</div></div>
</div>

<!-- Delete Event Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
<div class="modal-dialog modal-sm"><div class="modal-content">
<form method="GET" action="../functions/delete_events.php">
<input type="hidden" name="id" id="delete-id">
<div class="modal-header text-dark">
    <h5 class="modal-title" id="deleteModalLabel"><i class="ri-alert-line me-2"></i> Confirm Delete</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <p>Are you sure you want to permanently delete this event? This action cannot be undone.</p>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-danger"><i class="ri-delete-bin-line me-1"></i> Delete Event</button>
</div>
</form>
</div></div>
</div>

<!-- Printed Modal -->
<div class="modal fade" id="printedModal" tabindex="-1" aria-labelledby="printedModalLabel" aria-hidden="true">
<div class="modal-dialog modal-sm"><div class="modal-content">
<form method="POST" action="../functions/update_printed.php">
<input type="hidden" name="id" id="printed-id">
<div class="modal-header text-dark">
    <h5 class="modal-title" id="printedModalLabel"><i class="ri-printer-line me-2"></i> Printed Checklist</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <p class="text-muted small mb-3">Mark the documents that have been printed.</p>
    
    <div class="mb-2">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="sas_f6" id="printed-sas" role="switch"> 
            <label class="form-check-label" for="printed-sas">SAS F6</label>
        </div>
    </div>
    <div class="mb-2">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="transmittal" id="printed-trans" role="switch"> 
            <label class="form-check-label" for="printed-trans">Transmittal</label>
        </div>
    </div>
    <div class="mb-2">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="invitation" id="printed-inv" role="switch"> 
            <label class="form-check-label" for="printed-inv">Invitation</label>
        </div>
    </div>
    <div class="mb-2">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="endorsement" id="printed-end" role="switch"> 
            <label class="form-check-label" for="printed-end">Endorsement</label>
        </div>
    </div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-comsa">Save Printed Status</button>
</div>
</form>
</div>
</div>
</div>

<!-- Signed Modal -->
<div class="modal fade" id="signedModal" tabindex="-1" aria-labelledby="signedModalLabel" aria-hidden="true">
<div class="modal-dialog modal-sm"><div class="modal-content">
<form method="POST" action="../functions/update_signed.php">
<input type="hidden" name="id" id="signed-id">
<div class="modal-header text-dark">
    <h5 class="modal-title" id="signedModalLabel"><i class="ri-mark-pen-line me-2"></i> Signed Checklist</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <p class="text-muted small mb-3">Mark the documents that have been signed.</p>
    
    <div class="mb-2">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="sas_f6" id="signed-sas" role="switch"> 
            <label class="form-check-label" for="signed-sas">SAS F6</label>
        </div>
    </div>
    <div class="mb-2">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="transmittal" id="signed-trans" role="switch"> 
            <label class="form-check-label" for="signed-trans">Transmittal</label>
        </div>
    </div>
    <div class="mb-2">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="invitation" id="signed-inv" role="switch"> 
            <label class="form-check-label" for="signed-inv">Invitation</label>
        </div>
    </div>
    <div class="mb-2">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="endorsement" id="signed-end" role="switch"> 
            <label class="form-check-label" for="signed-end">Endorsement</label>
        </div>
    </div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-comsa">Save Signed Status</button>
</div>
</form>
</div></div>
</div>
        <!-- End Page Content Wrapper -->

    </div>
    <!-- End Wrapper -->

    <!-- 💡 Bootstrap JavaScript CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
<script>

  // Fill Delete Modal
document.querySelectorAll('.delete-btn').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        document.getElementById('delete-id').value = btn.dataset.id;
    });
});

// Fill Edit Modal
document.querySelectorAll('.edit-btn').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        document.getElementById('edit-id').value = btn.dataset.id;
        document.getElementById('edit-type').value = btn.dataset.type;
        document.getElementById('edit-title').value = btn.dataset.title;
        document.getElementById('edit-due').value = btn.dataset.due;
        document.getElementById('edit-status').value = btn.dataset.status;
    });
});

// Fill Printed Modal
document.querySelectorAll('.printed-btn').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        document.getElementById('printed-id').value = btn.dataset.id;
        document.getElementById('printed-sas').checked = btn.dataset.sas == 1;
        document.getElementById('printed-trans').checked = btn.dataset.trans == 1;
        document.getElementById('printed-inv').checked = btn.dataset.inv == 1;
        document.getElementById('printed-end').checked = btn.dataset.end == 1;
    });
});

// Fill Signed Modal
document.querySelectorAll('.signed-btn').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        document.getElementById('signed-id').value = btn.dataset.id;
        document.getElementById('signed-sas').checked = btn.dataset.sas == 1;
        document.getElementById('signed-trans').checked = btn.dataset.trans == 1;
        document.getElementById('signed-inv').checked = btn.dataset.inv == 1;
        document.getElementById('signed-end').checked = btn.dataset.end == 1;
    });
});
</script>

</body>
</html>