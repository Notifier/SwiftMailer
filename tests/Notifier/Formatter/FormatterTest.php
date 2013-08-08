<?php
/**
 * This file is part of the NotifierSwiftMailer package.
 *
 * (c) Dries De Peuter <dries@nousefreak.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Notifier\Handler;

use Notifier\Formatter\SwiftMailerFormatter;
use Notifier\Message\Message;

/**
 * @author Dries De Peuter <dries@nousefreak.be>
 */
class FormatterTest extends \PHPUnit_Framework_TestCase
{
	public function testHandler()
    {
		$formatter = new SwiftMailerFormatter();
		$formatter->setTemplate(new \Swift_Message());
		$message = $formatter->format(new Message('test'));

		$this->assertInstanceOf('\Swift_Message', $message->getFormatted('swiftmailer'));
	}
}
