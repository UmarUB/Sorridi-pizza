<?php
include('assets/db.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to fetch catering items from the database
function getCateringItems($conn) {
    $query = "SELECT id, catering_title, catering_detail FROM catering_items"; // Ensure to select the ID for editing
    $result = $conn->query($query);
    $cateringItems = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $cateringItems[] = $row;
        }
    }
    return $cateringItems;
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (int)$_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM catering_items WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    
    if ($stmt->execute()) {
        header("Location: catering.php?deleted=1");
        exit();
    } else {
        die("Error deleting record: " . $conn->error);
    }
}
$cateringItems = getCateringItems($conn); // Fetch catering items
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet" />
    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/55bd3bbc70.js" crossorigin="anonymous"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/sidebar.css" />
    <title>Sorridi Pizza</title>
</head>
<body>
    <a href="new-catering.php" class="btn btn-primary">Add New Catering</a>
    <div class="sidebar">
        <div class="logo-details">
            <a href="dashboard.php">
                <img src="assets/img/logo.png" />
            </a>
            <span class="logo_name">Sorridi Pizza</span>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php"><i class="fas fa-grip"></i><span class="link_name">Tabloul de bord</span></a></li>
            <li><a href="comenzilor.php"><i class="fas fa-file-alt"></i><span class="link_name">Gestionarea comenzilor</span></a></li>
            <li><a href="messages-pic-table.php"><i class="fas fa-utensils"></i><span class="link_name">Gestionarea meniului</span></a></li>
            <li class="active"><a href="catering.php"><i class="fas fa-hamburger"></i><span class="link_name">Catering</span></a></li>
            <li><a href="clients.php"><i class="fas fa-user"></i><span class="link_name">Gestionarea clienților</span></a></li>
            <li><a href="messages.php"><i class="fas fa-envelope"></i><span class="link_name">Mesaje</span></a></li>
            <li><a href="promotions.php"><i class="fas fa-award"></i><span class="link_name">Promoții/Reduceri</span></a></li>
            <li><a href="notifications.php"><i class="fas fa-bell"></i><span class="link_name">Notificări</span></a></li>
            <li><a href="setari.php"><i class="fas fa-cog"></i><span class="link_name">Setări</span></a></li>
        </ul>
        <div class="bottom-links">
            <ul class="nav-links">
                <li data-bs-toggle="modal" data-bs-target="#logoutmodal"><a href="#"><i class="fas fa-sign-out-alt"></i><span class="link_name">Deconectare</span></a></li>
            </ul>
        </div>
    </div>
    <section class="home-section">
        <div class="container-fluid">
            <div class="row">
                <div class="home-content fixed-top">
                    <div class="col-md-8" style="padding: 0px 12px; display: flex">
                        <i class="nav-dash-logo">
                            <a href="dashboard.php">
                                <img src="assets/img/logo.png" />
                            </a>
                        </i>
                        <i class="bx bx-menu"></i>
                    </div>
                    <div class="col-md-4 prof-sec" style="padding: 0px 12px">
                        <div class="nav-profile">
                            <a href="notifications.php"><i class="fa-regular fa-bell"></i></a>
                            <a href="#" class="d-flex align-items-center">
                                <img src="assets/img/profile-img.png" alt="" />
                                <h1>Sebastian &nbsp;<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M18 9L12 15L6 9" stroke="#060808" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></h1>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- =============================== MAIN CONTENT START HERE =========================== -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="search-bar-pg">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active" aria-current="page">Tabloul de bord</li>
                                <li class="breadcrumb-item"><a href="#" class="breadcrumb-page-link">Catering</a></li>
                            </ol>
                        </nav>
                        <div class="row mt-3">
                            <div class="col-lg-6 col-md-4">
                                <h2 class="label-title">Catering</h2>
                            </div>
                            <div class="col-lg-6 col-md-8">
                                <div class="catering-wrapper">
                                    <div class="search-btn position-relative">
                                        <input type="text" class="form-control pe-5" id="plholder" placeholder="Search order ID" />
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                        </svg>
                                    </div>
                                    <div class="add-butn">
                                        <a href="new-catering.php">
                                            <button type="button" class="add-cart-butn">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2" />
                                                </svg>
                                                Adaugă Catering Nou
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php foreach ($cateringItems as $item): ?>
                            <div class="col-md-6">
                                <div class="catreing-wrap">
                                    <div class="catering-card">
                                        <div class="catering-content">
                                            <h3><?php echo htmlspecialchars($item['catering_title']); ?></h3>
                                            <p><?php echo htmlspecialchars($item['catering_detail']); ?></p>
                                            <div class="catering-btns">
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="delete_id" value="<?php echo $item['id']; ?>">
                                                    <button type="submit" class="publish-btn" onclick="return confirm('Are you sure you want to delete this catering item?');">Unpublish</button>
                                                </form>
                                                <a href="edit.php?id=<?php echo $item['id']; ?>">
                                                    <button type="button" class="edit-cart-btn">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                        </svg>
                                                        Edit Catering
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="assets/js/script.js"></script>
    <!-- logout modal -->
    <div class="modal fade" id="logoutmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content msg-content-sec">
                <div class="modal-body p-0">
                    <h2>Are you sure you want to logout?</h2>
                    <div class="row">
                        <div class="col-md-12 logout-btns-sec">
                            <div class="modal-btns-sec">
                                <button class="btn reply-btn" data-bs-dismiss="modal" aria-label="Close">No, Cancel</button>
                                <button class="btn read-btn">Yes, Sure</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
