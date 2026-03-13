@props([
    'modalId' => 'correctiveActionsModal',
    'title' => 'Corrective Actions Required',
    'itemType' => 'incident', // or 'complaint'
])

<!-- Corrective Actions Modal -->
<div id="{{ $modalId }}" class="corrective-actions-modal">
    <div class="corrective-actions-modal-content">
        <div class="corrective-actions-modal-header">
            <h3 class="corrective-actions-modal-title">{{ $title }}</h3>
            <button type="button" class="corrective-actions-close" onclick="closeCorrectiveActionsModal()">&times;</button>
        </div>
        <div class="corrective-actions-modal-body">
            <div class="corrective-actions-notice">
                <div class="notice-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.98-.833-2.75 0L3.982 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="notice-content">
                    <h4>Corrective Actions Required</h4>
                    <p>Before closing or completing this {{ $itemType }}, you must provide corrective actions taken to prevent similar occurrences in the future.</p>
                </div>
            </div>

            <form class="corrective-actions-form" id="correctiveActionsForm">
                @csrf
                <label for="correctiveActionsInput" class="corrective-actions-label">
                    Corrective Actions Taken <span class="required">*</span>
                </label>
                <textarea
                    id="correctiveActionsInput"
                    class="corrective-actions-input"
                    placeholder="Please describe the corrective actions taken to prevent similar incidents/complaints from occurring again..."
                    required
                    maxlength="2000"
                    rows="6"
                ></textarea>
                <div class="character-count">
                    <span id="characterCount">0</span>/2000 characters
                </div>

                <div class="corrective-actions-buttons">
                    <button type="button" class="btn-cancel" onclick="closeCorrectiveActionsModal()">
                        Cancel
                    </button>
                    <button type="submit" class="btn-submit" id="correctiveActionsSubmitBtn">
                        Save & Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Corrective Actions Modal Styles */
    .corrective-actions-modal {
        display: none;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(3px);
        animation: fadeIn 0.3s ease;
    }

    .corrective-actions-modal-content {
        position: relative;
        background-color: white;
        margin: 3% auto;
        padding: 0;
        border-radius: 16px;
        width: 90%;
        max-width: 700px;
        max-height: 90vh;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        animation: modalSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        overflow: hidden;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .corrective-actions-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 28px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }

    .corrective-actions-modal-title {
        font-size: 20px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .corrective-actions-modal-title::before {
        content: "⚠️";
        font-size: 24px;
    }

    .corrective-actions-close {
        background: none;
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        line-height: 1;
    }

    .corrective-actions-close:hover {
        background-color: rgba(255, 255, 255, 0.15);
        transform: scale(1.1);
    }

    .corrective-actions-modal-body {
        padding: 28px;
        max-height: calc(90vh - 120px);
        overflow-y: auto;
    }

    .corrective-actions-notice {
        display: flex;
        gap: 16px;
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 2px solid #f59e0b;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .corrective-actions-notice::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f59e0b 0%, #d97706 50%, #f59e0b 100%);
    }

    .notice-icon {
        flex-shrink: 0;
        width: 28px;
        height: 28px;
        color: #d97706;
    }

    .notice-icon svg {
        width: 100%;
        height: 100%;
    }

    .notice-content h4 {
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: 700;
        color: #92400e;
    }

    .notice-content p {
        margin: 0;
        font-size: 14px;
        line-height: 1.5;
        color: #78350f;
    }

    .corrective-actions-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .corrective-actions-label {
        font-size: 16px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        display: block;
    }

    .required {
        color: #ef4444;
        font-weight: 700;
    }

    .corrective-actions-input {
        width: 100%;
        padding: 16px;
        border: 2px solid #d1d5db;
        border-radius: 12px;
        font-family: inherit;
        font-size: 15px;
        line-height: 1.5;
        resize: vertical;
        min-height: 150px;
        transition: all 0.3s ease;
        background: #fafafa;
    }

    .corrective-actions-input:focus {
        outline: none;
        border-color: #3b82f6;
        background: white;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    .corrective-actions-input::placeholder {
        color: #9ca3af;
        font-style: italic;
    }

    .character-count {
        text-align: right;
        font-size: 13px;
        color: #6b7280;
        margin-top: -12px;
    }

    .corrective-actions-buttons {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
    }

    .corrective-actions-buttons button {
        padding: 14px 28px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        min-width: 140px;
        position: relative;
        overflow: hidden;
    }

    .btn-cancel {
        background: #f3f4f6;
        color: #374151;
        border: 2px solid #e5e7eb;
    }

    .btn-cancel:hover {
        background: #e5e7eb;
        border-color: #d1d5db;
        transform: translateY(-1px);
    }

    .btn-submit {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-submit:hover:not(:disabled) {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .corrective-actions-modal-content {
            width: 95%;
            margin: 5% auto;
            max-height: 95vh;
        }

        .corrective-actions-modal-body {
            padding: 20px;
        }

        .corrective-actions-modal-header {
            padding: 20px;
        }

        .corrective-actions-buttons {
            flex-direction: column;
        }

        .corrective-actions-buttons button {
            width: 100%;
        }

        .corrective-actions-notice {
            flex-direction: column;
            text-align: center;
        }
    }

    /* Dark mode support */
    .dark .corrective-actions-modal-content {
        background-color: #1f2937;
        color: #e5e7eb;
    }

    .dark .corrective-actions-input {
        background: #374151;
        border-color: #4b5563;
        color: #e5e7eb;
    }

    .dark .corrective-actions-input:focus {
        background: #4b5563;
        border-color: #60a5fa;
    }

    .dark .corrective-actions-label {
        color: #e5e7eb;
    }

    .dark .character-count {
        color: #9ca3af;
    }

    .dark .corrective-actions-buttons {
        border-color: #4b5563;
    }

    .dark .btn-cancel {
        background: #374151;
        color: #e5e7eb;
        border-color: #4b5563;
    }

    .dark .btn-cancel:hover {
        background: #4b5563;
    }
</style>

<script>
    let currentItemId = null;
    let currentNewStatus = null;
    let currentItemType = '{{ $itemType }}';

    // Character counting
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.getElementById('correctiveActionsInput');
        const counter = document.getElementById('characterCount');

        if (textarea && counter) {
            textarea.addEventListener('input', function() {
                counter.textContent = this.value.length;

                // Color coding based on character count
                if (this.value.length > 1800) {
                    counter.style.color = '#ef4444';
                } else if (this.value.length > 1500) {
                    counter.style.color = '#f59e0b';
                } else {
                    counter.style.color = '#6b7280';
                }
            });
        }
    });

    function openCorrectiveActionsModal(itemId, newStatus) {
        currentItemId = itemId;
        currentNewStatus = newStatus;

        document.getElementById('{{ $modalId }}').style.display = 'block';
        document.body.style.overflow = 'hidden';

        // Focus on textarea after modal opens
        setTimeout(() => {
            document.getElementById('correctiveActionsInput').focus();
        }, 300);
    }

    function closeCorrectiveActionsModal() {
        document.getElementById('{{ $modalId }}').style.display = 'none';
        document.body.style.overflow = 'auto';

        // Reset form
        document.getElementById('correctiveActionsForm').reset();
        document.getElementById('characterCount').textContent = '0';
        document.getElementById('correctiveActionsSubmitBtn').disabled = false;
        document.getElementById('correctiveActionsSubmitBtn').innerHTML = 'Save & Update Status';

        currentItemId = null;
        currentNewStatus = null;
    }

    // Close modal when clicking outside
    document.getElementById('{{ $modalId }}').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCorrectiveActionsModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('{{ $modalId }}').style.display === 'block') {
            closeCorrectiveActionsModal();
        }
    });

    // Handle form submission
    document.getElementById('correctiveActionsForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('correctiveActionsSubmitBtn');
        const correctiveActions = document.getElementById('correctiveActionsInput').value.trim();

        if (!correctiveActions) {
            alert('Please enter the corrective actions before proceeding.');
            return;
        }

        if (!currentItemId || !currentNewStatus) {
            alert('Error: Missing required information.');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<div style="display: inline-block; width: 16px; height: 16px; border: 2px solid #ffffff; border-top: 2px solid transparent; border-radius: 50%; animation: spin 1s linear infinite;"></div> Saving...';

        try {
            const response = await fetch(`/${currentItemType}s/${currentItemId}/status-with-corrective-actions`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    status: currentNewStatus,
                    corrective_actions: correctiveActions
                })
            });

            const data = await response.json();

            if (data.success) {
                closeCorrectiveActionsModal();

                // Show success message
                showSuccessMessage('Status updated successfully with corrective actions!');

                // Reload the page to reflect changes
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Failed to update status');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error: ' + error.message);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Save & Update Status';
        }
    });

    function showSuccessMessage(message) {
        // Create success notification
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 16px 24px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
            z-index: 20000;
            font-size: 14px;
            font-weight: 600;
            animation: slideInRight 0.3s ease;
        `;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
</script>

<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100px);
        }
    }
</style>
