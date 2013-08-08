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
use Notifier\Handler\AbstractHandler;
use Notifier\Notifier;
use Notifier\Recipient\RecipientInterface;
use Notifier\Formatter\FormatterInterface;
use Notifier\Message\MessageInterface;

/**
 * @author Dries De Peuter <dries@nousefreak.be>
 */
class SwiftMailerHandler extends AbstractHandler
{
	protected $deliveryType = 'swiftmailer';

	protected $mailer;

	public function __construct(\Swift_Mailer $mailer, $types = Notifier::TYPE_ALL, $bubble = true)
	{
		$this->mailer = $mailer;
		$this->types = $types;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function sendOne(MessageInterface $message, RecipientInterface $recipient)
	{
		$mail = $message->getFormatted('swiftmailer');
		$mail->setTo($recipient->getInfo('email'));
		$this->mailer->send($mail);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function sendBulk(MessageInterface $message, array $recipients)
	{
		$emails = array();
		foreach ($recipients as $recipient) {
			$emails[$recipient->getInfo('email')] = $recipient->getInfo('email');
		}

		$mail = $message->getFormatted('swiftmailer');
		$mail->setTo($emails);
		$this->mailer->send($mail);
	}

	/**
	 * Gets the formatter.
	 *
	 * @return FormatterInterface
	 */
	public function getDefaultFormatter()
	{
		return new SwiftMailerFormatter();
	}

	/**
	 * Get the formatter. This will use the default as a fallback.
	 *
	 * @return SwiftMailerFormatter
	 */
	public function getFormatter()
	{
		return parent::getFormatter();
	}
}
