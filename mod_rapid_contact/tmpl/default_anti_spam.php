<?php
/*------------------------------------------------------------------------
# mod_rapid_contact - Rapid Contact
# ------------------------------------------------------------------------
# author    Christopher Mavros - Mavrosxristoforos.com
# copyright Copyright (C) 2008 Mavrosxristoforos.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: https://mavrosxristoforos.com
# Technical Support:  Forum - https://mavrosxristoforos.com/support/forum
-------------------------------------------------------------------------*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

?>
<div class="input-group">
  <?php if ($params->get('enable_anti_spam', '1') == '2') { ?>
    <?php if (JFactory::getConfig()->get('captcha') != '0') {
      print JCaptcha::getInstance(JFactory::getConfig()->get('captcha'))->display('rp_recaptcha', 'rp_recaptcha', 'g-recaptcha');
    ?>
      <input type="hidden" name="<?php print $form_id; ?>'_hasCaptcha" id="<?php print $form_id; ?>_hasCaptcha" value="true"/>
    <?php } ?>
  <?php } else if ($params->get('enable_anti_spam', '1') == '1') { // Label as Placeholder option is intentionally overlooked. ?>
    <label for="<?php print $form_id; ?>'_as_answer"><?php print $params->get('anti_spam_q', 'How many eyes has a typical person?'); ?></label>
    <input class="rapid_contact form-control inputbox" type="text" name="rp_anti_spam_answer" id="<?php print $form_id; ?>_as_answer" size="<?php print $params->get('email_width', '15'); ?>" value="<?php print $CORRECT_ANTISPAM_ANSWER; ?>"/>
  <?php } ?>
</div>