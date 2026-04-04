<?php

require_once __DIR__ . '/../core/Database.php';

class PostSkillsModel extends Database
{
    public function getPostSkills($postId)
    {
        $query = "SELECT s.Skill_ID, s.Skill
                      FROM post_need_skills pns 
                      JOIN skills s ON pns.Skill_ID = s.Skill_ID 
                      WHERE pns.Post_ID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $postId);  // 'i' means integer
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addPostSkill($postId, $skillId)
    {
        try {
            $query = "INSERT INTO post_need_skills (Post_ID, Skill_ID) VALUES (?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ii', $postId, $skillId);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log('Error in addPostSkill: ' . $e->getMessage());
            return false;
        }
    }

    public function deletePostSkills($postId): bool
    {
        $sql = "DELETE FROM post_need_skills WHERE Post_ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $postId);
        return $stmt->execute();
    }

}