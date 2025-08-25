
<div class="top-bar">
    <form method="post" class="top-bar-form">
      <table class="table-form">
         <thead>
            <tr>
               <th><label for="url">URL</label></th>
               <th><label for="device">Device</label></th>
               <th><label for="orientation">Orientation:</label></th>
               <th><label for="user_agent">User Agent:</label></th>
               <th><label for="network">Network:</label></th>
               <th colspan="4"><label for="width">Buttons</label></th>
            </tr>
         </thead>
         <tbody>
            <tr>
               <td>
                  <input type="text" id="width" name="width" value="<?php echo isset($_POST['width']) ? htmlspecialchars($_POST['width']) : ''; ?>" style="width:100px;">
               </td>
               <td>
                  <select name="device" id="device">
                     <?php foreach ($device_widths as $device => $width): ?>
                        <option value="<?php echo $device; ?>" <?php echo (isset($_POST['device']) && $_POST['device'] === $device) ? 'selected' : ''; ?>>
                           <?php echo ucfirst($device); ?>
                        </option>
                     <?php endforeach; ?>
                  </select>
               </td>
               <td>  
               <select name="orientation" id="orientation">
                     <option value="landscape" <?php echo (isset($_POST['orientation']) && $_POST['orientation'] === 'landscape') ? 'selected' : ''; ?>>Landscape</option>
                     <option value="portrait" <?php echo (isset($_POST['orientation']) && $_POST['orientation'] === 'portrait') ? 'selected' : ''; ?>>Portrait</option>
                  </select>
               </td>
               <td>
                  <select name="user_agent" id="user_agent">
                     <option value="default" <?php echo (isset($_POST['user_agent']) && $_POST['user_agent'] === 'default') ? 'selected' : ''; ?>>Default</option>
                     <option value="chrome_mobile" <?php echo (isset($_POST['user_agent']) && $_POST['user_agent'] === 'chrome_mobile') ? 'selected' : ''; ?>>Chrome Mobile</option>
                     <option value="safari_ios" <?php echo (isset($_POST['user_agent']) && $_POST['user_agent'] === 'safari_ios') ? 'selected' : ''; ?>>Safari iOS</option>
                     <option value="firefox_android" <?php echo (isset($_POST['user_agent']) && $_POST['user_agent'] === 'firefox_android') ? 'selected' : ''; ?>>Firefox Android</option>
               </select>
               </td>
               <td>
               <select name="network" id="network">
                     <option value="default" <?php echo (isset($_POST['network']) && $_POST['network'] === 'default') ? 'selected' : ''; ?>>Default</option>
                     <option value="slow_3g" <?php echo (isset($_POST['network']) && $_POST['network'] === 'slow_3g') ? 'selected' : ''; ?>>Slow 3G</option>
                     <option value="fast_3g" <?php echo (isset($_POST['network']) && $_POST['network'] === 'fast_3g') ? 'selected' : ''; ?>>Fast 3G</option>
                     <option value="4g" <?php echo (isset($_POST['network']) && $_POST['network'] === '4g') ? 'selected' : ''; ?>>4G</option>
               </select>
               </td>
               <td>
               <button type="submit" style="padding: 8px 18px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 1em; font-weight: 500; cursor: pointer;">
                     Show Responsive View
               </button>
               </td>
               <td>
               <button type="button" onclick="reloadAllFrames()" style="padding: 8px 18px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 1em; font-weight: 500; cursor: pointer;">
                     &#x21bb; Reload All Frames
               </button>
               </td>
               <td>
               <button type="button" id="toggle-accessibility" style="background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 1em; font-weight: 500; cursor: pointer; padding: 8px 18px;">
                     🦾 Accessibility Overlay
               </button>
               </td>
               <td>
               <label style="display:flex;flex-direction:row;align-items:center;">
                     <input type="checkbox" id="scroll-sync" style="margin-right:6px;">
                     Sync Scroll
               </label>
               <!-- <button type="button" id="toggle-dark-mode" style="padding: 8px 18px; background: #2563eb; color: #fff; border: none; border-radius: 6px; font-size: 1em; font-weight: 500; cursor: pointer;">
                     🌙 Dark Mode
               </button> -->
               </td>

            </tr>
         </tbody>
         <tfoot>
            <tr>
               <td>
               <?php if (!empty($error)): ?>
                  <div style="color:red;"><?php echo $error; ?></div>
               <?php endif; ?>
            </td>
         </tr>
         </tfoot>
      </table>
   </form>
</div>
