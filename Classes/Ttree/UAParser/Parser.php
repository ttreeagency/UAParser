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

use Ttree\UAParser\Service\RegularExpressionService;
use TYPO3\Flow\Annotations as Flow;
use UAParser\Result\Client;

/**
 * @Flow\Scope("singleton")
 */
class Parser {

	/**
	 * @Flow\Inject
	 * @var RegularExpressionService
	 */
	protected $regularExpressionService;

	/**
	 * @var boolean
	 */
	protected $initialized = FALSE;

	/**
	 * @var \UAParser\Parser
	 */
	protected $userAgentParser;

	/**
	 * Initialize the object
	 */
	public function initialize() {
		if ($this->initialized === TRUE) {
			return;
		}
		$this->userAgentParser = new \UAParser\Parser($this->regularExpressionService->load());
		$this->initialized = TRUE;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @param string $userAgent
	 * @param array $jsParseBits
	 * @return Client
	 */
	public function parse($userAgent, array $jsParseBits = array()) {
		if ($this->initialized === FALSE) {
			$this->initialize();
		}
		return $this->userAgentParser->parse($userAgent, $jsParseBits);
	}
}

?>
