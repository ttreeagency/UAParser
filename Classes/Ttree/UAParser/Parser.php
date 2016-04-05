<?php
namespace Ttree\UAParser;

/*
 * This file is part of the Ttree.UAParser package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use TYPO3\Flow\Annotations as Flow;
use UAParser\Result\Client;

/**
 * @Flow\Scope("singleton")
 */
class Parser {

	/**
	 * @var boolean
	 */
	protected $initialized = false;

	/**
	 * @var \UAParser\Parser
	 */
	protected $userAgentParser;

	/**
	 * Initialize the object
	 */
	public function initialize() {
		if ($this->initialized === true) {
			return;
		}
		$this->userAgentParser = \UAParser\Parser::create();
		$this->initialized = true;
	}

	/**
	 * Sets up some standard variables as well as starts the user agent parsing process
	 *
	 * @param string $userAgent
	 * @param array $jsParseBits
	 * @return Client
	 */
	public function parse($userAgent, array $jsParseBits = array()) {
		if ($this->initialized === false) {
			$this->initialize();
		}
		return $this->userAgentParser->parse($userAgent, $jsParseBits);
	}
}
