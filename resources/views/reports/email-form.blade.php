<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Email Monthly Report</h2>
    </x-slot>

    <style>
        * {
            box-sizing: border-box;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .email-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2.5rem;
            text-align: center;
        }

        .card-header h1 {
            font-size: 2rem;
            font-weight: 300;
            margin: 0 0 0.5rem 0;
        }

        .card-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
        }

        .card-body {
            padding: 3rem;
        }

        .form-section {
            margin-bottom: 2.5rem;
        }

        .section-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control {
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            color: #2d3748;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }

        .recipients-section {
            background: #f8f9fa;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .recipients-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .add-recipient-btn {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .add-recipient-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(72, 187, 120, 0.3);
        }

        .recipient-item {
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 1rem;
            align-items: center;
            background: white;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .recipient-item:hover {
            border-color: #cbd5e0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .recipient-email {
            flex: 1;
        }

        .recipient-type {
            padding: 0.5rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            font-size: 0.9rem;
            min-width: 100px;
        }

        .remove-recipient {
            background: #e53e3e;
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .remove-recipient:hover {
            background: #c53030;
            transform: scale(1.1);
        }

        .message-section textarea {
            min-height: 120px;
            resize: vertical;
            font-family: inherit;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 3rem;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 180px;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .loading-content {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .success-message {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: none;
        }

        .error-message {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            color: white;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: none;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .card-body {
                padding: 2rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .recipient-item {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .recipients-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }
        }

        @media (prefers-color-scheme: dark) {
            .email-card {
                background: #1f2937;
            }

            .card-body {
                background: #1f2937;
            }

            .section-title {
                color: #f9fafb;
                border-bottom-color: #374151;
            }

            .form-group label {
                color: #d1d5db;
            }

            .form-control {
                background: #374151;
                border-color: #4b5563;
                color: #f9fafb;
            }

            .form-control:focus {
                border-color: #667eea;
                background: #4b5563;
            }

            .recipients-section {
                background: #374151;
            }

            .recipient-item {
                background: #4b5563;
                border-color: #6b7280;
                color: #f9fafb;
            }

            .recipient-type {
                background: #6b7280;
                border-color: #9ca3af;
                color: #f9fafb;
            }

            .loading-content {
                background: #1f2937;
                color: #f9fafb;
            }
        }
    </style>

    <div class="py-10">
        <div class="container">
            <div class="email-card">
                <div class="card-header">
                    <h1>📧 Email Monthly Report</h1>
                    <p>Send comprehensive PDF reports to stakeholders</p>
                </div>

                <div class="card-body">
                    <div class="success-message" id="successMessage">
                        ✅ Report emailed successfully!
                    </div>

                    <div class="error-message" id="errorMessage">
                        ❌ Failed to send email. Please try again.
                    </div>

                    <form id="emailForm">
                        @csrf

                        <div class="form-section">
                            <h2 class="section-title">📅 Report Period</h2>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="month">Month</label>
                                    <select id="month" name="month" class="form-control" required>
                                        @foreach($months as $month)
                                            <option value="{{ $month['value'] }}" {{ $month['value'] == date('n') ? 'selected' : '' }}>
                                                {{ $month['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="year">Year</label>
                                    <select id="year" name="year" class="form-control" required>
                                        @foreach($years as $year)
                                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="recipients-section">
                                <div class="recipients-header">
                                    <h2 class="section-title" style="margin-bottom: 0; border-bottom: none;">👥 Recipients</h2>
                                    <button type="button" class="add-recipient-btn" onclick="addRecipient()">
                                        ➕ Add Recipient
                                    </button>
                                </div>

                                <div id="recipientsList">
                                    <div class="recipient-item">
                                        <input type="email" class="form-control recipient-email" placeholder="Enter email address" required>
                                        <select class="recipient-type">
                                            <option value="to">TO</option>
                                            <option value="cc">CC</option>
                                            <option value="bcc">BCC</option>
                                        </select>
                                        <button type="button" class="remove-recipient" onclick="removeRecipient(this)" style="display: none;">✖</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section message-section">
                            <h2 class="section-title">✉️ Custom Message (Optional)</h2>
                            <div class="form-group">
                                <label for="customMessage">Additional message to include in the email</label>
                                <textarea id="customMessage" name="customMessage" class="form-control"
                                          placeholder="Add any additional context or notes for the recipients..."></textarea>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary">
                                📤 Send Report
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="goBack()">
                                ⬅️ Back to Export Options
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner"></div>
            <h3>Generating and Sending Report...</h3>
            <p>This may take a few moments</p>
        </div>
    </div>

    <script>
        let recipientCount = 1;

        function addRecipient() {
            recipientCount++;
            const recipientsList = document.getElementById('recipientsList');

            const recipientDiv = document.createElement('div');
            recipientDiv.className = 'recipient-item';
            recipientDiv.innerHTML = `
                <input type="email" class="form-control recipient-email" placeholder="Enter email address" required>
                <select class="recipient-type">
                    <option value="to">TO</option>
                    <option value="cc">CC</option>
                    <option value="bcc">BCC</option>
                </select>
                <button type="button" class="remove-recipient" onclick="removeRecipient(this)">✖</button>
            `;

            recipientsList.appendChild(recipientDiv);
            updateRemoveButtons();
        }

        function removeRecipient(button) {
            button.parentElement.remove();
            recipientCount--;
            updateRemoveButtons();
        }

        function updateRemoveButtons() {
            const removeButtons = document.querySelectorAll('.remove-recipient');
            removeButtons.forEach(button => {
                button.style.display = recipientCount > 1 ? 'flex' : 'none';
            });
        }

        function goBack() {
            window.location.href = '{{ route("reports.export") }}';
        }

        document.getElementById('emailForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const loadingOverlay = document.getElementById('loadingOverlay');
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');

            // Hide previous messages
            successMessage.style.display = 'none';
            errorMessage.style.display = 'none';

            // Show loading overlay
            loadingOverlay.style.display = 'flex';

            // Collect form data
            const formData = {
                month: document.getElementById('month').value,
                year: document.getElementById('year').value,
                customMessage: document.getElementById('customMessage').value,
                recipients: [],
                _token: document.querySelector('input[name="_token"]').value
            };

            // Collect recipients
            const recipientItems = document.querySelectorAll('.recipient-item');
            recipientItems.forEach(item => {
                const email = item.querySelector('.recipient-email').value.trim();
                const type = item.querySelector('.recipient-type').value;

                if (email) {
                    formData.recipients.push({ email, type });
                }
            });

            if (formData.recipients.length === 0) {
                loadingOverlay.style.display = 'none';
                errorMessage.textContent = 'Please add at least one recipient.';
                errorMessage.style.display = 'block';
                return;
            }

            try {
                const response = await fetch('{{ route("reports.email.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': formData._token
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                loadingOverlay.style.display = 'none';

                if (response.ok) {
                    successMessage.textContent = result.message || 'Report emailed successfully!';
                    successMessage.style.display = 'block';
                    // Reset form
                    document.getElementById('customMessage').value = '';
                    // Scroll to top to show message
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    errorMessage.textContent = result.message || 'Failed to send email. Please try again.';
                    errorMessage.style.display = 'block';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            } catch (error) {
                loadingOverlay.style.display = 'none';
                errorMessage.textContent = 'Network error. Please check your connection and try again.';
                errorMessage.style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
                console.error('Error:', error);
            }
        });

        // Initialize remove buttons visibility
        updateRemoveButtons();
    </script>
</x-app-layout>
