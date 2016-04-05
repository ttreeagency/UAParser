<?php
namespace Ttree\UAParser\Service;

/*
 * This file is part of the Ttree.UAParser package.
 *
 * (c) Contributors of the Neos Project - www.neos.io
 *
 * This package is Open Source Software. For the full copyright and license
 * information, please view the LICENSE file which was distributed with this
 * source code.
 */

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use TYPO3\Flow\Annotations as Flow;
use Ttree\UAParser\Exception;

/**
 * @author Dominique Feyer <dfeyer@ttree.ch>
 * @Flow\Scope("singleton")
 */
class RegularExpressionService
{

    const CACHE_KEY = 'RegexesCollection';

    /**
     * @var string
     */
    protected $resourceUri = 'https://raw.github.com/tobie/ua-parser/master/regexes.yaml';

    /**
     * @var \TYPO3\Flow\Cache\Frontend\PhpFrontend
     */
    protected $cache;

    /**
     * @param null $resourceUri
     */
    public function __construct($resourceUri = NULL)
    {
        if ($resourceUri !== NULL) {
            $this->resourceUri = $resourceUri;
        }
    }

    /**
     * @return array Potential return value from the regular expressions cache
     */
    public function load()
    {
        if (!$this->cache->has(self::CACHE_KEY)) {
            $this->update();
        }

        return $this->cache->requireOnce(self::CACHE_KEY);
    }

    /**
     * @return boolean
     * @throws Exception
     */
    public function update()
    {
        $level = error_reporting(0);
        $yamlContent = file_get_contents($this->resourceUri);
        error_reporting($level);

        if ($yamlContent === false) {
            throw new Exception('Unable to download remote file: ' . $this->resourceUri, 1389359166);
        }

        try {
            $this->cache->set(self::CACHE_KEY, $this->convertYamlToRegularExpressions($yamlContent));
        } catch (ParseException $exception) {
            throw new Exception('Unable to parse YAML content', 1389688349);
        }

        return TRUE;
    }

    /**
     * @param string $yamlContent
     * @return string
     */
    protected function convertYamlToRegularExpressions($yamlContent)
    {
        return 'return ' . var_export(Yaml::parse($yamlContent), true) . ';';
    }
}
