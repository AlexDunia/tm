/**
 * Enhanced Session Management for Kaka
 *
 * Features:
 * - Proactively refreshes the session based on user activity
 * - Shows warning before session expires
 * - Provides silent re-authentication when using Remember Me
 * - Adapts to context-specific timeouts
 */

class SessionManager {
    constructor() {
        this.sessionRefreshUrl = '/session/refresh';
        this.silentAuthUrl = '/session/silent-auth';
        this.refreshIntervalMS = 60000; // 1 minute
        this.warningThresholdMS = 120000; // 2 minutes
        this.activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        this.lastActivity = Date.now();
        this.refreshInterval = null;
        this.warningShown = false;
        this.warningModal = null;
        this.sessionData = {
            timeout: 0,
            expires: 0
        };

        this.init();
    }

    init() {
        // Create the warning modal (hidden initially)
        this.createWarningModal();

        // Track user activity
        this.trackActivity();

        // Start the refresh interval
        this.startRefreshInterval();

        // First immediate check
        this.refreshSession();
    }

    trackActivity() {
        // Update lastActivity whenever user interaction occurs
        this.activityEvents.forEach(event => {
            document.addEventListener(event, () => {
                this.lastActivity = Date.now();
                this.warningShown = false;
                this.hideWarningModal();
            });
        });
    }

    startRefreshInterval() {
        this.refreshInterval = setInterval(() => {
            // Check if user has been active recently
            const inactiveTime = Date.now() - this.lastActivity;

            // If nearing timeout threshold, show warning
            if (!this.warningShown && this.sessionData.expires > 0) {
                const timeRemaining = (this.sessionData.expires * 1000) - Date.now();
                if (timeRemaining <= this.warningThresholdMS) {
                    this.showWarningModal(Math.floor(timeRemaining / 1000));
                }
            }

            // If there was recent activity, refresh the session
            if (inactiveTime < this.refreshIntervalMS) {
                this.refreshSession();
            }
        }, this.refreshIntervalMS);
    }

    createWarningModal() {
        // Create modal if it doesn't exist
        if (!document.getElementById('session-warning-modal')) {
            const modal = document.createElement('div');
            modal.id = 'session-warning-modal';
            modal.className = 'session-modal';
            modal.style.display = 'none';

            modal.innerHTML = `
                <div class="session-modal-content">
                    <div class="session-modal-header">
                        <h2>Your session is about to expire</h2>
                    </div>
                    <div class="session-modal-body">
                        <p>For your security, your session will expire in <span id="session-countdown">0</span> seconds due to inactivity.</p>
                    </div>
                    <div class="session-modal-footer">
                        <button id="session-continue-btn" class="session-btn-primary">Continue Session</button>
                        <button id="session-logout-btn" class="session-btn-secondary">Logout Now</button>
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Add styles
            const style = document.createElement('style');
            style.textContent = `
                .session-modal {
                    display: none;
                    position: fixed;
                    z-index: 9999;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.7);
                    animation: fadeIn 0.3s ease-in-out;
                }

                .session-modal-content {
                    background: #1e1e2d;
                    margin: 15% auto;
                    padding: 25px;
                    border-radius: 8px;
                    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
                    max-width: 500px;
                    width: 90%;
                    color: #fff;
                    animation: slideIn 0.3s ease-in-out;
                }

                .session-modal-header h2 {
                    margin: 0;
                    color: #C04888;
                    font-size: 1.5rem;
                }

                .session-modal-body {
                    padding: 15px 0;
                    font-size: 1rem;
                    color: #a0aec0;
                }

                .session-modal-footer {
                    display: flex;
                    justify-content: flex-end;
                    gap: 10px;
                }

                .session-btn-primary {
                    background: #C04888;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 5px;
                    cursor: pointer;
                    transition: all 0.2s;
                }

                .session-btn-secondary {
                    background: rgba(255, 255, 255, 0.1);
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 5px;
                    cursor: pointer;
                    transition: all 0.2s;
                }

                .session-btn-primary:hover, .session-btn-secondary:hover {
                    transform: translateY(-2px);
                }

                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }

                @keyframes slideIn {
                    from { transform: translateY(-20px); opacity: 0; }
                    to { transform: translateY(0); opacity: 1; }
                }
            `;

            document.head.appendChild(style);

            this.warningModal = modal;

            // Add event listeners to buttons
            document.getElementById('session-continue-btn').addEventListener('click', () => {
                this.refreshSession(true);
                this.hideWarningModal();
            });

            document.getElementById('session-logout-btn').addEventListener('click', () => {
                window.location.href = '/logout';
            });
        }
    }

    showWarningModal(secondsRemaining) {
        if (!this.warningShown && this.warningModal) {
            this.warningShown = true;

            const countdownElement = document.getElementById('session-countdown');
            countdownElement.textContent = secondsRemaining;

            // Update countdown
            const countdownInterval = setInterval(() => {
                secondsRemaining--;
                countdownElement.textContent = secondsRemaining;

                if (secondsRemaining <= 0) {
                    clearInterval(countdownInterval);
                    // Try silent re-authentication before logging out
                    this.attemptSilentAuthentication();
                }
            }, 1000);

            this.warningModal.setAttribute('data-interval-id', countdownInterval);
            this.warningModal.style.display = 'block';
        }
    }

    hideWarningModal() {
        if (this.warningModal) {
            this.warningModal.style.display = 'none';

            // Clear the countdown interval if it exists
            const intervalId = this.warningModal.getAttribute('data-interval-id');
            if (intervalId) {
                clearInterval(parseInt(intervalId));
            }
        }
    }

    refreshSession(forceRefresh = false) {
        // Only refresh if there was activity or force refresh is enabled
        if (forceRefresh || Date.now() - this.lastActivity < this.refreshIntervalMS * 2) {
            // Get the CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            // Make the AJAX request to refresh the session
            fetch(this.sessionRefreshUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    session_refresh: true,
                    last_activity: this.lastActivity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.sessionData.timeout = data.timeout;
                    this.sessionData.expires = data.expires;

                    // Hide warning if it was shown
                    if (this.warningShown) {
                        this.warningShown = false;
                        this.hideWarningModal();
                    }
                }
            })
            .catch(error => {
                console.error('Session refresh failed:', error);
            });
        }
    }

    attemptSilentAuthentication() {
        // Try silent re-authentication for remember me users
        fetch(this.silentAuthUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Successfully re-authenticated silently
                window.location.reload();
            } else {
                // Could not re-authenticate, redirect to login
                window.location.href = '/login?expired=1';
            }
        })
        .catch(() => {
            // Error, redirect to login
            window.location.href = '/login?expired=1';
        });
    }
}

// Initialize the session manager when the DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize for authenticated users
    const isAuthenticated = document.body.classList.contains('user-authenticated');
    if (isAuthenticated) {
        window.sessionManager = new SessionManager();
    }
});
