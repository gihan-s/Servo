<?php

require_once __DIR__ . '/../core/Database.php';

class SkillsModel extends Database
{

    public function getSkillsByPostId($postId)
    {
        $stmt = $this->conn->prepare("SELECT * FROM post_need_skills WHERE Post_ID = ?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getByCategoryId(int $categoryId): array
    {
        $sql = "SELECT s.Skill_ID, s.Skill
                FROM skills s
                INNER JOIN category c ON c.Category_ID = s.Category_ID
                WHERE c.Category_ID = ?
                ORDER BY s.Skill";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return [];
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?: [];
    }

    public function getAllSkills($SkillID)
    {
        $stmt = $this->conn->prepare("SELECT * FROM skills WHERE Skill_ID = ?");
        $stmt->bind_param("i", $SkillID);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getIdBySkill(string $skill): ?int
    {
        $stmt = $this->conn->prepare("SELECT Skill_ID FROM skills WHERE Skill = ? LIMIT 1");
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
            "INSERT INTO post_need_skills (Post_ID, Skill_ID) VALUES (?, ?)"
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


    public function getSkillsByProviderID(int $Provider_ID): array
    {

        if (empty($Provider_ID)) {
            return [];
        }

        $stmt = $this->conn->prepare(
            "SELECT 
                pc.ID,
                s.Skill
            FROM provider_categories pc
            JOIN provider_categories_has_skills pchs 
                ON pchs.Provider_Categories_ID = pc.ID
            JOIN skills s 
                ON s.Skill_ID = pchs.Skills_Skill_ID
            WHERE pc.Provider_ID = ?
            ORDER BY pc.ID"
        );

        $stmt->bind_param("i", $Provider_ID);
        $stmt->execute();

        $result = $stmt->get_result();

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $provcategoryId = $row['ID'];
            $skill = $row['Skill'];

            if (!isset($data[$provcategoryId])) {
                $data[$provcategoryId] = [];
            }

            $data[$provcategoryId][] = $skill;
        }

        $stmt->close();

        return $data;
    }
}
