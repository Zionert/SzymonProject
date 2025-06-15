<?php
session_start();
require_once 'server/connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $user_id = $_SESSION['user_id'];

    try {
        // Update order status to paid
        $stmt = $conn->prepare("UPDATE orders SET order_status = 'paid' WHERE order_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $order_id, $user_id);
        
        if ($stmt->execute()) {
            // Clear all payment-related session data
            if (isset($_SESSION['order_id'])) unset($_SESSION['order_id']);
            if (isset($_SESSION['total'])) unset($_SESSION['total']);
            if (isset($_SESSION['cart'])) unset($_SESSION['cart']);
            
            // Redirect to success page
            header('Location: order_details.php?order_status=success');
            exit();
        } else {
            throw new Exception("Error updating order");
        }
    } catch (Exception $e) {
        error_log("Error in place_order.php: " . $e->getMessage());
        header('Location: payment.php?order_status=error');
        exit();
    }
} else {
    header('Location: payment.php');
    exit();
}
?> 