<?php

namespace Riclep\Storyblok\Events;

use Illuminate\Foundation\Events\Dispatchable;

class StoryblokUnpublished implements PublishingEvent
{
	use Dispatchable;

	public $webhookPayload;

	/**
	 * Receives the webhook JSON, see:
	 * https://www.storyblok.com/docs/guide/in-depth/webhooks#available-webhooks
	 *
	 * @param $webhookPayload
	 */
	public function __construct($webhookPayload)
	{
		$this->webhookPayload = $webhookPayload;
	}
}