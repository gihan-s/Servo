/**
 * Project Requirements Management JavaScript
 * Handles CRUD operations for project requirements via API calls
 */

class ProjectRequirementsManager {
    constructor(baseUrl = '/api') {
        this.baseUrl = baseUrl;
    }

    /**
     * Add a new requirement to a project
     * @param {number} projectId - The project ID
     * @param {string} requirementText - The requirement text
     * @returns {Promise<Object>} Response from server
     */
    async addRequirement(projectId, requirementText) {
        try {
            const response = await fetch(`${this.baseUrl}project/addRequirement`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    project_id: projectId,
                    requirement_text: requirementText
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Error adding requirement:', error);
            return {
                success: false,
                message: 'Failed to add requirement: ' + error.message
            };
        }
    }

    /**
     * Get all requirements for a project
     * @param {number} projectId - The project ID
     * @returns {Promise<Object>} Response from server with requirements array
     */
    async getRequirements(projectId) {
        try {
            const response = await fetch(`${this.baseUrl}project/getRequirements?project_id=${projectId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Error fetching requirements:', error);
            return {
                success: false,
                requirements: [],
                message: 'Failed to fetch requirements: ' + error.message
            };
        }
    }

    /**
     * Update a requirement
     * @param {number} requirementId - The requirement ID
     * @param {string} requirementText - The updated requirement text
     * @returns {Promise<Object>} Response from server
     */
    async updateRequirement(requirementId, requirementText) {
        try {
            const response = await fetch(`${this.baseUrl}project/updateRequirement`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    requirement_id: requirementId,
                    requirement_text: requirementText
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Error updating requirement:', error);
            return {
                success: false,
                message: 'Failed to update requirement: ' + error.message
            };
        }
    }

    /**
     * Delete a requirement
     * @param {number} requirementId - The requirement ID
     * @returns {Promise<Object>} Response from server
     */
    async deleteRequirement(requirementId) {
        try {
            const response = await fetch(`${this.baseUrl}project/deleteRequirement`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    requirement_id: requirementId
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            console.error('Error deleting requirement:', error);
            return {
                success: false,
                message: 'Failed to delete requirement: ' + error.message
            };
        }
    }
}

// Initialize the manager globally
const projectRequirementsManager = new ProjectRequirementsManager('/');

/**
 * Initialize the requirements UI for a project
 * @param {number} projectId - The project ID
 * @param {string} containerId - The HTML element ID where requirements will be displayed
 */
async function initializeProjectRequirements(projectId, containerId = 'requirements-container') {
    const container = document.getElementById(containerId);
    if (!container) {
        console.error(`Container with ID '${containerId}' not found`);
        return;
    }

    // Load existing requirements
    await loadRequirements(projectId, containerId);

    // Setup form submission for adding new requirements
    const form = document.getElementById('requirement-form');
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const input = document.getElementById('requirement-input');
            const requirementText = input.value.trim();

            if (requirementText) {
                const result = await projectRequirementsManager.addRequirement(projectId, requirementText);
                if (result.success) {
                    input.value = '';
                    await loadRequirements(projectId, containerId);
                    showNotification('Requirement added successfully', 'success');
                } else {
                    showNotification(result.message || 'Failed to add requirement', 'error');
                }
            }
        });
    }
}

/**
 * Load and display requirements for a project
 * @param {number} projectId - The project ID
 * @param {string} containerId - The HTML element ID where requirements will be displayed
 */
async function loadRequirements(projectId, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const result = await projectRequirementsManager.getRequirements(projectId);

    if (!result.success) {
        container.innerHTML = `<p class="error">Failed to load requirements: ${result.message}</p>`;
        return;
    }

    const requirements = result.requirements || [];

    if (requirements.length === 0) {
        container.innerHTML = '<p class="empty-message">No requirements added yet.</p>';
        return;
    }

    let html = '<ul class="requirements-list">';
    requirements.forEach(req => {
        html += `
            <li class="requirement-item" data-requirement-id="${req.Requirement_ID}">
                <div class="requirement-content">
                    <input type="text" class="requirement-text" value="${escapeHtml(req.Requirement_Text)}" readonly>
                </div>
                <div class="requirement-actions">
                    <button class="btn-edit" onclick="editRequirement(${req.Requirement_ID})">Edit</button>
                    <button class="btn-delete" onclick="deleteRequirement(${projectId}, ${req.Requirement_ID})">Delete</button>
                </div>
            </li>
        `;
    });
    html += '</ul>';

    container.innerHTML = html;
}

/**
 * Edit a requirement
 * @param {number} requirementId - The requirement ID
 */
function editRequirement(requirementId) {
    const item = document.querySelector(`[data-requirement-id="${requirementId}"]`);
    if (!item) return;

    const input = item.querySelector('.requirement-text');
    const isEditing = input.hasAttribute('readonly');

    if (isEditing) {
        // Enter edit mode
        input.removeAttribute('readonly');
        input.focus();
        item.classList.add('editing');

        // Replace edit button with save button
        const editBtn = item.querySelector('.btn-edit');
        editBtn.textContent = 'Save';
        editBtn.onclick = () => saveRequirement(requirementId);
    }
}

/**
 * Save an edited requirement
 * @param {number} requirementId - The requirement ID
 */
async function saveRequirement(requirementId) {
    const item = document.querySelector(`[data-requirement-id="${requirementId}"]`);
    if (!item) return;

    const input = item.querySelector('.requirement-text');
    const requirementText = input.value.trim();

    if (!requirementText) {
        showNotification('Requirement text cannot be empty', 'error');
        return;
    }

    const result = await projectRequirementsManager.updateRequirement(requirementId, requirementText);

    if (result.success) {
        input.setAttribute('readonly', '');
        item.classList.remove('editing');
        const editBtn = item.querySelector('.btn-edit');
        editBtn.textContent = 'Edit';
        editBtn.onclick = () => editRequirement(requirementId);
        showNotification('Requirement updated successfully', 'success');
    } else {
        showNotification(result.message || 'Failed to update requirement', 'error');
    }
}

/**
 * Delete a requirement
 * @param {number} projectId - The project ID
 * @param {number} requirementId - The requirement ID
 */
async function deleteRequirement(projectId, requirementId) {
    if (!confirm('Are you sure you want to delete this requirement?')) {
        return;
    }

    const result = await projectRequirementsManager.deleteRequirement(requirementId);

    if (result.success) {
        await loadRequirements(projectId, 'requirements-container');
        showNotification('Requirement deleted successfully', 'success');
    } else {
        showNotification(result.message || 'Failed to delete requirement', 'error');
    }
}

/**
 * Show a notification message to the user
 * @param {string} message - The message to display
 * @param {string} type - The type of notification ('success', 'error', 'info')
 */
function showNotification(message, type = 'info') {
    // Create a simple alert or use a custom notification system
    // This is a basic implementation using browser alert
    // You can replace this with a custom notification UI
    console.log(`[${type.toUpperCase()}] ${message}`);

    // Optional: Create and display a notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background-color: ${type === 'success' ? '#4caf50' : type === 'error' ? '#f44336' : '#2196f3'};
        color: white;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        z-index: 10000;
        max-width: 400px;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}

/**
 * Escape HTML special characters
 * @param {string} text - Text to escape
 * @returns {string} Escaped text
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
