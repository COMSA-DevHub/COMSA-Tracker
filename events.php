<?php
session_start();
// Regenerate session ID after login to prevent fixation attacks
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}
// Check if user is logged in and is admin
if (!isset($_SESSION['student_number']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}
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
    <link rel="stylesheet" href="styles.css">
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

        /* 3. Sidebar Styles: fixed, full height, dark background */
        #sidebar-wrapper {
            min-height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
            background-color: white; /* Darker than the top nav */
            transition: all 0.3s;
        }
        
        /* Style for the logo/brand area within the sidebar */
        .sidebar-heading {
            padding: 1.5rem 1rem;
            font-size: 1.2rem;
            color: #f8f9fa;
            font-weight: bold;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Style for the navigation links in the sidebar */
        .sidebar-links .list-group-item {
            border: none;
            padding: 0.8rem 1.5rem;
            background-color: transparent;
            color: #000000ff; /* Light grey text */
            transition: background-color 0.2s;
        }

        .sidebar-links .list-group-item:hover,
        .sidebar-links .list-group-item.active {
            color: #ffffff;
            background-color: #09b003; /* Slightly lighter dark on hover/active */
        }
        
        .sidebar-links .list-group-item.active {
            border-left: 20px solid #007a00; /* Highlight active link */
        }

        /* 4. Content Area Styles */
        #page-content-wrapper {
            flex-grow: 1;
            width: 100%;
            /* Offset content to make space for the desktop sidebar */
            padding-left: 250px; 
            padding-top: 70px; /* Padding for the fixed top header */
        }

        .top-header {
            width: 100%;
            padding-left: 250px;
            z-index: 1000;
        }
        
        /* 5. Responsive / Mobile Toggling */
        @media (max-width: 991.98px) { /* Adjusting for lg breakpoint */
            /* Hide the sidebar completely on smaller screens by default */
            #sidebar-wrapper {
                margin-left: -250px;
            }
            /* Content takes full width on mobile */
            #page-content-wrapper,
            .top-header {
                padding-left: 0;
            }
            /* When the toggled class is present, slide the sidebar in */
            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
            }
            /* Push the content over when the sidebar is open */
            #wrapper.toggled #page-content-wrapper {
                margin-left: 250px;
            }
        }
        
        /* Adjust for overall responsiveness */
        .container-fluid {
            max-width: 100%; /* Use full width in the dashboard layout */
        }
    </style>
</head>
<body>
    
    <!-- Overall Layout Wrapper -->
    <div id="wrapper">
        
        <!-- 🌟 SIDEBAR/NAVIGATION 🌟 -->
        <div id="sidebar-wrapper" class="shadow-lg border-right">
            <!-- Sidebar Heading now includes the close button for mobile -->
            <div class="sidebar-heading d-flex justify-content-between align-items-center">
                <div class="text-center">
                <img src="img/secondary_logo.png" alt="COMSA Logo" style="width: 200px;">
             </div>
                <!-- Close button (visible only on mobile) -->
                <button class="btn text-black d-lg-none p-0" id="sidebarClose" aria-label="Close menu">
                    <i class="bi bi-x-lg fs-4"></i>
                </button>
            </div>
            
            <div class="list-group list-group-flush sidebar-links">
                <!-- Navigation Items -->
                <a href="admin_dashboard.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a href="events.php" class="list-group-item list-group-item-action active">
                    <i class="bi bi-calendar-week me-2"></i> Events
                </a>
                <a href="tasks.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-table me-2"></i> Tasks
                </a>
                <a href="users.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-person me-2"></i> Users
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
        <!-- 🌟 END SIDEBAR 🌟 -->

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

            <!-- Main Content Area -->
            <div class="container-fluid">
                <h2 class="">Events</h2>
                <p class="lead text-muted"></p>
                <hr class="mb-4">

                <form action="/submit" method="post">
                    <div class="table-responsive">
                        <!-- Table remains the same, but centered in the main content area -->
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="text-center">TYPE</th>
                                    <th scope="col" class="text-center">TITLE</th>
                                    <th scope="col" class="text-center">SAS F6</th>
                                    <th scope="col" class="text-center">TRANSMITTAL</th>
                                    <th scope="col" class="text-center">INVITATION</th>
                                    <th scope="col" class="text-center">ENDORSEMENT</th>
                                    <th scope="col" class="text-center">DUE DATE</th>
                                    <th scope="col" class="text-center">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Task A</td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r1c2" id="r1c2"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r1c3" id="r1c3"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r1c4" id="r1c4"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r1c5" id="r1c5"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r1c6" id="r1c6"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r1c7" id="r1c7"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r1c8" id="r1c8"></div></td>
                                </tr>
                                <tr>
                                    <td>Task B</td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r2c2" id="r2c2"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r2c3" id="r2c3"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r2c4" id="r2c4"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r2c5" id="r2c5"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r2c6" id="r2c6"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r2c7" id="r2c7"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r2c8" id="r2c8"></div></td>
                                </tr>
                                <tr>
                                    <td>Task C</td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r3c2" id="r3c2"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r3c3" id="r3c3"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r3c4" id="r3c4"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r3c5" id="r3c5"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r3c6" id="r3c6"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r3c7" id="r3c7"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r3c8" id="r3c8"></div></td>
                                </tr>
                                <tr>
                                    <td>Task D</td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r4c2" id="r4c2"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r4c3" id="r4c3"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r4c4" id="r4c4"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r4c5" id="r4c5"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r4c6" id="r4c6"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r4c7" id="r4c7"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r4c8" id="r4c8"></div></td>
                                </tr>
                                <tr>
                                    <td>Task E</td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r5c2" id="r5c2"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r5c3" id="r5c3"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r5c4" id="r5c4"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r5c5" id="r5c5"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r5c6" id="r5c6"></div></td> 
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r5c7" id="r5c7"></div></td>
                                    <td class="text-center"><div class="form-check"><input class="form-check-input mx-auto" type="checkbox" name="r5c8" id="r5c8"></div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end my-4">
                        <button type="submit" class="btn btn-comsa btn-lg">Save Changes</button>
                    </div>
                </form>
            </div>
            <!-- End Main Content Area -->
        </div>
        <!-- End Page Content Wrapper -->

    </div>
    <!-- End Wrapper -->

    <!-- 💡 Bootstrap JavaScript CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- Custom JS for Sidebar Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var sidebarToggle = document.getElementById('sidebarToggle');
            var sidebarClose = document.getElementById('sidebarClose');
            var wrapper = document.getElementById('wrapper');

            // Function to toggle the sidebar (open/close)
            function toggleSidebar(e) {
                e.preventDefault();
                wrapper.classList.toggle('toggled');
            }

            // Toggle sidebar visibility on click for the hamburger button
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }
            
            // Toggle sidebar visibility on click for the close button
            if (sidebarClose) {
                sidebarClose.addEventListener('click', toggleSidebar);
            }
        });
    </script>
</body>
</html>