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
use Joomla\CMS\Captcha\Captcha;

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
            print '<span style="color: ' . $error_text_color . ';">' . $errorText . '</span>';
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
        input.rapid_contact.inputanswer.form-control { max-width: fit-content; margin: 0 0 8px 8px !important; border-radius: var(--border-radius) !important; }
        .rapid_contact span { display: inline-block; margin-bottom: 8px;}
        .rapid_contact .g-recaptcha { max-width: 95%;  margin-bottom: 10px;}
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

require ModuleHelper::getLayoutPath('mod_rapid_contact', 'default_form');

print '</div>';
