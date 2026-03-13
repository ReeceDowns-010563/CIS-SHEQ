<!-- Comments Modal -->
<div id="commentsModal" class="comments-modal">
    <div class="comments-modal-content">
        <div class="comments-modal-header">
            <h3 class="comments-modal-title">Comments</h3>
            <button type="button" class="comments-close" onclick="closeCommentsModal()">&times;</button>
        </div>
        <div class="comments-modal-body">
            <div id="commentsLoader" style="text-align: center; padding: 20px;">
                <div class="loading-spinner"></div>
                <p style="margin-top: 10px; color: #6b7280;">Loading comments...</p>
            </div>

            <div id="commentsContent" style="display: none;">
                <div class="comments-list" id="commentsList">
                    <!-- Comments will be loaded here -->
                </div>

                <form class="comment-form" id="commentForm">
                    @csrf
                    <textarea
                        id="commentInput"
                        class="comment-input"
                        placeholder="Add your comment..."
                        required
                        maxlength="1000"
                    ></textarea>
                    <button type="submit" class="comment-submit" id="commentSubmitBtn">
                        Post Comment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let currentComplaintId = null;

        function openCommentsModal(complaintId) {
            currentComplaintId = complaintId;
            document.getElementById('commentsModal').style.display = 'block';
            document.getElementById('commentsLoader').style.display = 'block';
            document.getElementById('commentsContent').style.display = 'none';
            document.body.style.overflow = 'hidden';

            loadComments(complaintId);
        }

        function closeCommentsModal() {
            document.getElementById('commentsModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            currentComplaintId = null;

            // Reset form
            document.getElementById('commentForm').reset();
            document.getElementById('commentSubmitBtn').disabled = false;
        }

        // Close modal when clicking outside
        document.getElementById('commentsModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCommentsModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('commentsModal').style.display === 'block') {
                closeCommentsModal();
            }
        });

        async function loadComments(complaintId) {
            try {
                const response = await fetch(`/complaints/${complaintId}/comments`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    displayComments(data.comments);
                    updateCommentsCount(complaintId, data.count);
                } else {
                    showError('Failed to load comments');
                }
            } catch (error) {
                console.error('Error loading comments:', error);
                showError('Error loading comments');
            } finally {
                document.getElementById('commentsLoader').style.display = 'none';
                document.getElementById('commentsContent').style.display = 'block';
            }
        }

        function displayComments(comments) {
            const commentsList = document.getElementById('commentsList');

            if (comments.length === 0) {
                commentsList.innerHTML = '<div class="no-comments">No comments yet. Be the first to comment!</div>';
                return;
            }

            commentsList.innerHTML = comments.map(comment => `
        <div class="comment-item" data-comment-id="${comment.id}">
            <div class="comment-header">
                <span class="comment-author">${escapeHtml(comment.user_name)}</span>
                <span class="comment-date">${comment.created_at}</span>
            </div>
            <div class="comment-text">${escapeHtml(comment.comment)}</div>
            ${comment.can_delete ? `
                <div class="comment-actions">
                    <button type="button" class="comment-delete" onclick="deleteComment(${comment.id})">
                        Delete
                    </button>
                </div>
            ` : ''}
        </div>
    `).join('');
        }

        // Handle comment form submission
        document.getElementById('commentForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('commentSubmitBtn');
            const commentInput = document.getElementById('commentInput');
            const comment = commentInput.value.trim();

            if (!comment) {
                showError('Please enter a comment');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<div class="loading-spinner" style="width: 16px; height: 16px;"></div> Posting...';

            try {
                const response = await fetch(`/complaints/${currentComplaintId}/comments`, {
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
                    loadComments(currentComplaintId); // Reload comments
                    showSuccess('Comment added successfully');
                } else {
                    showError(data.message || 'Failed to add comment');
                }
            } catch (error) {
                console.error('Error posting comment:', error);
                showError('Error posting comment');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Post Comment';
            }
        });

        async function deleteComment(commentId) {
            if (!confirm('Are you sure you want to delete this comment?')) {
                return;
            }

            try {
                const response = await fetch(`/complaints/${currentComplaintId}/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    loadComments(currentComplaintId); // Reload comments
                    showSuccess('Comment deleted successfully');
                } else {
                    showError(data.message || 'Failed to delete comment');
                }
            } catch (error) {
                console.error('Error deleting comment:', error);
                showError('Error deleting comment');
            }
        }

        function updateCommentsCount(complaintId, count) {
            const countElement = document.getElementById(`comments-count-${complaintId}`);
            if (countElement) {
                countElement.textContent = count;
                countElement.style.display = count > 0 ? 'block' : 'none';
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function showError(message) {
            // You can customize this to match your app's notification system
            alert('Error: ' + message);
        }

        function showSuccess(message) {
            // You can customize this to match your app's notification system
            alert('Success: ' + message);
        }
    </script>
@endpush
