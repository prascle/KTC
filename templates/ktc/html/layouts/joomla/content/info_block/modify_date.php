<?php

/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2013 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/*
 * KTC — override du layout « date de modification » (décision M02 du 06/11) :
 * la date ne s'affiche que si elle est plus récente que la date de création.
 * Copie du layout noyau (Joomla 6.2.0) avec cet écart seul.
 */

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

?>
<?php if ($displayData['item']->modified > $displayData['item']->created) : ?>
<dd class="modified">
    <span class="icon-calendar icon-fw" aria-hidden="true"></span>
    <time datetime="<?php echo HTMLHelper::_('date', $displayData['item']->modified, 'c'); ?>">
        <?php echo Text::sprintf('COM_CONTENT_LAST_UPDATED', HTMLHelper::_('date', $displayData['item']->modified, Text::_('DATE_FORMAT_LC3'))); ?>
    </time>
</dd>
<?php endif; ?>
