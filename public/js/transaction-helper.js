/**
 * Transaction Helper Functions for Success Page
 */

// Function to fetch transaction details from API
function fetchTransactionDetails(reference, isRetry = false) {
    console.log('Fetching transaction details for reference:', reference, isRetry ? '(retry attempt)' : '');

    // Show loading indicator
    const notificationArea = document.querySelector('.notification-area');
    if (notificationArea) {
        notificationArea.innerHTML = `
            <div class="notification info">
                <i class="fa-solid fa-circle-notch fa-spin"></i>
                <p>Loading transaction details for reference: ${reference}${isRetry ? ' (retry attempt)' : ''}...</p>
            </div>
        `;
    }

    // Use the correct API endpoint path
    fetch(`/api/transaction/${reference}`)
        .then(response => {
            console.log('API response status:', response.status);
            if (!response.ok) {
                if (response.status === 404) {
                    // Handle 404 specifically - could be routing issue
                    throw new Error(`API error: 404. The transaction endpoint could not be found.`);
                }
                throw new Error(`API error: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Transaction data received:', data);
            if (data.status === 'success') {
                // Update receipt with complete transaction details
                updateReceiptWithTransactionData(data.data);
            } else {
                showVerificationError(data.message || 'Transaction verification failed');

                // If first attempt failed and no retry yet, try alternative format
                if (!isRetry && reference.startsWith('TD')) {
                    console.log('First attempt failed, trying without TD prefix');
                    setTimeout(() => {
                        fetchTransactionDetails(reference.substring(2), true);
                    }, 1000);
                }
            }
        })
        .catch(error => {
            console.error('Error fetching transaction details:', error);

            // If it's the first attempt, try again after a delay
            if (!isRetry) {
                console.log('Will retry after 2 seconds...');
                setTimeout(() => {
                    fetchTransactionDetails(reference, true);
                }, 2000);
            } else {
                showVerificationError('Failed to verify transaction. Please try again or contact support.');

                // Clear the notification area completely to avoid showing error messages
                if (notificationArea) {
                    notificationArea.innerHTML = '';
                }
            }
        });
}

// Function to update receipt with transaction data
function updateReceiptWithTransactionData(transaction) {
    console.log('Updating receipt with transaction data:', transaction);

    try {
        // Update reference
        const referenceValue = document.querySelector('.receipt-value');
        if (referenceValue) {
            referenceValue.textContent = transaction.reference || 'N/A';
        }

        // Update customer details
        const receiptDetails = document.querySelector('.receipt-details');
        if (receiptDetails) {
            receiptDetails.innerHTML = `
                <div class="receipt-row">
                    <span class="receipt-label">Reference:</span>
                    <span class="receipt-value">${transaction.reference || 'N/A'}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Customer:</span>
                    <span class="receipt-value">${transaction.customer?.name || 'N/A'}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Email:</span>
                    <span class="receipt-value">${transaction.customer?.email || 'N/A'}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Phone:</span>
                    <span class="receipt-value">${transaction.customer?.phone || 'N/A'}</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Date:</span>
                    <span class="receipt-value">${transaction.date || 'N/A'}</span>
                </div>
            `;
        }

        // Update items
        const receiptItems = document.querySelector('.receipt-items');
        if (receiptItems) {
            receiptItems.innerHTML = `
                <div class="receipt-items-header">
                    <span class="item-name">Item</span>
                    <span class="item-qty">Qty</span>
                    <span class="item-price">Price</span>
                    <span class="item-total">Total</span>
                </div>
                <div class="receipt-item">
                    <div class="item-name">
                        <strong>Ticket</strong>
                        <div class="item-event">${transaction.event || 'Event Ticket'}</div>
                    </div>
                    <span class="item-qty">${transaction.quantity || 1}</span>
                    <span class="item-price">₦${((transaction.subtotal || 0) / (transaction.quantity || 1)).toFixed(2)}</span>
                    <span class="item-total">₦${(transaction.subtotal || 0).toFixed(2)}</span>
                </div>
            `;
        }

        // Update summary
        const receiptSummary = document.querySelector('.receipt-summary');
        if (receiptSummary) {
            receiptSummary.innerHTML = `
                <div class="receipt-row subtotal">
                    <span class="receipt-label">Subtotal:</span>
                    <span class="receipt-value">₦${(transaction.subtotal || 0).toFixed(2)}</span>
                </div>
                <div class="receipt-row fee">
                    <span class="receipt-label">Service Fee (5%):</span>
                    <span class="receipt-value">₦${(transaction.serviceFee || 0).toFixed(2)}</span>
                </div>
                <div class="receipt-row total">
                    <span class="receipt-label">Total Amount:</span>
                    <span class="receipt-value">₦${(transaction.totalAmount || 0).toFixed(2)}</span>
                </div>
            `;
        }

        // Update barcode
        const barcodeText = document.querySelector('.barcode-text');
        if (barcodeText) {
            barcodeText.textContent = transaction.reference || 'N/A';
        }

        // Update tickets section if available
        if (transaction.ticket_ids && transaction.ticket_ids.length > 0) {
            let ticketHtml = `
                <div class="ticket-section">
                    <h2>Your Tickets</h2>
                    <p class="ticket-instruction">Please save or screenshot these ticket IDs - you'll need to present them at the event.</p>
                    <div class="ticket-list">
            `;

            transaction.ticket_ids.forEach(id => {
                ticketHtml += `
                    <div class="ticket-item">
                        <div class="ticket-id">${id || 'N/A'}</div>
                        <div class="ticket-qr">
                            <i class="fa-solid fa-qrcode"></i>
                        </div>
                    </div>
                `;
            });

            ticketHtml += `</div></div>`;

            // If ticket section doesn't exist, append it
            const ticketSection = document.querySelector('.ticket-section');
            if (ticketSection) {
                ticketSection.innerHTML = ticketHtml;
            } else {
                const receiptContainer = document.querySelector('.receipt-container');
                if (receiptContainer) {
                    receiptContainer.insertAdjacentHTML('afterend', ticketHtml);
                }
            }
        }

        // Hide verification form if it was used
        const verificationWrapper = document.querySelector('.manual-verification-wrapper');
        if (verificationWrapper) {
            verificationWrapper.style.display = 'none';
        }

        // Show success notification
        const notificationArea = document.querySelector('.notification-area');
        if (notificationArea) {
            notificationArea.innerHTML = `
                <div class="notification success">
                    <i class="fa-solid fa-circle-check"></i>
                    <p>Transaction details successfully loaded!</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error updating receipt:', error);

        // Clear notification area completely instead of showing error
        const notificationArea = document.querySelector('.notification-area');
        if (notificationArea) {
            notificationArea.innerHTML = '';
        }
    }
}

// Function to show verification error - replace with warning style
function showVerificationError(message) {
    const resultDiv = document.getElementById('verificationResult');
    if (resultDiv) {
        resultDiv.innerHTML = `<div class="verification-warning"><i class="fa-solid fa-triangle-exclamation"></i> ${message}</div>`;
        resultDiv.style.display = 'block';
    }
}

// Initialize when page is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if we have a reference parameter in the URL
    const urlParams = new URLSearchParams(window.location.search);
    const reference = urlParams.get('reference');

    if (reference) {
        console.log('Reference found in URL:', reference);
        // Wait a bit for DOM to fully initialize
        setTimeout(() => {
            fetchTransactionDetails(reference);
        }, 500);
    }

    // Handle manual verification form submission
    const verifyForm = document.getElementById('verifyReferenceForm');
    if (verifyForm) {
        verifyForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const referenceInput = document.getElementById('referenceInput');
            if (referenceInput) {
                const reference = referenceInput.value.trim();

                if (!reference) return;

                // Simple validation using regex for alphanumeric, hyphen, and underscore
                const referenceRegex = /^[A-Za-z0-9\-_]+$/;
                if (!referenceRegex.test(reference)) {
                    showVerificationError('Invalid reference format');
                    return;
                }

                const resultDiv = document.getElementById('verificationResult');
                if (resultDiv) {
                    resultDiv.innerHTML = '<div class="verification-loading"><i class="fa-solid fa-circle-notch fa-spin"></i> Verifying payment...</div>';
                    resultDiv.style.display = 'block';
                }

                // Call the API to fetch transaction details
                fetchTransactionDetails(reference);
            }
        });
    }
});
