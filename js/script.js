// Screenshot capture using html2canvas (same-origin only)
function captureScreenshot(btn) {
    var frame = btn.closest('.frame').querySelector('iframe');
    var resultDiv = btn.closest('.chrome-footer').querySelector('.screenshot-result');
    try {
        html2canvas(frame.contentDocument.body).then(function(canvas) {
            resultDiv.innerHTML = '';
            resultDiv.appendChild(canvas);
        });
    } catch (e) {
        resultDiv.innerHTML = '<span style="color:red;">Screenshot only works for same-origin URLs.</span>';
    }
}

// Reload all frames
function reloadAllFrames() {
    document.querySelectorAll('.frame iframe').forEach(function(iframe) {
        iframe.contentWindow.location.reload();
    });
}

// Accessibility overlay toggle
const accBtn = document.getElementById('toggle-accessibility');
if (accBtn) {
    let accMode = false;
    accBtn.addEventListener('click', function() {
        accMode = !accMode;
        document.body.classList.toggle('accessibility-mode', accMode);
        accBtn.textContent = accMode ? '❌ Hide Accessibility Overlay' : '🦾 Accessibility Overlay';
        document.querySelectorAll('.frame').forEach(function(frame) {
            frame.classList.toggle('accessibility-overlay', accMode);
        });
    });
}

// Scroll sync toggle (same-origin only)
const scrollSync = document.getElementById('scroll-sync');
if (scrollSync) {
    scrollSync.addEventListener('change', function(e) {
        const enabled = e.target.checked;
        const iframes = document.querySelectorAll('.frame iframe');
        iframes.forEach(function(iframe) {
            if (iframe.contentWindow) iframe.contentWindow.onscroll = null;
        });
        if (enabled) {
            iframes.forEach(function(iframe) {
                if (iframe.contentWindow) {
                    iframe.contentWindow.onscroll = function() {
                        const scrollTop = iframe.contentWindow.scrollY;
                        iframes.forEach(function(other) {
                            if (other !== iframe && other.contentWindow) {
                                other.contentWindow.scrollTo(0, scrollTop);
                            }
                        });
                    };
                }
            });
        }
    });
}

// Show/hide scrollbars toggle
const toggleScrollbar = document.getElementById('toggle-scrollbar');
if (toggleScrollbar) {
    toggleScrollbar.addEventListener('change', function(e) {
        const hide = e.target.checked;
        document.querySelectorAll('.frame iframe').forEach(function(iframe) {
            if (hide) {
                iframe.style.overflow = 'hidden';
                iframe.style.scrollbarWidth = 'none'; // Firefox
                iframe.style.msOverflowStyle = 'none'; // IE 10+
            } else {
                iframe.style.overflow = 'auto';
                iframe.style.scrollbarWidth = '';
                iframe.style.msOverflowStyle = '';
            }
        });
    });
}

// Dark/Light mode toggle
const toggleBtn = document.getElementById('toggle-theme');
if (toggleBtn) {
    let darkMode = false;
    toggleBtn.addEventListener('click', function() {
        darkMode = !darkMode;
        document.body.classList.toggle('dark-mode', darkMode);
        toggleBtn.textContent = darkMode ? '☀️ Light Mode' : '🌙 Dark Mode';
    });
}