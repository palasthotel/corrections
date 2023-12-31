<?php

/**
 *
 * Plugin Name: Corrections
 * Plugin URI: https://github.com/palasthotel/corrections/
 * Description: Post corrections workflow
 * Version: 1.0.0
 * Author: Palasthotel by Edward <edward.bock@palasthotel.de>
 * Author URI: https://palasthotel.de
 * Text Domain: corrections
 * Domain Path: /languages
 * Requires at least: 6.0
 * Tested up to: 6.2.2
 * License: http://www.gnu.org/licenses/gpl-3.0.html GPLv3
 *
 * @copyright Copyright (c) 2023, Palasthotel
 * @package Palasthotel\WordPress\Corrections
 *
 */

namespace Palasthotel\WordPress\Corrections;

use Palasthotel\WordPress\Corrections\Source\Messages;

require_once __DIR__ . "/vendor/autoload.php";

class Plugin extends Components\Plugin {

	const DOMAIN = "corrections";
	const FILTER_SHOULD_PROCESS_PENDING_MESSAGES = "corrections_should_process_messages";
	const FILTER_MESSAGE_SERVICE = "corrections_message_service";
	const FILTER_EMAIL_SUBJECT = "corrections_email_subject";
	const FILTER_EMAIL_BODY = "corrections_email_text";
	const FILTER_POST_TYPES = "corrections_post_types";
	const FILTER_MESSAGE_CONTENT_STRUCTURE = "corrections_content_structure";
	const FILTER_RECIPIENT_SUGGESTIONS = "corrections_recipient_suggestions";
	const HANDLE_GUTENBERG_SCRIPT = "corrections-script";
	const REST_FIELD_REVISIONS = "corrections_revisions";
	const REST_FIELD_MESSAGES = "corrections_messages";
	const REST_FIELD_MESSAGE_CONTENT = "corrections_message_content";
	public Process $process;
	public Ajax $ajax;
	public Repository $repository;
	public Messages $messagesSource;

	function onCreate() {
		$this->loadTextdomain(self::DOMAIN, "languages");

		$this->messagesSource   = new Messages();
		$this->repository = new Repository($this->messagesSource);
		new REST($this);
		$this->ajax = new Ajax($this);
		$this->process = new Process($this);
		new Gutenberg($this);


		if(WP_DEBUG){
			$this->messagesSource->createTables();
		}
	}

	public function onSiteActivation(): void {
		parent::onSiteActivation();
		$this->messagesSource->createTables();
	}
}

Plugin::instance();
