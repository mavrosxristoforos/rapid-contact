<?php
/*------------------------------------------------------------------------
# mod_rapid_contact - Rapid Contact
# ------------------------------------------------------------------------
# author    Christopher Mavros - Mavrosxristoforos.com
# copyright Copyright (C) 2008 Mavrosxristoforos.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.mavrosxristoforos.com
# Technical Support:  Forum - http://www.mavrosxristoforos.com/support/forum
-------------------------------------------------------------------------*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

//Email Parameters
$recipient = $params->get('email_recipient', 'email@email.com');
$fromName = @$params->get('from_name', 'Rapid Contact');
$fromEmail = @$params->get('from_email', 'rapid_contact@yoursite.com');

// Text Parameters
$myEmailLabel = $params->get('email_label', 'email@site.com');
$mySubjectLabel = $params->get('subject_label', 'Subject');
$myMessageLabel = $params->get('message_label', 'Your Message');
$buttonText = $params->get('button_text', 'Send Message');
$pageText = $params->get('page_text', 'Thank you for your contact.');
$errorText = $params->get('error_text', 'Your message could not be sent. Please try again.');
$noEmail = $params->get('no_email', 'Please write your email');
$invalidEmail = $params->get('invalid_email', 'Please write a valid email');
$wrongantispamanswer = $params->get('wrong_antispam', 'Wrong anti-spam answer');
$pre_text = $params->get('pre_text', '');

// Size and Color Parameters
$thanksTextColor = $params->get('thank_text_color', '#FF0000');
$error_text_color = $params->get('error_text_color', '#FF0000');
$emailWidth = $params->get('email_width', '15');
$subjectWidth = $params->get('subject_width', '15');
$messageWidth = $params->get('message_width', '13');
$buttonWidth = $params->get('button_width', '100');
$label_pos = $params->get('label_pos', '2');

// Anti-spam Parameters
$enable_anti_spam = $params->get('enable_anti_spam', '1');
$myAntiSpamQuestion = $params->get('anti_spam_q', 'How many eyes has a typical person?');
$myAntiSpamAnswer = $params->get('anti_spam_a', '2');
$anti_spam_position = $params->get('anti_spam_position', 0);

// Module Class Suffix Parameter
$mod_class_suffix = $params->get('moduleclass_sfx', '');

$url = $params->get('fixed_url', false) ? 'action="' . $params->get('fixed_url_address', '') . '"' : '';

$myError = '';
$CORRECT_ANTISPAM_ANSWER = '';
$CORRECT_EMAIL = '';
$CORRECT_SUBJECT = '';
$CORRECT_MESSAGE = '';
$email_class = '';

if (isset($_POST["rp_email"])) {
  $CORRECT_SUBJECT = htmlentities($_POST["rp_subject"], ENT_COMPAT, "UTF-8");
  $CORRECT_MESSAGE = htmlentities($_POST["rp_message"], ENT_COMPAT, "UTF-8");
  // check anti-spam
  if ($enable_anti_spam == '1') {
    if (strtolower($_POST["rp_anti_spam_answer"]) != strtolower($myAntiSpamAnswer)) {
      $myError = '<span style="color: ' . $error_text_color . ';">' . $wrongantispamanswer . '</span>';
    }
    else {
      $CORRECT_ANTISPAM_ANSWER = htmlentities($_POST["rp_anti_spam_answer"], ENT_COMPAT, "UTF-8");
    }
  }
  else if ($enable_anti_spam == '2') {
    // check captcha plugin.
    JPluginHelper::importPlugin('captcha');
    $d = JEventDispatcher::getInstance();
    $res = $d->trigger('onCheckAnswer', 'not_used');
    if( (!isset($res[0])) || (!$res[0]) ){
      $myError = '<span style="color: ' . $error_text_color . ';">' . $wrongantispamanswer . '</span>';
    }
  }
  // check email
  if ($_POST["rp_email"] === "") {
    $myError = '<span style="color: ' . $error_text_color . ';">' . $noEmail . '</span>';
    $email_class = ' has-error';
  }
  if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", strtolower($_POST["rp_email"]))) {
    $myError = '<span style="color: ' . $error_text_color . ';">' . $invalidEmail . '</span>';
    $email_class = ' has-error';
  }
  else {
    $CORRECT_EMAIL = htmlentities($_POST["rp_email"], ENT_COMPAT, "UTF-8");
  }

  if ($myError == '') {
    $mySubject = $_POST["rp_subject"];
    $myMessage = 'You received a message from '. $_POST["rp_email"] ."\n\n". $_POST["rp_message"];

    $mailSender = JFactory::getMailer();
    $mailSender->addRecipient($recipient);

    $mailSender->setSender(array($fromEmail,$fromName));
    if(version_compare(JVERSION, '3.5', 'ge')) {
      $mailSender->addReplyTo($_POST["rp_email"], $fromName);
    }
    else {
      $mailSender->addReplyTo(array( $_POST["rp_email"], $fromName ));
    }

    $mailSender->setSubject($mySubject);
    $mailSender->setBody($myMessage);

    if ($mailSender->Send() !== true) {
      $myReplacement = '<span style="color: ' . $error_text_color . ';">' . $errorText . '</span>';
      print $myReplacement;
      return true;
    }
    else {
      $myReplacement = '<span style="color: '.$thanksTextColor.';">' . $pageText . '</span>';
      print $myReplacement;
      return true;
    }

  }
} // end if posted

// check recipient
if ($recipient === "email@email.com") {
  $myReplacement = '<span style="color: ' . $error_text_color . ';">Your form recipient is email@email.com. Please change that in the Rapid Contact module options.</span>';
  print $myReplacement;
  return true;
}

$document = JFactory::getDocument();
$document->addStyleDeclaration('
  .rapid_contact .form-control { max-width: 95%; }
  .rapid_contact .g-recaptcha { margin-bottom: 10px; max-width: 95%; }
');
if ($params->get('addcss', '') != '') {
  $document->addStyleDeclaration($params->get('addcss', ''));
}

$form_id = 'rp_'.uniqid();
print '<div class="rapid_contact ' . $mod_class_suffix . '"><form '.$url.' id="'.$form_id.'" method="post">' . "\n" .
      '<div class="rapid_contact intro_text ' . $mod_class_suffix . '">'.$pre_text.'</div>' . "\n";

if ($myError != '') { print $myError; }

print '<div class="rapid_contact_form" id="rapid_contact_form_'.$form_id.'">';

$anti_spam_field = '';
if ($enable_anti_spam == '2') {
  $anti_spam_field = (JFactory::getConfig()->get('captcha') != '0') ? JCaptcha::getInstance(JFactory::getConfig()->get('captcha'))->display('rp_recaptcha', 'rp_recaptcha', 'g-recaptcha') : '';
}
else if ($enable_anti_spam == '1') {
  // Label as Placeholder option is intentionally overlooked.
  $anti_spam_field = '<label for="'.$form_id.'_as_answer">'.$myAntiSpamQuestion.'</label><input class="rapid_contact form-control inputbox ' . $mod_class_suffix . '" type="text" name="rp_anti_spam_answer" id="'.$form_id.'_as_answer" size="' . $emailWidth . '" value="'.$CORRECT_ANTISPAM_ANSWER.'"/>';
}

// print anti-spam
if ($anti_spam_position == 0) {
  print '<div class="input-group">'.$anti_spam_field.'</div>';
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
  print '<div class="input-group">'.$anti_spam_field.'</div>';
}
// print button
print '<div class="input-group">';
print '<input class="rapid_contact btn btn-primary button ' . $mod_class_suffix . '" type="submit" value="' . $buttonText . '"/>';
print '</div>';
print '</div></form></div>';
return true;
