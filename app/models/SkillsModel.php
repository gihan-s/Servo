<?php

require_once __DIR__ . '/../core/Database.php';

class SkillsModel extends Database
{

    public function getSkillsByPostId($postId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Post_Need_Skills WHERE Post_ID = ?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getByCategoryId(int $categoryId): array
    {
        $sql = "SELECT s.Skill_ID, s.Skill
                FROM Skills s
                INNER JOIN Category c ON c.Category_ID = s.Category_ID
                WHERE c.Category_ID = ?
                ORDER BY s.Skill";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt)
            return [];
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?: [];
    }

    public function getAllSkills($SkillID)
    {
        $stmt = $this->conn->prepare("SELECT * FROM Skills WHERE Skill_ID = ?");
        $stmt->bind_param("i", $SkillID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getIdBySkill(string $skill): ?int
    {
        $stmt = $this->conn->prepare("SELECT Skill_ID FROM Skills WHERE Skill = ? LIMIT 1");
        if (!$stmt)
            return null;
        $stmt->bind_param("s", $skill);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ? (int) $row['Skill_ID'] : null;
    }

    public function insertPostSkill(int $PostID, string $Skills): int
    {
        $skills = json_decode($Skills, true);          // -> ['GraphicDesgin','Design']
        if (!is_array($skills)) {
            // Fallback if it isn't valid JSON
            $skills = array_filter(array_map('trim', explode(',', trim($Skills, "[]\"' "))));
        }

        $skills = array_values(array_unique(array_map('strval', $skills)));
        if ($PostID <= 0 || empty($skills))
            return 0;

        $stmt = $this->conn->prepare(
            "INSERT INTO Post_Need_Skills (Post_ID, Skill_ID) VALUES (?, ?)"
        );
        if (!$stmt) {
            throw new RuntimeException("Prepare failed: " . $this->conn->error);
        }

        $inserted = 0;
        foreach ($skills as $s) {
            // Accept numeric IDs directly; otherwise resolve by name
            $skillId = is_numeric($s) ? (int) $s : ($this->getIdBySkill((string) $s) ?? 0);
            if ($skillId <= 0)
                continue;

            $stmt->bind_param("ii", $PostID, $skillId);
            if ($stmt->execute()) {
                $inserted++;
            }
        }
        $stmt->close();
        return $inserted;
    }


    public function getByProviderCategoryIds(array $categoryIds): array
    {
        if (empty($categoryIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));
        $types = str_repeat('i', count($categoryIds));

        $stmt = $this->conn->prepare(
            "SELECT Provider_Categories_ID, Skill FROM Skills WHERE Provider_Categories_ID IN ($placeholders)"
        );
        $stmt->bind_param($types, ...$categoryIds);
        $stmt->execute();

        $skills = [];
        foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $row) {
            $skills[$row['Provider_Categories_ID']][] = $row["Skill"];
        }

        return $skills;
    }
}
