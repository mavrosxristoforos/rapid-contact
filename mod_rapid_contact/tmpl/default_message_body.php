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

use \Joomla\CMS\Factory;

print $email_pretext . $input->get('rp_email', '', 'string') . "\n\n";
print $input->get('rp_message', '', 'string');
