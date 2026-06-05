<?php
header('Content-Type: application/json');
session_start();
require_once '../config/database.php'; // Path එක නිවැරදිදැයි බලන්න

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newsletter_email'])) {
    $email = filter_var(trim($_POST['newsletter_email']), FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address!']);
        exit;
    }

    try {
        // 💡 ඩේටාබේස් එකේ 'subscribers' කියලා ටේබල් එකක් තියෙනවා නම් මේ කේතය පාවිච්චි කරන්න පුළුවන්:
        /*
        $stmt = $pdo->prepare("SELECT id FROM subscribers WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            echo json_encode(['status' => 'error', 'message' => 'This email is already subscribed!']);
            exit;
        }

        $stmt = $pdo->prepare("INSERT INTO subscribers (email, subscribed_at) VALUES (?, NOW())");
        $stmt->execute([$email]);
        */

        echo json_encode(['status' => 'success', 'message' => 'Awesome! Check your inbox for premium perks. 🎉']);
        exit;

    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Server error! Please try again later.']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request!']);
    exit;
}