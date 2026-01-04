/**
 * Activity Tracker - Resets session inactivity timer on user interaction
 * Place this script in your main layout or base view
 */

(function() {
    // List of events that indicate user activity
    const activityEvents = [
        'mousedown',
        'mousemove',
        'keypress',
        'scroll',
        'touchstart',
        'click'
    ];

    let inactivityTimer;
    const inactivityTimeout = 60 * 60 * 1000; // 60 minutes in milliseconds

    /**
     * Reset the inactivity timer
     */
    function resetInactivityTimer() {
        clearTimeout(inactivityTimer);
        
        // Optional: Set a warning before logout (e.g., 5 minutes before)
        // inactivityTimer = setTimeout(() => {
        //     console.warn('Your session will expire soon due to inactivity');
        // }, inactivityTimeout - (5 * 60 * 1000));
    }

    /**
     * Add event listeners for user activity
     */
    function initActivityTracking() {
        activityEvents.forEach(event => {
            document.addEventListener(event, resetInactivityTimer);
        });

        // Initialize the timer on page load
        resetInactivityTimer();
    }

    // Start tracking when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initActivityTracking);
    } else {
        initActivityTracking();
    }
})();
