<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'inc/header.php'; 
include 'admin-panel/assets/db.php';

// Helper function to render images
function renderImages($id, $conn) {
    $query = "SELECT images FROM catering_items WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $imageNames = array_map('trim', explode(',', $row['images']));
        echo '<div class="row">';
        foreach ($imageNames as $img) {
            $imgPath = 'admin-panel/' . $img;
            echo '<div class="col-lg-3 col-md-6 mb-4">
                      <div class="catering-images">
                          <img src="' . $imgPath . '" alt="Catering Image" style="width:100%;">
                      </div>
                  </div>';
        }
        echo '</div>';
    } else {
        echo '<p>No catering items found.</p>';
    }
}

// Fetch categories dynamically from the catering_items table
function getCategories($conn) {
    $query = "SELECT DISTINCT catering_title, id FROM catering_items";
    $result = $conn->query($query);
    $tabs = [];

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $slug = strtolower(str_replace(' ', '-', $row['catering_title']));
            $tabs[$slug] = [
                'title' => $row['catering_title'],
                'id' => $row['id']
            ];
        }
    }
    return $tabs;
}

$tabs = getCategories($conn);

?>

<!-- BACKGROUND -->
<div class="container-fluid p-0">
    <div class="bg-green">
        <h1>Meniu</h1>
    </div>
</div>

<!-- TABS -->
<div class="container page-container">
    <div class="tab-container">
        <ul class="nav nav-pills custom-nav" id="myTab" role="tablist">
            <?php
            $first = true;
            foreach ($tabs as $slug => $tab) {
                $active = $first ? 'active' : '';
                echo '<li class="nav-item items-space" role="presentation">
                        <a class="nav-link ' . $active . '" id="' . $slug . '-tab" data-bs-toggle="pill" href="#' . $slug . '" role="tab" aria-controls="' . $slug . '" aria-selected="true">' . $tab['title'] . '</a>
                      </li>';
                $first = false;
            }
            ?>
        </ul>
    </div>

    <div class="tab-content mt-0">
        <?php
        $first = true;
        foreach ($tabs as $slug => $tab) {
            $active = $first ? 'show active' : '';
            echo '<div class="tab-pane fade ' . $active . '" id="' . $slug . '" role="tabpanel" aria-labelledby="' . $slug . '-tab">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="catering">
                                <h1>' . $tab['title'] . '</h1>
                                <p>Oferim meniuri rapide, delicioase și echilibrate, perfecte pentru pauza de prânz la birou. Fie că organizezi o ședință, un eveniment corporate sau vrei să-ți surprinzi echipa cu ceva bun, livrăm preparate proaspete direct la sediu.</p>
                                <a href="#"><button class="download-menu">Descarcă meniul</button></a>
                            </div>';
            renderImages($tab['id'], $conn);
            echo '<div class="catering mt-0 catering-bottom">
                    <p>Pizza artizanală, salate fresh, bruschete și deserturi – totul gata de servit, fără bătăi de cap. Alegerea noastră de bufeturi și tăvi este o preacunoscută înlesnire pentru orice adunare.</p>
                  </div>
                </div>
              </div>
            </div>';
            $first = false;
        }
        ?>
    </div>
</div>

<?php include 'inc/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
