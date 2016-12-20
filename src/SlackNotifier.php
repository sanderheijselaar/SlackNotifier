<?php
namespace SanderHeijselaar\SlackNotifier;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Class for sending notifications to slack
 *
 * @version 0.2.0
 * @author Sander Heijselaar <info@xl-and.com>
 */
Class SlackNotifier
{
    const COLOR_GOOD    = 'good';
    const COLOR_WARNING = 'warning';
    const COLOR_DANGER  = 'danger';

    /** @var array */
	protected $config;
    /** @var GuzzleClient */
	protected $client;

    protected $attachments = array();
    protected $lastAttachmentKey;

	/**
	 * Constructor method
     * Reads the config and inits Guzzle
     *
     * @param array $config
	 */
	public function __construct($config)
	{
        $this->config = $config;

		// Init guzzle
		$this->client = new GuzzleClient([
			'base_url' => $this->getConfig('base_url'),
			'defaults' => [
				'exceptions' => $this->getConfig('exceptions')
			]
		]);
	}

	public function sendNotification($message, $username=null, $channel=null, $iconEmoji=null, $markDownText=false)
	{
		$payload = json_encode([
            'channel'     => $channel,
            'text'        => $message,
            'username'    => $username,
            'icon_emoji'  => $iconEmoji,
            'mrkdwn'      => $markDownText,
            'attachments' => $this->attachments,
		]);
        return $this->client->post($this->getConfig('base_url') . $this->getConfig('uri'), ['body' => $payload]);
	}

    public function newAttachment()
    {
        $this->attachments[] = array(
            'fallback'    => '',      // fall back text for deveices that cannot show the marked up text (only plain text) and for the notification
            'color'       => '',      // Color of the attachment
            'pretext'     => '',      // Text above the attachement
            'author_name' => '',      // Name of the author
            'author_link' => '',      // Link to authors website
            'author_icon' => '',      // Icon of author. Only 16x16 pixels1
            'title'       => '',      // Title of the attachment
            'title_link'  => '',      // Title link of the attachment
            'text'        => '',      // Text of the attachment
            'fields'      => array(), // Fields in the attachments
            'image_url'   => '',      // (Larger) Image linked on the thumb
            'thumb_url'   => '',      // (Smaller) Image to display as a thumb
            'mrkdwn_in'   => array(),
        );

        $lastAttachmentKey = array_keys($this->attachments);
        $this->lastAttachmentKey = end($lastAttachmentKey);

        return $this;
    }

    public function addAttachmentFallback($fallback)
    {
        $this->attachments[$this->lastAttachmentKey]['fallback'] = $fallback;

        return $this;
    }

    public function addAttachmentColor($color)
    {
        $this->attachments[$this->lastAttachmentKey]['color'] = $color;

        return $this;
    }

    public function addAttachmentPreText($pretext, $markDown=false)
    {
        $this->attachments[$this->lastAttachmentKey]['pretext'] = $pretext;
        
        if ((bool) $markDown === false && in_array('pretext', $this->attachments[$this->lastAttachmentKey]['mrkdwn_in']) === true)
        {
            $key = array_search('pretext', $this->attachments[$this->lastAttachmentKey]['mrkdwn_in']);
            unset($this->attachments[$this->lastAttachmentKey]['mrkdwn_in'][$key]);
        }
        else
        if ((bool) $markDown === true && in_array('pretext', $this->attachments[$this->lastAttachmentKey]['mrkdwn_in']) === false)
        {
            $this->attachments[$this->lastAttachmentKey]['mrkdwn_in'][] = 'pretext';
        }

        return $this;
    }

    public function addAttachmentAuthor($authorName, $authorLink, $authorIcon)
    {
        $this->attachments[$this->lastAttachmentKey]['author_name'] = $authorName;
        $this->attachments[$this->lastAttachmentKey]['author_link'] = $authorLink;
        $this->attachments[$this->lastAttachmentKey]['author_icon'] = $authorIcon;

        return $this;
    }

    public function addAttachmentTitle($title, $titleLink)
    {
        $this->attachments[$this->lastAttachmentKey]['title']     = $title;
        $this->attachments[$this->lastAttachmentKey]['title_ink'] = $titleLink;

        return $this;
    }

    public function addAttachmentText($text, $markDown=false)
    {
        $this->attachments[$this->lastAttachmentKey]['text'] = $text;

        if ((bool) $markDown === false && in_array('text', $this->attachments[$this->lastAttachmentKey]['mrkdwn_in']) === true)
        {
            $key = array_search('text', $this->attachments[$this->lastAttachmentKey]['mrkdwn_in']);
            unset($this->attachments[$this->lastAttachmentKey]['mrkdwn_in'][$key]);
        }
        else
        if ((bool) $markDown === true && in_array('text', $this->attachments[$this->lastAttachmentKey]['mrkdwn_in']) === false)
        {
            $this->attachments[$this->lastAttachmentKey]['mrkdwn_in'][] = 'text';
        }

        return $this;
    }

    public function addAttachmentImage($imageUrl, $imageThumb)
    {
        $this->attachments[$this->lastAttachmentKey]['image_url']   = $imageUrl;
        $this->attachments[$this->lastAttachmentKey]['image_thumb'] = $imageThumb;

        return $this;
    }

    public function addAttachmentField($title, $value, $short)
    {
        $this->attachments[$this->lastAttachmentKey]['fields'][] = array(
            'title' => $title,
            'value' => $value,
            'short' => (bool) $short,
        );

        return $this;
    }

    /**
	 * Get a settings from the slack config
	 *
	 * @param string $setting
	 *
	 * @return mixed|false Mixed when exists, false when the config setting isn't found
	 */
	protected function getConfig($setting)
	{
		return (empty($this->config[$setting])) ? false : $this->config[$setting];
	}

}
