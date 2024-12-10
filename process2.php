<?php
include 'db.php'; 

$action = $_POST['action'] ?? null;

if ($action == 'add') {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    

    $image = $_FILES['image'];
    $imageName = $image['name'];
    $imageTmpName = $image['tmp_name'];
    $imageSize = $image['size'];
    $imageError = $image['error'];
    $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageExtension, $allowedExtensions) && $imageError === 0 && $imageSize < 5000000) {
        $imageNewName = uniqid('', true) . '.' . $imageExtension;
        $imageUploadPath = 'uploads/' . $imageNewName;
        move_uploaded_file($imageTmpName, $imageUploadPath);
        

        $stmt = $conn->prepare("INSERT INTO products (name, price, category, image_url) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $name, $price, $category, $imageUploadPath);
        $stmt->execute();
        $stmt->close();

        header("Location: Adashboard.php"); 
        exit();
    } else {
        echo "Error uploading image.";
    }

} elseif ($action == 'edit') {

    $productId = $_POST['product_id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];


    $imageUrl = $_POST['current_image']; 


    if ($_FILES['image']['error'] == 0) {
        // Handle new image upload
        $image = $_FILES['image'];
        $imageName = $image['name'];
        $imageTmpName = $image['tmp_name'];
        $imageSize = $image['size'];
        $imageError = $image['error'];
        $imageExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageExtension, $allowedExtensions) && $imageError === 0 && $imageSize < 5000000) {

            $imageNewName = uniqid('', true) . '.' . $imageExtension;
            $imageUploadPath = 'uploads/' . $imageNewName;
            move_uploaded_file($imageTmpName, $imageUploadPath);


            if (file_exists($imageUrl)) {
                unlink($imageUrl); 
            }


            $imageUrl = $imageUploadPath;
        }
    }


    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, category = ?, image_url = ? WHERE id = ?");
    $stmt->bind_param('ssssi', $name, $price, $category, $imageUrl, $productId);
    $stmt->execute();
    $stmt->close();

    header("Location: Adashboard.php"); 
    exit();

} elseif ($action == 'delete') {

    $productId = $_POST['product_id'];
    

    $stmt = $conn->prepare("SELECT image_url FROM products WHERE id = ?");
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $stmt->bind_result($imageUrl);
    $stmt->fetch();
    $stmt->close();


    if (file_exists($imageUrl)) {
        unlink($imageUrl);
    }

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $stmt->close();

    header("Location: Adashboard.php"); 
    exit();
}
?>
