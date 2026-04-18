<?php

require_once __DIR__ . '/../core/Database.php';

class ReviewModel extends Database
{
    public function getAverageRatingByPostIds(array $postIds): ?float
    {
        $postIds = array_values(array_filter(array_map('intval', $postIds), function ($id) {
            return $id > 0;
        }));

        if (empty($postIds)) {
            return null;
        }

        $placeholders = implode(',', array_fill(0, count($postIds), '?'));
        $types = str_repeat('i', count($postIds));
        $sql = "SELECT ROUND(AVG(r.Rating), 1) AS average_rating
                FROM reviews r
                INNER JOIN project pr ON pr.Project_ID = r.Project_ID
                WHERE pr.Post_ID IN ($placeholders)";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('ReviewModel::getAverageRatingByPostIds prepare: ' . $this->conn->error);
            return null;
        }

        $stmt->bind_param($types, ...$postIds);
        if (!$stmt->execute()) {
            error_log('ReviewModel::getAverageRatingByPostIds exec: ' . $stmt->error);
            $stmt->close();
            return null;
        }

        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return isset($row['average_rating']) ? (float) $row['average_rating'] : null;
    }
}