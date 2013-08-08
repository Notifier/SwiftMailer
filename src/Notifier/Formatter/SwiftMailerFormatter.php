<?php
/**
 * This file is part of the NotifierSwiftMailer package.
 *
 * (c) Dries De Peuter <dries@nousefreak.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Notifier\Formatter;

use Notifier\Exception\FormatterException;
use Notifier\Message\MessageInterface;

/**
 * @author Dries De Peuter <dries@nousefreak.be>
 */
class SwiftMailerFormatter implements FormatterInterface
{
	/**
	 * @var \Swift_Message
	 */
	protected $template;

	/**
	 * @var callable
	 */
	protected $callback;

	/**
	 * @param callable $callback
	 */
	public function setCallback(callable $callback)
	{
		$this->callback = $callback;
	}

	/**
	 * @return callable
	 */
	public function getCallback()
	{
		return $this->callback;
	}

	/**
	 * @param \Swift_Message $template
	 */
	public function setTemplate(\Swift_Message $template)
	{
		$this->template = $template;
	}

	/**
	 * @return \Swift_Message
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	public function format(MessageInterface $message)
	{
		if (isset($this->template)) {
			$template = clone $this->template;
		}
		elseif (isset($this->callback)) {
			$template = call_user_func($this->callback, $message);
		}

		if (!isset($template) || !($template instanceof \Swift_Message)) {
			throw new FormatterException(__CLASS__ . ' could not create a \Swift_Message.');
		}

		$template->setSubject($message->getSubject());
		$content = str_replace('{{subject}}', $message->getSubject(), $template->getBody());
		$content = str_replace('{{content}}', $message->getContent(), $content);
		$template->setBody($content);

		$message->setFormatted('swiftmailer', $template);

		return $message;
	}

	public function formatBatch(array $messages)
	{
		foreach ($messages as &$message) {
			$message = $this->format($message);
		}

		return $messages;
	}
}
