<?php
namespace App\Models;

use Core\Model;

class FormDefinition extends Model
{
    public function createInput(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO Post_Inputs (Title, Type, Input_Name, Default_Value, Placeholder) VALUES (?,?,?,?,?)');
        $stmt->execute([$data['Title'], $data['Type'], $data['Input_Name'], $data['Default_Value'] ?? null, $data['Placeholder'] ?? null]);
        return (int)$this->db->lastInsertId();
    }

    public function linkToCategory(int $inputId, int $categoryId): int
    {
        $stmt = $this->db->prepare('INSERT INTO Form_Structure (Input_ID, Category_ID) VALUES (?,?)');
        $stmt->execute([$inputId, $categoryId]);
        return (int)$this->db->lastInsertId();
    }

    public function addOption(int $structureId, string $title, string $value): int
    {
        $stmt = $this->db->prepare('INSERT INTO Selection_Options (Title, Value, Structure_ID) VALUES (?,?,?)');
        $stmt->execute([$title, $value, $structureId]);
        return (int)$this->db->lastInsertId();
    }

    public function savePostData(int $postId, int $inputId, string $value): int
    {
        $stmt = $this->db->prepare('INSERT INTO Post_Data (Input_ID, Value, Post_ID) VALUES (?,?,?)');
        $stmt->execute([$inputId, $value, $postId]);
        return (int)$this->db->lastInsertId();
    }
}


