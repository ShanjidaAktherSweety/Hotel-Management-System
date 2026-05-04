<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$staff_name = trim($_POST['staff_name'] ?? '');
$department = trim($_POST['department'] ?? '');
$review_date = trim($_POST['review_date'] ?? '');
$review_period = trim($_POST['review_period'] ?? '');
$task_completion = (int)($_POST['task_completion'] ?? 0);
$attendance_rate = (int)($_POST['attendance_rate'] ?? 0);
$service_quality = trim($_POST['service_quality'] ?? '');
$punctuality = trim($_POST['punctuality'] ?? '');
$overall_rating = trim($_POST['overall_rating'] ?? '');
$review_notes = trim($_POST['review_notes'] ?? '');

$eligible_for_reward = isset($_POST['eligible_for_reward']) ? 1 : 0;
$promotion_recommended = isset($_POST['promotion_recommended']) ? 1 : 0;
$needs_training = isset($_POST['needs_training']) ? 1 : 0;
$review_completed = isset($_POST['review_completed']) ? 1 : 0;

if (
    $staff_name === '' ||
    $department === '' ||
    $review_date === '' ||
    $review_period === '' ||
    $service_quality === '' ||
    $punctuality === '' ||
    $overall_rating === ''
) {
    jsonResponse(false, 'Please fill all required performance fields.');
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO staff_performance (
            staff_name, department, review_date, review_period,
            task_completion, attendance_rate, service_quality, punctuality,
            overall_rating, review_notes,
            eligible_for_reward, promotion_recommended, needs_training, review_completed
        ) VALUES (
            :staff_name, :department, :review_date, :review_period,
            :task_completion, :attendance_rate, :service_quality, :punctuality,
            :overall_rating, :review_notes,
            :eligible_for_reward, :promotion_recommended, :needs_training, :review_completed
        )
    ");

    $stmt->execute([
        ':staff_name' => $staff_name,
        ':department' => $department,
        ':review_date' => $review_date,
        ':review_period' => $review_period,
        ':task_completion' => $task_completion,
        ':attendance_rate' => $attendance_rate,
        ':service_quality' => $service_quality,
        ':punctuality' => $punctuality,
        ':overall_rating' => $overall_rating,
        ':review_notes' => $review_notes !== '' ? $review_notes : null,
        ':eligible_for_reward' => $eligible_for_reward,
        ':promotion_recommended' => $promotion_recommended,
        ':needs_training' => $needs_training,
        ':review_completed' => $review_completed
    ]);

    jsonResponse(true, 'Performance review saved successfully.');
} catch (Exception $e) {
    jsonResponse(false, 'Failed to save performance review: ' . $e->getMessage());
}
?>