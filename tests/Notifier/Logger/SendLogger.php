<?php
/**
 * This file is part of the NotifierSwiftMailer package.
 *
 * (c) Dries De Peuter <dries@nousefreak.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Notifier\Logger;

use Notifier\Message\Message;

/**
 * @author Dries De Peuter <dries@nousefreak.be>
 */
class SendLogger extends \Swift_Plugins_LoggerPlugin implements \Swift_Events_SendListener
{
    /**
     * Invoked immediately before the Message is sent.
     *
     * @param \Swift_Events_SendEvent $evt
     */
    public function beforeSendPerformed(\Swift_Events_SendEvent $evt)
    {
        // ignoring
    }

    /**
     * Invoked immediately after the Message is sent.
     *
     * @param \Swift_Events_SendEvent $evt
     */
    public function sendPerformed(\Swift_Events_SendEvent $evt)
    {
        $to = $evt->getMessage()->getTo();
        $this->add(sprintf("send to: %s", implode(',', $to)));
    }

}
