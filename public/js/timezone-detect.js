// public/js/timezone-detect.js

(function() {
    // Detect browser timezone
    const browserTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    
    // Store in cookie for backend use
    document.cookie = `browser_timezone=${browserTimezone}; path=/; max-age=31536000`;
    
    // Display local time
    function updateLocalTime() {
        const now = new Date();
        const options = {
            timeZone: browserTimezone,
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };
        
        const localTimeElement = document.getElementById('local-time');
        if (localTimeElement) {
            localTimeElement.textContent = now.toLocaleString('en-US', options);
        }
    }
    
    // Update every second
    setInterval(updateLocalTime, 1000);
    updateLocalTime();
})();