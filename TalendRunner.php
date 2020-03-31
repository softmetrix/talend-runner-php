<?php

namespace Softmetrix\TalendRunner;

use Exception;

class TalendRunner
{
    const OS_WINDOWS = 1;
    const OS_NIX = 2;
    const OS_OTHER = 3;
    const SUCCESS_YES = 'YES';
    const SUCCESS_NO = 'NO';
    private $jobPath;
    private $contextParams = [];
    private $executeBefore;
    private $executeAfter;

    public function __construct($jobPath, $executeBefore = false, $executeAfter = false)
    {
        $this->jobPath = $jobPath;
        $this->executeBefore = $executeBefore;
        $this->executeAfter = $executeAfter;
    }

    public function addContextParam($name, $value)
    {
        $this->contextParams[$name] = $value;
    }

    public function run()
    {
        if ($this->executeBefore) {
            exec($this->executeBefore);
        }
        $command = $this->buildCommand();
        exec($command.' 2>&1', $output, $returnVar);
        if ($this->executeAfter) {
            exec($this->executeAfter);
        }

        return [
            'success' => ($returnVar === 0) ? self::SUCCESS_YES : self::SUCCESS_NO,
            'output' => implode("\,", $output),
        ];
    }

    private function buildCommand()
    {
        $command = $this->jobPath;
        switch ($this->getOS()) {
            case self::OS_WINDOWS:
                $command .= '.bat';
            break;
            case self::OS_NIX:
                $command .= '.sh';
            break;
            default:
                throw new Exception('Unsupported OS');
            break;
        }
        foreach ($this->contextParams as $key => $param) {
            $command .= " --context_param {$key}=\"{$param}\"";
        }

        return $command;
    }

    private function getOS()
    {
        $os = strtoupper(PHP_OS);
        $nixOsList = ['LINUX', 'FREEBSD', 'DARWIN'];
        if (substr($os, 0, 3) === 'WIN') {
            return self::OS_WINDOWS;
        } elseif (in_array($os, $nixOsList)) {
            return self::OS_NIX;
        }

        return self::OS_OTHER;
    }
}
