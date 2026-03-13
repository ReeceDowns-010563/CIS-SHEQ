<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'modalId' => 'commentsModal',
    'itemType' => 'incident', // 'incident' or 'complaint'
    'title' => 'Comments'
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'modalId' => 'commentsModal',
    'itemType' => 'incident', // 'incident' or 'complaint'
    'title' => 'Comments'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<!-- Comments Modal -->
<div id="<?php echo e($modalId); ?>" class="comments-modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(3px);">
    <div class="comments-modal-content" style="position: relative; background-color: white; margin: 5% auto; padding: 0; border-radius: 12px; width: 90%; max-width: 600px; max-height: 80vh; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2); animation: modalSlideIn 0.3s ease;">
        <div class="comments-modal-header" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 24px; border-bottom: 1px solid #e5e7eb; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px 12px 0 0;">
            <h3 class="comments-modal-title" style="font-size: 18px; font-weight: 600; margin: 0;"><?php echo e($title); ?></h3>
            <button type="button" class="comments-close" onclick="closeCommentsModal('<?php echo e($modalId); ?>')" style="background: none; border: none; color: white; font-size: 24px; cursor: pointer; padding: 4px; border-radius: 4px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; transition: background-color 0.2s;">&times;</button>
        </div>
        <div class="comments-modal-body" style="padding: 24px; max-height: 50vh; overflow-y: auto;">
            <div id="<?php echo e($modalId); ?>Loader" style="text-align: center; padding: 20px;">
                <div class="loading-spinner" style="display: inline-block; width: 20px; height: 20px; border: 3px solid #f3f3f3; border-top: 3px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <p style="margin-top: 10px; color: #6b7280;">Loading comments...</p>
            </div>

            <div id="<?php echo e($modalId); ?>Content" style="display: none;">
                <div class="comments-list" id="<?php echo e($modalId); ?>List" style="max-height: 300px; overflow-y: auto; margin-bottom: 20px;">
                    <!-- Comments will be loaded here -->
                </div>

                <form class="comment-form" id="<?php echo e($modalId); ?>Form" style="border-top: 1px solid #e5e7eb; padding-top: 20px;">
                    <?php echo csrf_field(); ?>
                    <textarea id="<?php echo e($modalId); ?>Input" class="comment-input" placeholder="Add your comment..." required maxlength="1000" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; resize: vertical; min-height: 80px; font-family: inherit; font-size: 14px; transition: border-color 0.2s;"></textarea>
                    <button type="submit" class="comment-submit" id="<?php echo e($modalId); ?>SubmitBtn" style="margin-top: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s;">
                        Post Comment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    @keyframes modalSlideIn {
        from { opacity: 0; transform: translateY(-50px) scale(0.9); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .comments-close:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
    .comment-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    .comment-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }
    .comment-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
</style>

<script>
    // Comments modal functionality for <?php echo e($modalId); ?>

    (function() {
    let currentItemId = null;
    const modalId = '<?php echo e($modalId); ?>';
    const itemType = '<?php echo e($itemType); ?>';

    // Make functions globally available
    window.openCommentsModal = window.openCommentsModal || function(itemId, targetModalId = modalId) {
        if (targetModalId !== modalId) return;

        currentItemId = itemId;
        document.getElementById(modalId).style.display = 'block';
        document.getElementById(modalId + 'Loader').style.display = 'block';
        document.getElementById(modalId + 'Content').style.display = 'none';
        document.body.style.overflow = 'hidden';
        loadComments(itemId);
    };

    window.closeCommentsModal = window.closeCommentsModal || function(targetModalId = modalId) {
        if (targetModalId !== modalId) return;

        document.getElementById(modalId).style.display = 'none';
        document.body.style.overflow = 'auto';
        currentItemId = null;
        document.getElementById(modalId + 'Form').reset();
        document.getElementById(modalId + 'SubmitBtn').disabled = false;
    };

    // Close modal when clicking outside
    document.getElementById(modalId).addEventListener('click', function(e) {
        if (e.target === this) {
            closeCommentsModal(modalId);
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById(modalId).style.display === 'block') {
            closeCommentsModal(modalId);
        }
    });

    async function loadComments(itemId) {
        try {
            const endpoint = itemType === 'complaint'
                ? `/complaints/${itemId}/comments`
                : `/incidents/${itemId}/comments`;

            const response = await fetch(endpoint, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                displayComments(data.comments);
                updateCommentsCount(itemId, data.count);
            } else {
                alert('Failed to load comments');
            }
        } catch (error) {
            console.error('Error loading comments:', error);
            alert('Error loading comments');
        } finally {
            document.getElementById(modalId + 'Loader').style.display = 'none';
            document.getElementById(modalId + 'Content').style.display = 'block';
        }
    }

    function displayComments(comments) {
        const commentsList = document.getElementById(modalId + 'List');

        if (comments.length === 0) {
            commentsList.innerHTML = '<div style="text-align: center; color: #6b7280; font-style: italic; padding: 40px 20px;">No comments yet. Be the first to comment!</div>';
            return;
        }

        commentsList.innerHTML = comments.map(comment => `
                <div style="padding: 16px; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 12px; background: #f9fafb; position: relative;" data-comment-id="${comment.id}">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-weight: 600; color: #374151; font-size: 14px;">${escapeHtml(comment.user_name)}</span>
                        <span style="font-size: 12px; color: #6b7280;">${comment.created_at}</span>
                    </div>
                    <div style="color: #374151; line-height: 1.5; margin-bottom: 8px;">${escapeHtml(comment.comment)}</div>
                    ${comment.can_delete ? `
                        <div style="display: flex; gap: 8px;">
                            <button type="button" onclick="deleteComment(${comment.id}, '${modalId}')" style="background: #ef4444; color: white; border: none; padding: 4px 8px; border-radius: 4px; font-size: 12px; cursor: pointer; transition: background-color 0.2s;">
                                Delete
                            </button>
                        </div>
                    ` : ''}
                </div>
            `).join('');
    }

    // Handle comment form submission
    document.getElementById(modalId + 'Form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById(modalId + 'SubmitBtn');
        const commentInput = document.getElementById(modalId + 'Input');
        const comment = commentInput.value.trim();

        if (!comment) {
            alert('Please enter a comment');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<div class="loading-spinner" style="width: 16px; height: 16px; display: inline-block; border: 3px solid #f3f3f3; border-top: 3px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite;"></div> Posting...';

        try {
            const endpoint = itemType === 'complaint'
                ? `/complaints/${currentItemId}/comments`
                : `/incidents/${currentItemId}/comments`;

            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ comment: comment })
            });

            const data = await response.json();

            if (data.success) {
                commentInput.value = '';
                loadComments(currentItemId);
                alert('Comment added successfully');
            } else {
                alert('Error: ' + (data.message || 'Failed to add comment'));
            }
        } catch (error) {
            console.error('Error posting comment:', error);
            alert('Error posting comment');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Post Comment';
        }
    });

    window.deleteComment = window.deleteComment || async function(commentId, targetModalId = modalId) {
        if (targetModalId !== modalId) return;

        if (!confirm('Are you sure you want to delete this comment?')) {
            return;
        }

        try {
            const endpoint = itemType === 'complaint'
                ? `/complaints/${currentItemId}/comments/${commentId}`
                : `/incidents/${currentItemId}/comments/${commentId}`;

            const response = await fetch(endpoint, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                loadComments(currentItemId);
                alert('Comment deleted successfully');
            } else {
                alert('Error: ' + (data.message || 'Failed to delete comment'));
            }
        } catch (error) {
            console.error('Error deleting comment:', error);
            alert('Error deleting comment');
        }
    };

    function updateCommentsCount(itemId, count) {
        const countElement = document.querySelector(`[data-count][onclick*="${itemId}"] .comments-count`);
        if (countElement) {
            countElement.textContent = count > 0 ? count : '';
            countElement.setAttribute('data-count', count);
            countElement.style.display = count > 0 ? 'inline' : 'none';
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    })();
</script>
<?php /**PATH /home/sites/29a/4/4aab0e4a5e/public_html/laravel12/resources/views/incidents/components/comments-modal.blade.php ENDPATH**/ ?>