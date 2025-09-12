<?php
namespace App\Models;

use Core\Model;

class ProviderCategory extends Model
{
    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO Provider_Categories (Category_ID, Provider_ID, Title, Description, Default_Price) VALUES (?,?,?,?,?)');
        $stmt->execute([
            $data['Category_ID'], $data['Provider_ID'], $data['Title'] ?? null, $data['Description'] ?? null, $data['Default_Price'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function attachLocations(int $providerCategoryId, array $locationIds): void
    {
        $stmt = $this->db->prepare('INSERT INTO Provider_Categories_has_Location (Provider_Categories_ID, Location_Location_ID) VALUES (?, ?)');
        foreach ($locationIds as $loc) {
            $stmt->execute([$providerCategoryId, $loc]);
        }
    }

    public function attachSkills(int $providerCategoryId, array $skillIds): void
    {
        $stmt = $this->db->prepare('INSERT INTO Provider_Categories_has_Skills (Provider_Categories_ID, Skills_Skill_ID) VALUES (?, ?)');
        foreach ($skillIds as $sid) {
            $stmt->execute([$providerCategoryId, $sid]);
        }
    }
}


