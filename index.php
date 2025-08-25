<?php
   // Create an html page with multiple iframes or say framesets having different widths of each frameset as per standar Desktop, latop, Tablet, Mobile widths.
   $device_widths = [
       'large-desktop' => '1920',
       'desktop' => '1418',
       'laptop' => '1280',
       'tablet' => '1024',
       'mobile' => '480'
   ];
   // take url as input from user and once it's submitted, display the iframes with the given url in each frameset.
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $url = $_POST['url'];
         if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            $error = "Invalid URL provided.";
         } else {
            // Update the iframe source with the user-provided URL
            foreach ($device_widths as $device => $width) {
                $widths[$device] = "<iframe src=\"$url\" style=\"height: 80vh; border: none;\"></iframe>";
            }
         }
   }
   else {
       $url = 'http://localhost'; // Default URL if not provided
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Responsive Iframes</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="top-bar">
    <form method="post" class="top-bar-form">
      <table></table>
        <label for="url">Enter URL:</label>
        <input type="text" id="url" name="url" value="<?php echo isset($url) ? htmlspecialchars($url) : ''; ?>" style="width:300px;">
        <label for="device">Device:</label>
        <select name="device" id="device">
            <?php foreach ($device_widths as $device => $width): ?>
                <option value="<?php echo $device; ?>" <?php echo (isset($_POST['device']) && $_POST['device'] === $device) ? 'selected' : ''; ?>>
                    <?php echo ucfirst($device); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="orientation">Orientation:</label>
        <select name="orientation" id="orientation">
            <option value="landscape" <?php echo (isset($_POST['orientation']) && $_POST['orientation'] === 'landscape') ? 'selected' : ''; ?>>Landscape</option>
            <option value="portrait" <?php echo (isset($_POST['orientation']) && $_POST['orientation'] === 'portrait') ? 'selected' : ''; ?>>Portrait</option>
         </select>
        <label for="user_agent">User Agent:</label>
        <select name="user_agent" id="user_agent">
            <option value="default" <?php echo (isset($_POST['user_agent']) && $_POST['user_agent'] === 'default') ? 'selected' : ''; ?>>Default</option>
            <option value="chrome_mobile" <?php echo (isset($_POST['user_agent']) && $_POST['user_agent'] === 'chrome_mobile') ? 'selected' : ''; ?>>Chrome Mobile</option>
            <option value="safari_ios" <?php echo (isset($_POST['user_agent']) && $_POST['user_agent'] === 'safari_ios') ? 'selected' : ''; ?>>Safari iOS</option>
            <option value="firefox_android" <?php echo (isset($_POST['user_agent']) && $_POST['user_agent'] === 'firefox_android') ? 'selected' : ''; ?>>Firefox Android</option>
        </select>
        <label for="network">Network:</label>
        <select name="network" id="network">
            <option value="default" <?php echo (isset($_POST['network']) && $_POST['network'] === 'default') ? 'selected' : ''; ?>>Default</option>
            <option value="slow_3g" <?php echo (isset($_POST['network']) && $_POST['network'] === 'slow_3g') ? 'selected' : ''; ?>>Slow 3G</option>
            <option value="fast_3g" <?php echo (isset($_POST['network']) && $_POST['network'] === 'fast_3g') ? 'selected' : ''; ?>>Fast 3G</option>
            <option value="4g" <?php echo (isset($_POST['network']) && $_POST['network'] === '4g') ? 'selected' : ''; ?>>4G</option>
        </select>
        <button type="submit" style="padding: 8px 18px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 1em; font-weight: 500; cursor: pointer;">
            Show Responsive View
        </button>
        <button type="button" onclick="reloadAllFrames()" style="padding: 8px 18px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 1em; font-weight: 500; cursor: pointer;">
            &#x21bb; Reload All Frames
        </button>
        <button type="button" id="toggle-accessibility" style="background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 1em; font-weight: 500; cursor: pointer; padding: 8px 18px;">
            🦾 Accessibility Overlay
        </button>
        <label style="display:flex;flex-direction:row;align-items:center;">
            <input type="checkbox" id="scroll-sync" style="margin-right:6px;">
            Sync Scroll
        </label>
        <?php if (!empty($error)): ?>
            <div style="color:red;"><?php echo $error; ?></div>
        <?php endif; ?>
    </form>
</div>

<!-- Feature 2: Custom Width Input for Each Device -->
<?php
// Add this above your device loop to handle custom widths
$custom_widths = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['custom_width'])) {
    $custom_widths = $_POST['custom_width'];
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)):
   foreach ($device_widths as $device => $width):
        $isLandscape = true; // Always set to landscape
        $defaultPortrait = (int)preg_replace('/[^0-9]/', '', $width);
        $defaultLandscape = $device === 'mobile' ? 384 : ($device === 'tablet' ? 1024 : $defaultPortrait);
        $activeWidth = $isLandscape ? $defaultLandscape : $defaultPortrait;
        
        // Use custom width if provided and valid
        $customWidth = isset($custom_widths[$device]) && is_numeric($custom_widths[$device]) && $custom_widths[$device] > 0
            ? (int)$custom_widths[$device]
            : $activeWidth;

        $containerWidth = ($customWidth + 20) . 'px';
?>
    <form method="post" style="display:inline;">
        <input type="hidden" name="url" value="<?php echo htmlspecialchars($url); ?>">
        <?php foreach ($device_widths as $d => $w): // Keep existing hidden inputs for other devices ?>
            <input type="hidden" name="orientation[<?php echo $d; ?>]" value="<?php echo isset($orientations[$d]) ? $orientations[$d] : 'portrait'; ?>">
            <input type="hidden" name="custom_width[<?php echo $d; ?>]" value="<?php echo isset($custom_widths[$d]) ? htmlspecialchars($custom_widths[$d]) : ''; ?>">
        <?php endforeach; ?>
        <div class="frame" style="width: <?php echo $containerWidth; ?>;">
            <div class="chrome-bar">
                <span class="chrome-circles">
                    <span class="chrome-circle red"></span>
                    <span class="chrome-circle yellow"></span>
                    <span class="chrome-circle green"></span>
                </span>
                <span class="chrome-title">
                    <?php echo ucfirst($device); ?> View &mdash; <?php echo $customWidth; ?>px &mdash; <?php echo htmlspecialchars($url); ?>
                </span>
                <input type="number" name="custom_width[<?php echo $device; ?>]" value="<?php echo $customWidth; ?>" min="200" max="2000" style="width:80px; margin-left:12px;" title="Custom width for <?php echo $device; ?>">
                <button type="submit" style="margin-left:4px;">Set Width</button>
            </div>
            <iframe src="<?php echo htmlspecialchars($url); ?>" style="height: 80vh;"></iframe>
        </div>
    </form>
<?php endforeach;
endif;
?>

<!-- Feature 3: Height Adjustment for Each Device Window -->
<?php /*
// Add this above your device loop to handle custom heights
$custom_heights = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['custom_height'])) {
    $custom_heights = $_POST['custom_height'];
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)):
    foreach ($device_widths as $device => $width):
        $isLandscape = isset($orientations[$device]) && $orientations[$device] === 'landscape';
        $defaultPortrait = (int)preg_replace('/[^0-9]/', '', $width);
        $defaultLandscape = $device === 'mobile' ? 384 : ($device === 'tablet' ? 1024 : $defaultPortrait);
        $activeWidth = $isLandscape ? $defaultLandscape : $defaultPortrait;

        // Use custom width if provided and valid
        $customWidth = isset($custom_widths[$device]) && is_numeric($custom_widths[$device]) && $custom_widths[$device] > 0
            ? (int)$custom_widths[$device]
            : $activeWidth;

        // Use custom height if provided and valid
        $customHeight = isset($custom_heights[$device]) && is_numeric($custom_heights[$device]) && $custom_heights[$device] > 0
            ? (int)$custom_heights[$device]
            : 600;

        $containerWidth = ($customWidth + 20) . 'px';
?>
    <form method="post" style="display:inline;">
        <input type="hidden" name="url" value="<?php echo htmlspecialchars($url); ?>">
        <?php foreach ($device_widths as $d => $w): ?>
            <input type="hidden" name="orientation[<?php echo $d; ?>]" value="<?php echo isset($orientations[$d]) ? $orientations[$d] : 'portrait'; ?>">
            <input type="hidden" name="custom_width[<?php echo $d; ?>]" value="<?php echo isset($custom_widths[$d]) ? htmlspecialchars($custom_widths[$d]) : ''; ?>">
            <input type="hidden" name="custom_height[<?php echo $d; ?>]" value="<?php echo isset($custom_heights[$d]) ? htmlspecialchars($custom_heights[$d]) : ''; ?>">
        <?php endforeach; ?>
        <div class="frame" style="width: <?php echo $containerWidth; ?>;">
            <div class="chrome-bar">
                <span class="chrome-circles">
                    <span class="chrome-circle red"></span>
                    <span class="chrome-circle yellow"></span>
                    <span class="chrome-circle green"></span>
                </span>
                <span class="chrome-title">
                    <?php echo ucfirst($device); ?> View &mdash; <?php echo $customWidth; ?>px × <?php echo $customHeight; ?>px &mdash; <?php echo htmlspecialchars($url); ?>
                </span>
                <button type="submit" name="orientation[<?php echo $device; ?>]" value="<?php echo $isLandscape ? 'portrait' : 'landscape'; ?>" style="margin-left:16px;">
                    <?php echo $isLandscape ? 'Portrait' : 'Landscape'; ?>
                </button>
                <input type="number" name="custom_width[<?php echo $device; ?>]" value="<?php echo $customWidth; ?>" min="200" max="2000" style="width:80px; margin-left:12px;" title="Custom width for <?php echo $device; ?>">
                <input type="number" name="custom_height[<?php echo $device; ?>]" value="<?php echo $customHeight; ?>" min="200" max="2000" style="width:80px; margin-left:8px;" title="Custom height for <?php echo $device; ?>">
                <button type="submit" style="margin-left:4px;">Set Size</button>
            </div>
            <iframe src="<?php echo htmlspecialchars($url); ?>" style="height: <?php echo $customHeight; ?>px;"></iframe>
            
            <div class="chrome-footer" style="
                background: linear-gradient(90deg, #e0e0e0 0%, #f5f5f5 100%);
                height: 50px;
                display: flex;
                align-items: center;
                border-top: 1px solid #d1d5db;
                border-bottom-left-radius: 8px;
                border-bottom-right-radius: 8px;
                padding: 0 16px;
                margin-top: 8px;
                justify-content: space-between;
            ">
            <div>
               <button type="button" onclick="captureScreenshot(this)" class="capture-btn">
                  <span style="font-size:1.2em;vertical-align:middle;">&#128247;</span>
                  <span style="vertical-align:middle;">Capture</span>
               </button>
               <div class="screenshot-result"></div>
            </div>
            <div>
               <span style="font-size:0.95em; color:#444;">
                  |&nbsp; Powered by Responsive Tool
               </span>
            </div>
            </div>
        </div>
    </form>
<?php 
    endforeach;
endif;
*/
?>

<?php
// Only show the selected device window with user-inputted height and width
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error) && isset($_POST['device']) && isset($_POST['orientation'])):
    $device = $_POST['device'];
    $orientation = $_POST['orientation'];

    // Get custom width and height from user input, fallback to defaults
    $customWidth = isset($_POST['custom_width'][$device]) && is_numeric($_POST['custom_width'][$device]) && $_POST['custom_width'][$device] > 0
        ? (int)$_POST['custom_width'][$device]
        : (int)preg_replace('/[^0-9]/', '', $device_widths[$device]);

    $customHeight = isset($_POST['custom_height'][$device]) && is_numeric($_POST['custom_height'][$device]) && $_POST['custom_height'][$device] > 0
        ? (int)$_POST['custom_height'][$device]
        : 600;

    // Calculate width based on orientation if not custom
    if (!isset($_POST['custom_width'][$device]) || !is_numeric($_POST['custom_width'][$device]) || $_POST['custom_width'][$device] <= 0) {
        if ($orientation === 'landscape') {
            $customWidth = $device === 'mobile' ? 384 : ($device === 'tablet' ? 1024 : $customWidth);
        }
    }

    $containerWidth = ($customWidth + 20) . 'px';
?>
    <div class="frame" style="width: <?php echo $containerWidth; ?>;">
        <div class="chrome-bar">
            <span class="chrome-circles">
                <span class="chrome-circle red"></span>
                <span class="chrome-circle yellow"></span>
                <span class="chrome-circle green"></span>
            </span>
            <span class="chrome-title">
                <?php echo ucfirst($device); ?> View &mdash; <?php echo $customWidth; ?>px × <?php echo $customHeight; ?>px &mdash; <?php echo htmlspecialchars($url); ?> &mdash; <?php echo ucfirst($orientation); ?>
            </span>
        </div>
        <iframe src="<?php echo htmlspecialchars($url); ?>" style="height: <?php echo $customHeight; ?>px;"></iframe>
    </div>
<?php
endif;
?>

<!-- Feature 4: Screenshot Capture Button -->
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
function captureScreenshot(btn) {
    var frame = btn.parentElement.querySelector('iframe');
    var resultDiv = btn.parentElement.querySelector('.screenshot-result');
    // Try to access iframe content (works only for same-origin)
    try {
        html2canvas(frame.contentDocument.body).then(function(canvas) {
            resultDiv.innerHTML = '';
            resultDiv.appendChild(canvas);
        });
    } catch (e) {
        resultDiv.innerHTML = '<span style="color:red;">Screenshot only works for same-origin URLs.</span>';
    }
}

function reloadAllFrames() {
    document.querySelectorAll('.frame iframe').forEach(function(iframe) {
        iframe.contentWindow.location.reload();
    });
}

let scrollSyncEnabled = false;
const toggleScrollSyncButton = document.getElementById('toggle-scroll-sync');

toggleScrollSyncButton.addEventListener('click', function() {
    scrollSyncEnabled = !scrollSyncEnabled;
    this.textContent = `Sync Scroll: ${scrollSyncEnabled ? 'ON' : 'OFF'}`;
    
    const iframes = document.querySelectorAll('.frame iframe');

    if (scrollSyncEnabled) {
        iframes.forEach(function(iframe) {
            iframe.contentWindow.onscroll = function() {
                const scrollTop = iframe.contentWindow.scrollY;
                iframes.forEach(function(other) {
                    if (other !== iframe) {
                        // Check if the other iframe's contentWindow is accessible
                        try {
                            other.contentWindow.scrollTo(0, scrollTop);
                        } catch (e) {
                            // Cross-origin frame, cannot sync scroll
                        }
                    }
                });
            };
        });
    } else {
        iframes.forEach(function(iframe) {
            iframe.contentWindow.onscroll = null; // Disable scroll sync
        });
    }
});
</script>

<?php
$network_profiles = [
    'default' => 'No throttling',
    'slow_3g' => 'Slow 3G (400kbps, 400ms RTT)',
    'fast_3g' => 'Fast 3G (1.6Mbps, 150ms RTT)',
    '4g' => '4G (4Mbps, 20ms RTT)'
];
$selected_network = isset($_POST['network']) ? $_POST['network'] : 'default';
?>
<div style="font-size:0.9em; color:#888; margin:8px 0 0 16px;">
    <strong>Network:</strong> <?php echo $network_profiles[$selected_network]; ?> (Simulated)
</div>

</body>
</html>
