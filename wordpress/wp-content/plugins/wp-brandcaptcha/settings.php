<?php

    if (defined('ALLOW_INCLUDE') === false)
        die('no direct access');

?>

<div class="wrap">
   <a name="brandcaptcha"></a>
   <h2><?php _e('BrandCaptcha Options', 'brandcaptcha'); ?></h2>
   <p><?php _e('BrandCaptcha is a CAPTCHA service.', 'brandcaptcha'); ?></p>
   <p><?php _e('For details, visit ', 'brandcaptcha'); ?><a href="http://www.pontamedia.com" target="_blank"><?php _e('Pontamedia website', 'brandcaptcha'); ?></a></p>

   <form method="post" action="options.php">
      <?php settings_fields('brandcaptcha_options_group'); ?>

      <h3><?php _e('Authentication', 'brandcaptcha'); ?></h3>
      <p><?php _e('These keys are required before you are able to do anything else.', 'brandcaptcha'); ?>
         <?php _e('You can get the keys', 'brandcaptcha'); ?> <a href="http://www.pontamedia.com" target="_blank"><?php _e('here', 'brandcaptcha'); ?></a>.</p>
      <p><?php _e('Be sure not to mix them up! The public and private keys are not interchangeable!'); ?></p>

      <table class="form-table">
         <tr valign="top">
           <th scope="row"><?php _e('Public Key', 'brandcaptcha'); ?></th>
            <td>
               <input type="text" name="brandcaptcha_options[public_key]" size="40" value="<?php echo $this->options['public_key']; ?>" />
            </td>
         </tr>
         <tr valign="top">
            <th scope="row"><?php _e('Private Key', 'brandcaptcha'); ?></th>
            <td>
               <input type="text" name="brandcaptcha_options[private_key]" size="40" value="<?php echo $this->options['private_key']; ?>" />
            </td>
         </tr>
      </table>
      
      <h3><?php _e('Comment Options', 'brandcaptcha'); ?></h3>
      <table class="form-table">
         <tr valign="top">
            <th scope="row"><?php _e('Activation', 'brandcaptcha'); ?></th>
            <td>
               <input type="checkbox" id ="brandcaptcha_options[show_in_comments]" name="brandcaptcha_options[show_in_comments]" value="1" <?php checked('1', $this->options['show_in_comments']); ?> />
               <label for="brandcaptcha_options[show_in_comments]"><?php _e('Enable for comments form', 'brandcaptcha'); ?></label>
            </td>
         </tr>
         
         <tr valign="top">
            <th scope="row"><?php _e('Target', 'brandcaptcha'); ?></th>
            <td>
               <input type="checkbox" id="brandcaptcha_options[bypass_for_registered_users]" name="brandcaptcha_options[bypass_for_registered_users]" value="1" <?php checked('1', $this->options['bypass_for_registered_users']); ?> />
               <label for="brandcaptcha_options[bypass_for_registered_users]"><?php _e('Hide for Registered Users who can', 'brandcaptcha'); ?></label>
               <?php $this->capabilities_dropdown(); ?>
            </td>
         </tr>

         

         <tr valign="top">
            <th scope="row"><?php _e('Tab Index', 'brandcaptcha'); ?></th>
            <td>
               <input type="text" name="brandcaptcha_options[comments_tab_index]" size="10" value="<?php echo $this->options['comments_tab_index']; ?>" />
            </td>
         </tr>
      </table>
      
      <h3><?php _e('Registration Options', 'brandcaptcha'); ?></h3>
      <table class="form-table">
         <tr valign="top">
            <th scope="row"><?php _e('Activation', 'brandcaptcha'); ?></th>
            <td>
               <input type="checkbox" id ="brandcaptcha_options[show_in_registration]" name="brandcaptcha_options[show_in_registration]" value="1" <?php checked('1', $this->options['show_in_registration']); ?> />
               <label for="brandcaptcha_options[show_in_registration]"><?php _e('Enable for registration form', 'brandcaptcha'); ?></label>
            </td>
         </tr>
         
         
         
         <tr valign="top">
            <th scope="row"><?php _e('Tab Index', 'brandcaptcha'); ?></th>
            <td>
               <input type="text" name="brandcaptcha_options[registration_tab_index]" size="10" value="<?php echo $this->options['registration_tab_index']; ?>" />
            </td>
         </tr>
      </table>
      
      <h3><?php _e('General Options', 'brandcaptcha'); ?></h3>
      <table class="form-table">
         <tr valign="top">
            <th scope="row"><?php _e('brandCAPTCHA Form', 'brandcaptcha'); ?></th>
            <td>
               <label for="brandcaptcha_options[brandcaptcha_language]"><?php _e('Language:', 'brandcaptcha'); ?></label>
               <?php $this->brandcaptcha_language_dropdown(); ?>
            </td>
         </tr>
         
         <tr valign="top">
            <th scope="row"><?php _e('Standards Compliance', 'brandcaptcha'); ?></th>
            <td>
               <input type="checkbox" id ="brandcaptcha_options[xhtml_compliance]" name="brandcaptcha_options[xhtml_compliance]" value="1" <?php checked('1', $this->options['xhtml_compliance']); ?> />
               <label for="brandcaptcha_options[xhtml_compliance]"><?php _e('Produce XHTML 1.0 Strict Compliant Code', 'brandcaptcha'); ?></label>
            </td>
         </tr>
      </table>
      
      <h3><?php _e('Error Messages', 'brandcaptcha'); ?></h3>
      <table class="form-table">
         <tr valign="top">
            <th scope="row"><?php _e('brandCAPTCHA Ignored', 'brandcaptcha'); ?></th>
            <td>
               <input type="text" name="brandcaptcha_options[no_response_error]" size="70" value="<?php echo $this->options['no_response_error']; ?>" />
            </td>
         </tr>
         
         <tr valign="top">
            <th scope="row"><?php _e('Incorrect Guess', 'brandcaptcha'); ?></th>
            <td>
               <input type="text" name="brandcaptcha_options[incorrect_response_error]" size="70" value="<?php echo $this->options['incorrect_response_error']; ?>" />
            </td>
         </tr>
      </table>

      <p class="submit"><input type="submit" class="button-primary" title="<?php _e('Save brandCAPTCHA Options') ?>" value="<?php _e('Save brandCAPTCHA Changes') ?> &raquo;" /></p>
   </form>
   
   <?php do_settings_sections('brandcaptcha_options_page'); ?>
</div>