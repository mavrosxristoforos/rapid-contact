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

$label_pos = $params->get('label_pos', '2');

// print anti-spam
if ($params->get('anti_spam_position', 0) == 0) {
  require JModuleHelper::getLayoutPath('mod_rapid_contact', 'default_anti_spam');
}

// print email input
print '<div class="input-group">';
$email_placeholder = ($label_pos == '2') ? ' placeholder="'.$params->get('email_label', 'email@site.com').'"' : '';
if ($label_pos != '2') {
  print '<label for="'.$form_id.'_email">'.$params->get('email_label', 'email@site.com').'</label>';
}
print '<input class="rapid_contact form-control inputbox ' . $email_class . '" type="email" name="rp_email" id="'.$form_id.'_email" size="'.$params->get('email_width', '15').'" value="'.$CORRECT_EMAIL.'" '.$email_placeholder.'/>';
print '</div>';
// print subject input
print '<div class="input-group">';
$subject_placeholder = ($label_pos == '2') ? ' placeholder="'.$params->get('subject_label', 'Subject').'"' : '';
if ($label_pos != '2') {
  print '<label for="'.$form_id.'_subject">'.$params->get('subject_label', 'Subject').'</label>';
}
print '<input class="rapid_contact form-control inputbox" type="text" name="rp_subject" id="'.$form_id.'_subject" size="'.$params->get('subject_width', '15').'" value="'.$CORRECT_SUBJECT.'" '.$subject_placeholder.'/>';
print '</div>';
// print message input
print '<div class="input-group">';
$message_placeholder = ($label_pos == '2') ? ' placeholder="'.$params->get('message_label', 'Your Message').'"' : '';
if ($label_pos != '2') {
  print '<label for="'.$form_id.'_message">'.$params->get('message_label', 'Your Message').'</label>';
}
print '<textarea class="rapid_contact form-control textarea" name="rp_message" id="'.$form_id.'_message" cols="' . $params->get('message_width', '13') . '" rows="4" '.$message_placeholder.'>'.$CORRECT_MESSAGE.'</textarea>';
print '</div>';

//print anti-spam
if ($params->get('anti_spam_position', 0) == 1) {
  require JModuleHelper::getLayoutPath('mod_rapid_contact', 'default_anti_spam');
}

require JModuleHelper::getLayoutPath('mod_rapid_contact', 'default_form_button');