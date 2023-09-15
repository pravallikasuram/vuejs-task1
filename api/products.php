<?php
$conn = new mysqli("localhost", "root", "", "crud");

// API endpoint to get the list of products
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    $products = array();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode($products);
}

// API endpoint to add a new product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newProduct = json_decode(file_get_contents("php://input"), true);
    $name = $newProduct['name'];
    $description = $newProduct['description'];
    $sql = "INSERT INTO products (name, description) VALUES ('$name', '$description')";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
        echo json_encode(["message" => "Product added successfully"]);
    } else {
        echo json_encode(["error" => "Database error"]);
    }
    $stmt->close();
}

// API endpoint to delete a product
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $productId = $_GET['id'];
    $sql = "DELETE FROM products WHERE id = '$productId'";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
        echo json_encode(["message" => "Product deleted successfully"]);
    } else {
        echo json_encode(["error" => "Database error"]);
    }
    $stmt->close();
}

$conn->close();
?>
