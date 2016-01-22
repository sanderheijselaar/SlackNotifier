<?php
include_once (__DIR__ . '/vendor/autoload.php');

use SanderHeijselaar\SlackNotifier\SlackNotifier;
use SanderHeijselaar\SlackNotifier\Emoji;

$config = array(
	"base_url"   => "https://hooks.slack.com",
	"uri"        => "", // Add your unique integration url here, beginning with /services/
	"exceptions" => false
);

$i = new SlackNotifier($config);

$i->newAttachment()
->addAttachmentFallback('Put your fallback text here without mark up')
->addAttachmentColor(SlackNotifier::COLOR_GOOD)
->addAttachmentPreText("_Pre Text_", true)
->addAttachmentAuthor('Sander', 'http://www.google.com', 'https://jenkins-assembler.googlecode.com/files/griffon-icon-16x16.png')
->addAttachmentTitle('Title', 'http://www.title.com')
->addAttachmentText('Text *here*', true)
->addAttachmentImage('http://a4.mzstatic.com/eu/r30/Purple49/v4/1a/6c/7a/1a6c7a08-db87-52ba-1d5b-eb39897c0f94/icon256.png', 'http://a4.mzstatic.com/eu/r30/Purple49/v4/1a/6c/7a/1a6c7a08-db87-52ba-1d5b-eb39897c0f94/icon256.png')
->addAttachmentField('Priority', 'High', true)
->addAttachmentField('Status', 'Down', true)
;

$message = "My test message\n*This line is bold*\n`Some code on this line`\n_Last but not least, some italic text_";
$message = "My notification";
$name    = "Bot";
$channel = ''; // #random OR @username
$emoji = Emoji::WHITE_CHECK_MARK;

$result = $i->sendNotification($message, $name, $channel, $emoji, true);

echo '<pre>' . print_r(json_decode($result, true), true) . '</pre>';
