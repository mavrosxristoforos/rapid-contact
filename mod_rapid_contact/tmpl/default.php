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

$document = JFactory::getDocument();
$document->addStyleDeclaration('
  .rapid_contact .form-control { max-width: 95%; margin-bottom: 8px; }
  .rapid_contact .g-recaptcha { margin-bottom: 10px; max-width: 95%; }
');
if ($params->get('addcss', '') != '') {
  $document->addStyleDeclaration($params->get('addcss', ''));
}
$document->addScriptDeclaration('
  function rp_checkCaptcha(form_id) {
    result = true;
    if (document.getElementById(form_id+"_hasCaptcha")) {
      if ((grecaptcha) && (jQuery(".g-recaptcha").length == 1)) { // We only know how to deal with Google ReCaptcha, and only one of it in JS
        if (grecaptcha.getResponse().length == 0) {
          alert("'.$params->get('please_complete_captcha_text', 'Please complete the Captcha').'");
          result = false;
        }
      }
    }
    return result;
  }
');

$form_id = 'rp_'.random_int(1,999999);
?>
<div class="rapid_contact '<?php print $params->get('moduleclass_sfx', ''); ?>">
  <form <?php print $url; ?> id="<?php print $form_id; ?>" method="post" onSubmit="return rp_checkCaptcha('<?php print $form_id; ?>');">

    <?php if ($params->get('pre_text', '') != '') {
      print '<div class="rapid_contact intro_text">'.$params->get('pre_text', '').'</div>';
    } ?>

    <?php if ($myError != '') { print $myError; } ?>

    <div class="rapid_contact_form" id="rapid_contact_form_<?php print $form_id; ?>">
      <?php require JModuleHelper::getLayoutPath('mod_rapid_contact', 'default_form'); ?>
    </div>
  </form>
</div>