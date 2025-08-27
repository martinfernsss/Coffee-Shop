<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['action'])) {
        switch ($data['action']) {
            case 'add':
                if (isset($data['item'])) {
                    $item = $data['item'];
                    $_SESSION['cart'][] = $item;
                    echo json_encode(['status' => 'success', 'message' => 'Item added to cart successfully.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Invalid item data.']);
                }
                break;
            case 'remove_one':
                if (isset($data['name'])) {
                    $itemName = $data['name'];
                    $index = -1;
                    foreach ($_SESSION['cart'] as $key => $item) {
                        if ($item['name'] === $itemName) {
                            $index = $key;
                            break;
                        }
                    }
                    if ($index !== -1) {
                        array_splice($_SESSION['cart'], $index, 1);
                        echo json_encode(['status' => 'success', 'message' => 'One item removed from cart.']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Item not found.']);
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
                }
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Unknown action.']);
                break;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Action not specified.']);
    }
} 
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(['cart' => $_SESSION['cart']]);
}

?>
