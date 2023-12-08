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
\defined( '_JEXEC' ) or die( 'Restricted access' );

use Joomla\CMS\Helper\ModuleHelper;

// print anti-spam
if ($anti_spam_position == 0) {
    require ModuleHelper::getLayoutPath('mod_rapid_contact', 'default_anti_spam');
}

// print email input
print '<div class="input-group">';
$email_placeholder = ($label_pos == '2') ? ' placeholder="'.$myEmailLabel.'"' : '';
if ($label_pos != '2') {
    print '<label for="'.$form_id.'_email">'.$myEmailLabel.'</label>';
}
print '<input class="rapid_contact form-control inputbox ' . $email_class . $mod_class_suffix . '" type="email" name="rp_email" id="'.$form_id.'_email" size="'.$emailWidth.'" value="'.$CORRECT_EMAIL.'" '.$email_placeholder.'/>';
print '</div>';

// print subject input
print '<div class="input-group">';
$subject_placeholder = ($label_pos == '2') ? ' placeholder="'.$mySubjectLabel.'"' : '';
if ($label_pos != '2') {
    print '<label for="'.$form_id.'_subject">'.$mySubjectLabel.'</label>';
}
print '<input class="rapid_contact form-control inputbox ' . $mod_class_suffix . '" type="text" name="rp_subject" id="'.$form_id.'_subject" size="'.$subjectWidth.'" value="'.$CORRECT_SUBJECT.'" '.$subject_placeholder.'/>';
print '</div>';

// print message input
print '<div class="input-group">';
$message_placeholder = ($label_pos == '2') ? ' placeholder="'.$myMessageLabel.'"' : '';
if ($label_pos != '2') {
    print '<label for="'.$form_id.'_message">'.$myMessageLabel.'</label>';
}
print '<textarea class="rapid_contact form-control textarea ' . $mod_class_suffix . '" name="rp_message" id="'.$form_id.'_message" cols="' . $messageWidth . '" rows="4" '.$message_placeholder.'>'.$CORRECT_MESSAGE.'</textarea>';
print '</div>';

//print anti-spam
if ($anti_spam_position == 1) {
    require ModuleHelper::getLayoutPath('mod_rapid_contact', 'default_anti_spam');
}

require ModuleHelper::getLayoutPath('mod_rapid_contact', 'default_form_button');

