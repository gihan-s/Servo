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

}