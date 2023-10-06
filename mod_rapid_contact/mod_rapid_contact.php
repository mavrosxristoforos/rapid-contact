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

use \Joomla\CMS\Factory;

$recipient = $params->get('email_recipient', 'email@email.com');
$wrongantispamanswer = $params->get('wrong_antispam', 'Wrong anti-spam answer');
$error_text_color = $params->get('error_text_color', '#FF0000');
$url = $params->get('fixed_url', false) ? 'action="' . $params->get('fixed_url_address', '') . '"' : '';

$input = Factory::getApplication()->input;

$myError = '';
$CORRECT_ANTISPAM_ANSWER = '';
$CORRECT_EMAIL = '';
$CORRECT_SUBJECT = '';
$CORRECT_MESSAGE = '';
$email_class = '';

if ($input->exists('rp_email')) {
  $CORRECT_SUBJECT = $input->get('rp_subject', '', 'string');
  $CORRECT_MESSAGE = $input->get('rp_message', '', 'string');
  // check anti-spam
  if ($params->get('enable_anti_spam', '1') == '1') {
    if (strtolower($input->get('rp_anti_spam_answer', '', 'string')) != strtolower($params->get('anti_spam_a', '2'))) {
      $myError = '<span style="color: ' . $error_text_color . ';">' . $wrongantispamanswer . '</span>';
    }
    else {
      $CORRECT_ANTISPAM_ANSWER = $input->get('rp_anti_spam_answer', '', 'string');
    }
  }
  else if ($params->get('enable_anti_spam', '1') == '2') {
    if (JFactory::getConfig()->get('captcha') != '0') {
      $captcha = JCaptcha::getInstance(JFactory::getConfig()->get('captcha'));
      try {
        if (!$captcha->checkAnswer(JFactory::getApplication()->input->get('rp_recaptcha', null, 'string'))) {
          $myError = '<span style="color: ' . $error_text_color . ';">' . $wrongantispamanswer . '</span>';
        }
      }
      catch(RuntimeException $e) {
        $myError = '<span style="color: ' . $error_text_color . ';">' . $wrongantispamanswer . '</span>';
      }
    }
  }
  // check email
  $posted_email = $input->get('rp_email', '', 'string');
  if ($posted_email === '') {
    $myError = '<span style="color: ' . $error_text_color . ';">' . $params->get('no_email', 'Please write your email') . '</span>';
    $email_class = ' has-error';
  }
  if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,24})$/", strtolower($posted_email))) {
    $myError = '<span style="color: ' . $error_text_color . ';">' . $params->get('invalid_email', 'Please write a valid email') . '</span>';
    $email_class = ' has-error';
  }
  else {
    $CORRECT_EMAIL = $posted_email;
  }

  if ($myError == '') {
    $mailSender = JFactory::getMailer();
    $mailSender->addRecipient($recipient);

    $from_email = ($params->get('from_email', 'rapid_contact@yoursite.com') == 'rapid_contact@yoursite.com') ? JFactory::getApplication()->getCfg('mailfrom') : $params->get('from_email', 'rapid_contact@yoursite.com');

    $mailSender->setSender(array($from_email, $params->get('from_name', 'Rapid Contact')));
    if(version_compare(JVERSION, '3.5', 'ge')) {
      $mailSender->addReplyTo($posted_email, $params->get('from_name', 'Rapid Contact'));
    }
    else {
      $mailSender->addReplyTo(array( $posted_email, $params->get('from_name', 'Rapid Contact') ));
    }

    $mailSender->setSubject($input->get('rp_subject', '', 'string'));

    ob_start();
    require JModuleHelper::getLayoutPath('mod_rapid_contact', 'default_message_body');
    $myMessage = ob_get_clean();
    $mailSender->setBody($myMessage);

    if ($mailSender->Send() !== true) {
      print '<span style="color: ' . $error_text_color . ';">' . $params->get('error_text', 'Your message could not be sent. Please try again.') . '</span>';
      return true;
    }
    else {
      require JModuleHelper::getLayoutPath('mod_rapid_contact', 'default_thank_you');
      return true;
    }

  }
} // end if posted

// check recipient
if ($recipient === "email@email.com") {
  print '<span style="color: ' . $error_text_color . ';">Your form recipient is email@email.com. Please change that in the Rapid Contact module options.</span>';
  return true;
}

require JModuleHelper::getLayoutPath('mod_rapid_contact');