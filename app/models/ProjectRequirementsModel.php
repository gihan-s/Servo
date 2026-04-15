<?php

require_once __DIR__ . '/../core/Database.php';

class ProjectRequirementsModel extends Database
{
    public function addRequirement($projectId, $requirementText)
    {
        try {
            $sql = "INSERT INTO project_requirements (Project_ID, Requirement_Text) VALUES (?, ?)";
            $stmt = $this->connect()->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->connect()->error);
            }
            
            $stmt->bind_param("is", $projectId, $requirementText);
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            $requirementId = $stmt->insert_id;
            $stmt->close();
            
            return [
                'success' => true,
                'requirement_id' => $requirementId,
                'message' => 'Requirement added successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error adding requirement: ' . $e->getMessage()
            ];
        }
    }

    public function getRequirementsByProjectId($projectId)
    {
        try {
            $sql = "SELECT Requirement_ID, Project_ID, Requirement_Text FROM project_requirements WHERE Project_ID = ? ORDER BY Requirement_ID DESC";
            $stmt = $this->connect()->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->connect()->error);
            }
            
            $stmt->bind_param("i", $projectId);
            $stmt->execute();
            $result = $stmt->get_result();
            $requirements = [];
            
            while ($row = $result->fetch_assoc()) {
                $requirements[] = $row;
            }
            
            $stmt->close();
            return $requirements;
        } catch (Exception $e) {
            error_log("Error fetching requirements: " . $e->getMessage());
            return [];
        }
    }

    public function getRequirementById($requirementId)
    {
        try {
            $sql = "SELECT Requirement_ID, Project_ID, Requirement_Text FROM project_requirements WHERE Requirement_ID = ?";
            $stmt = $this->connect()->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->connect()->error);
            }
            
            $stmt->bind_param("i", $requirementId);
            $stmt->execute();
            $result = $stmt->get_result();
            $requirement = $result->fetch_assoc();
            $stmt->close();
            
            return $requirement;
        } catch (Exception $e) {
            error_log("Error fetching requirement: " . $e->getMessage());
            return null;
        }
    }

    public function updateRequirement($requirementId, $requirementText)
    {
        try {
            $sql = "UPDATE project_requirements SET Requirement_Text = ? WHERE Requirement_ID = ?";
            $stmt = $this->connect()->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->connect()->error);
            }
            
            $stmt->bind_param("si", $requirementText, $requirementId);
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            $stmt->close();
            
            return [
                'success' => true,
                'message' => 'Requirement updated successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error updating requirement: ' . $e->getMessage()
            ];
        }
    }

    public function deleteRequirement($requirementId)
    {
        try {
            $sql = "DELETE FROM project_requirements WHERE Requirement_ID = ?";
            $stmt = $this->connect()->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->connect()->error);
            }
            
            $stmt->bind_param("i", $requirementId);
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            $stmt->close();
            
            return [
                'success' => true,
                'message' => 'Requirement deleted successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error deleting requirement: ' . $e->getMessage()
            ];
        }
    }

    public function deleteRequirementsByProjectId($projectId)
    {
        try {
            $sql = "DELETE FROM project_requirements WHERE Project_ID = ?";
            $stmt = $this->connect()->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->connect()->error);
            }
            
            $stmt->bind_param("i", $projectId);
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            $stmt->close();
            
            return [
                'success' => true,
                'message' => 'All requirements deleted successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error deleting requirements: ' . $e->getMessage()
            ];
        }
    }
}
