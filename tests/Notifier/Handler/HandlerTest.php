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

use Notifier\Logger\SendLogger;
use Notifier\Message\Message;
use Notifier\Notifier;
use Notifier\Recipient\Recipient;

/**
 * @author Dries De Peuter <dries@nousefreak.be>
 */
class HandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Notifier
     */
    protected $notifier;

    /**
     * @var \Swift_Plugins_Loggers_ArrayLogger
     */
    protected $logger;

    public function setUp()
    {
        $this->notifier = new Notifier();
    }

    public function tearDown()
    {
        unset($this->notifier);
    }

    protected function getMailer()
    {
        $mailer = \Swift_Mailer::newInstance(new \Swift_NullTransport());

        $this->logger = new \Swift_Plugins_Loggers_ArrayLogger();
        $mailer->registerPlugin(new SendLogger($this->logger));

        return $mailer;
    }

    public function testHandler()
    {
        $handler = new SwiftMailerHandler($this->getMailer());
        $this->assertInstanceOf('Notifier\Handler\SwiftMailerHandler', $handler);
    }

    public function testRecipientFilterSuccess()
    {
        $handler = new SwiftMailerHandler($this->getMailer());
        $handler->getFormatter()->setTemplate(new \Swift_Message());

        $this->notifier->pushHandler($handler);

        $recipient = new Recipient('Me');
        $recipient->setInfo('email', 'name@domail.tld');
        $recipient->addType('test', 'swiftmailer');

        $message = new Message('test');
        $message->setContent('content');
        $message->addRecipient($recipient);

        $this->notifier->sendMessage($message);

        $this->assertContains($recipient->getInfo('email'), $this->logger->dump());
    }

}
