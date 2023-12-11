<?php

/**
 * ------------------------------------------------------------------------
 * mod_rapid_contact - Rapid Contact
 * ------------------------------------------------------------------------
 * 
 * @author    Christopher Mavros - Mavrosxristoforos.com
 * @copyright Copyright (C) 2008 Mavrosxristoforos.com. All Rights Reserved.
 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites:  https://mavrosxristoforos.com
 * Technical  Support:  Forum - https://mavrosxristoforos.com/support/forum
 * ------------------------------------------------------------------------
 */

// no direct access
\defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;

//Email Parameters
$recipient = $params->get('email_recipient', 'email@email.com');
$fromName  = $params->get('from_name', 'Rapid Contact');
$fromEmail = ($params->get('from_email', 'rapid_contact@yoursite.com') == 'rapid_contact@yoursite.com') ? Factory::getApplication()->getCfg('mailfrom') : $params->get('from_email', 'rapid_contact@yoursite.com');

// Text Parameters
$myEmailLabel        = $params->get('email_label', 'email@site.com');
$mySubjectLabel      = $params->get('subject_label', 'Subject');
$myMessageLabel      = $params->get('message_label', 'Your Message');
$buttonText          = $params->get('button_text', 'Send Message');
$pageText            = $params->get('page_text', 'Thank you for your contact.');
$errorText           = $params->get('error_text', 'Your message could not be sent. Please try again.');
$noEmail             = $params->get('no_email', 'Please write your email.');
$invalidEmail        = $params->get('invalid_email', 'Please write a valid email.');
$wrongantispamanswer = $params->get('wrong_antispam', 'Wrong anti-spam answer.');
$pre_text            = $params->get('pre_text', '');
$email_pretext       = $params->get('email_pretext', 'You received a message from ');

// Format Parameters
$thanksTextColor  = $params->get('thank_text_color', '#FF0000');
$error_text_color = $params->get('error_text_color', '#FF0000');
$emailWidth       = $params->get('email_width', '15');
$subjectWidth     = $params->get('subject_width', '15');
$messageWidth     = $params->get('message_width', '13');
$buttonWidth      = $params->get('button_width', '100');
$label_pos        = $params->get('label_pos', '2');
$addCSS           = $params->get('addcss', '');

// Anti-spam Parameters
$enable_anti_spam             = $params->get('enable_anti_spam', '1');
$myAntiSpamQuestion           = $params->get('anti_spam_q', 'How many eyes has a typical person?');
$myAntiSpamAnswer             = $params->get('anti_spam_a', '2');
$anti_spam_position           = $params->get('anti_spam_position', 0);
$please_complete_captcha_text = $params->get('please_complete_captcha_text', 'Please complete the Captcha');

// Advanced
$mod_class_suffix = $params->get('moduleclass_sfx', '');
$buttonClass      = $params->get('button_class', 'btn btn-primary');
$url              = $params->get('fixed_url', false) ? 'action="' . $params->get('fixed_url_address', '') . '"' : '';

$myError                 = '';
$CORRECT_ANTISPAM_ANSWER = '';
$CORRECT_EMAIL           = '';
$CORRECT_SUBJECT         = '';
$CORRECT_MESSAGE         = '';
$email_class             = '';

$input = Factory::getApplication()->input;

require ModuleHelper::getLayoutPath('mod_rapid_contact', 'default');
