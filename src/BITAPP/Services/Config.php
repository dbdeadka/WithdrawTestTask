<?php declare(strict_types=1);

namespace BITAPP\Services;

use \BITAPP\AbstractManager;

/**
 * @method static Config get()
 */
class Config extends AbstractManager
{
    protected static $instance;

    /** @var array $configData */
    private $configData;

    /**
     * @param string $fpath
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function load(string $fpath): self
    {
        if (!file_exists($fpath)) {
            throw new \InvalidArgumentException('File ' . $fpath . ' is not file or corrupted.');
        }
        /** @noinspection PhpIncludeInspection */
        $this->configData = include $fpath;
        return $this;
    }

    /**
     * @param string $section
     * @return array
     * @throws \InvalidArgumentException If the provided argument $section is empty
     * @throws \RuntimeException If section is not exists in the config
     */
    public function getConfig(string $section) :array
    {
        if (empty($section)) {
            throw new \InvalidArgumentException(
                'Bad $section argument ("' . $section . '")'
            );
        }
        if (!isset($this->configData[$section]) || empty($this->configData[$section])) {
            throw new \RuntimeException(
                'Bad $section argument ("' . $section . '"), may be incorrect config'
            );
        }
        return $this->configData[$section];
    }
}
