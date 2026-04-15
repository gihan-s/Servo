<?php
/**
 * Project Requirements Component
 * 
 * This component displays and manages project requirements.
 * Include this file in your project view with the project ID.
 * 
 * Usage:
 * <?php 
 *     $projectId = 123; // Your project ID
 *     include __DIR__ . '/requirements-component.php'; 
 * ?>
 */

// Make sure $projectId is defined
if (!isset($projectId)) {
    echo '<p class="error">Error: Project ID not provided</p>';
    return;
}

$projectId = (int)$projectId;

if ($projectId <= 0) {
    echo '<p class="error">Error: Invalid project ID</p>';
    return;
}
?>

<div class="requirements-container">
    <div class="requirements-header">
        <h3>Project Requirements</h3>
    </div>

    <!-- Form to add new requirements -->
    <form id="requirement-form">
        <input 
            type="text" 
            id="requirement-input" 
            placeholder="Enter a new requirement..." 
            maxlength="512"
            required
        >
        <button type="submit">Add Requirement</button>
    </form>

    <!-- Container for requirements list -->
    <div id="requirements-container">
        <p class="empty-message">Loading requirements...</p>
    </div>
</div>

<script>
// Initialize requirements manager when component is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize with the project ID
    initializeProjectRequirements(<?php echo $projectId; ?>, 'requirements-container');
});
</script>
