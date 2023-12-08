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

use Joomla\CMS\Captcha\Captcha;
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

if ($input->exists('rp_email')) {
    $CORRECT_SUBJECT = $input->get('rp_subject', '', 'string');
    $CORRECT_MESSAGE = $input->get('rp_message', '', 'string');
    // check anti-spam
    if ($enable_anti_spam == '1') {
        if (strtolower($input->get('rp_anti_spam_answer', '', 'string')) != strtolower($myAntiSpamAnswer)) {
            $myError = '<span style="color: ' . $error_text_color . ';">' . $wrongantispamanswer . '</span>';
        } else {
            $CORRECT_ANTISPAM_ANSWER = $input->get('rp_anti_spam_answer', '', 'string');
        }
    } else if ($enable_anti_spam == '2') {
        if (Factory::getConfig()->get('captcha') != '0') {
            $captcha = Captcha::getInstance(Factory::getConfig()->get('captcha'));
            try {
                if (!$captcha->checkAnswer(Factory::getApplication()->input->get('rp_recaptcha', null, 'string'))) {
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
        $myError     = '<span style="color: ' . $error_text_color . ';">' . $noEmail . '</span>';
        $email_class = ' has-error';
    }
    if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,24})$/", strtolower($posted_email))) {
        $myError     = '<span style="color: ' . $error_text_color . ';">' . $invalidEmail . '</span>';
        $email_class = ' has-error';
    } else {
        $CORRECT_EMAIL = $posted_email;
    }

    if ($myError == '') {
        $mySubject     = $input->get('rp_subject', '', 'string');

        $mailSender = Factory::getMailer();
        $mailSender->addRecipient($recipient);

        $mailSender->setSender(array($fromEmail,$fromName));
        $mailSender->addReplyTo($posted_email);

        $mailSender->setSubject($mySubject);

        ob_start();
        require ModuleHelper::getLayoutPath('mod_rapid_contact', 'default_message_body');
        $myMessage = ob_get_clean();
        $mailSender->setBody($myMessage);


        if ($mailSender->Send() !== true) {
            $myReplacement = '<span style="color: ' . $error_text_color . ';">' . $errorText . '</span>';
            print $myReplacement;
            return true;
        } else {
            require ModuleHelper::getLayoutPath('mod_rapid_contact', 'default_thank_you');
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

$document = Factory::getDocument();
$document->addStyleDeclaration(
    '
        .rapid_contact .form-control { max-width: 95%; margin-bottom: 8px; }
        .rapid_contact .g-recaptcha { margin-bottom: 10px; max-width: 95%; }
    '
);
if ($addCSS != '') {
    $document->addStyleDeclaration($addCSS);
}
$document->addScriptDeclaration(
    'function rp_checkCaptcha(form_id) {
        result = true;
        if (document.getElementById(form_id+"_hasCaptcha")) {
            if ((grecaptcha) && (jQuery(".g-recaptcha").length == 1)) { // We only know how to deal with Google ReCaptcha, and only one of it in JS
                if (grecaptcha.getResponse().length == 0) {
                    alert("'.$please_complete_captcha_text.'");
                    result = false;
                }
            }
        }
        return result;
    }'
);

$form_id = 'rp_'.random_int(1, 999999);
print '<div class="rapid_contact ' . $mod_class_suffix . '"><form '.$url.' id="'.$form_id.'" method="post" onSubmit="return rp_checkCaptcha(\''.$form_id.'\');">' . "\n" .
      '<div class="rapid_contact intro_text ' . $mod_class_suffix . '">'.$pre_text.'</div>' . "\n";

if ($myError != '') {
    print $myError; 
}

print '<div class="rapid_contact_form" id="rapid_contact_form_'.$form_id.'">';

$anti_spam_field = '';
if ($enable_anti_spam == '2') {
    $anti_spam_field = (Factory::getConfig()->get('captcha') != '0') ? Captcha::getInstance(Factory::getConfig()->get('captcha'))->display('rp_recaptcha', 'rp_recaptcha', 'g-recaptcha') : '';
    $anti_spam_field .= '<input type="hidden" name="'.$form_id.'_hasCaptcha" id="'.$form_id.'_hasCaptcha" value="true"/>';
} else if ($enable_anti_spam == '1') {
    // Label as Placeholder option is intentionally overlooked.
    $anti_spam_field = '<label for="'.$form_id.'_as_answer">'.$myAntiSpamQuestion.'</label><input class="rapid_contact form-control inputbox ' . $mod_class_suffix . '" type="text" name="rp_anti_spam_answer" id="'.$form_id.'_as_answer" size="' . $emailWidth . '" value="'.$CORRECT_ANTISPAM_ANSWER.'"/>';
}

require ModuleHelper::getLayoutPath('mod_rapid_contact', 'default_form');
