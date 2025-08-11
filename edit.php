<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'assets/db.php';

function normalizeImageArray($csv) {
    $arr = array_values(array_filter(array_map('trim', explode(',', (string)$csv))));
    while (count($arr) < 4) $arr[] = '';
    return array_slice($arr, 0, 4);
}

function resolveImageSrc($img) {
    if (empty($img)) return '';
    // Absolute URLs
    if (preg_match('#^(https?:)?//#', $img)) return $img;

    // If it starts with assets/, uploaded_images/, or any already-public path → return as is
    if (preg_match('#^(assets/|uploaded_images/)#', $img)) {
        return $img;
    }

    // If prefixed with admin-panel/ keep as is
    if (strpos($img, 'admin-panel/') === 0) return $img;

    // Try common locations
    $candidates = [
        'admin-panel/' . $img,
        $img,
        'admin-panel/assets/img/' . basename($img),
        'admin-panel/uploaded_images/' . basename($img)
    ];
    foreach ($candidates as $c) {
        $serverPath = __DIR__ . '/' . str_replace('admin-panel/', '', $c);
        if (file_exists($serverPath)) return $c;
    }

    return $img; // fallback
}

function safeUnlink($img) {
    if (empty($img)) return false;
    $candidates = [
        (__DIR__ . '/' . str_replace('admin-panel/', '', $img)),
        (__DIR__ . '/assets/img/' . basename($img)),
        (__DIR__ . '/uploaded_images/' . basename($img)),
        (__DIR__ . '/' . basename($img))
    ];
    foreach ($candidates as $p) {
        if (file_exists($p) && is_file($p)) {
            return unlink($p);
        }
    }
    return false;
}

if (!isset($_GET['id'])) die('Missing ID');
$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM catering_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$item = $res->fetch_assoc();
if (!$item) die('Item not found');

$imageNames = normalizeImageArray($item['images']);
$uploadDirWeb = 'uploaded_images/';
$uploadDirFs  = __DIR__ . '/uploaded_images/';

if (!is_dir($uploadDirFs)) {
    @mkdir($uploadDirFs, 0755, true);
}

$messages = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['catering_title'] ?? '';
    $detail = $_POST['catering_detail'] ?? '';
    $more = $_POST['more_details'] ?? '';
    $finalImages = $imageNames;

    for ($i = 0; $i < 4; $i++) {
        $field = "image_$i";
        if (!empty($_FILES[$field]['name']) && $_FILES[$field]['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES[$field]['error'] === UPLOAD_ERR_OK) {
                $tmp = $_FILES[$field]['tmp_name'];
                if (@getimagesize($tmp) && $_FILES[$field]['size'] <= 6 * 1024 * 1024) {
                    $ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
                    $unique = time() . '_' . uniqid() . '.' . $ext;
                    $targetFs = $uploadDirFs . $unique;
                    $targetWeb = $uploadDirWeb . $unique;
                    if (move_uploaded_file($tmp, $targetFs)) {
                        safeUnlink($finalImages[$i]);
                        $finalImages[$i] = $targetWeb;
                        $messages[] = "Replaced image slot " . ($i + 1);
                    }
                } else {
                    $messages[] = "Invalid or too large file for image " . ($i + 1);
                }
            }
        }
    }

    $imageCsv = implode(',', $finalImages);
    $u = $conn->prepare("UPDATE catering_items SET catering_title = ?, catering_detail = ?, more_details = ?, images = ? WHERE id = ?");
    $u->bind_param("ssssi", $title, $detail, $more, $imageCsv, $id);
    if ($u->execute()) {
        header("Location: edit.php?id={$id}");
        exit;
    } else {
        $messages[] = "DB update failed: " . $conn->error;
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Catering</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h2>Edit Catering Item (#<?= htmlspecialchars($id) ?>)</h2>
    <?php foreach ($messages as $m): ?>
        <div class="alert alert-info"><?= htmlspecialchars($m) ?></div>
    <?php endforeach; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Title</label>
            <input name="catering_title" class="form-control" value="<?= htmlspecialchars($item['catering_title']) ?>">
        </div>
        <div class="mb-3">
            <label>Detail</label>
            <textarea name="catering_detail" class="form-control"><?= htmlspecialchars($item['catering_detail']) ?></textarea>
        </div>
        <div class="mb-3">
            <label>More details</label>
            <textarea name="more_details" class="form-control"><?= htmlspecialchars($item['more_details']) ?></textarea>
        </div>
        <h5>Current images</h5>
        <div class="row">
            <?php
            $imageNames = normalizeImageArray($item['images']);
            for ($i=0; $i<4; $i++):
                $img = $imageNames[$i] ?? '';
                $src = resolveImageSrc($img);
            ?>
            <div class="col-md-3 text-center mb-3">
                <?php if ($src): ?>
                    <img src="<?= htmlspecialchars($src) ?>" class="img-fluid border" style="max-height:160px;">
                <?php else: ?>
                    <div class="border bg-light p-4">No image</div>
                <?php endif; ?>
                <input type="file" name="image_<?= $i ?>" class="form-control mt-2" accept="image/*">
                <div class="small text-muted"><?= htmlspecialchars($img) ?></div>
            </div>
            <?php endfor; ?>
        </div>
        <button class="btn btn-primary">Save changes</button>
        <a href="catering.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
