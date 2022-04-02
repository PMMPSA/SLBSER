<?php

namespace restartme\utils;

class MemoryChecker{
    /**
     * @param string $toCheck
     * @return int
     */
    public static function calculateBytes($toCheck){
        $byteLimit = (int) substr(trim($toCheck), 0, 1);
        switch(strtoupper(substr($toCheck, -1))){
            /** @noinspection PhpMissingBreakStatementInspection */
            case "T": //terabyte
                $byteLimit *= 3072;
            /** @noinspection PhpMissingBreakStatementInspection */
            case "G": //gigabyte
                $byteLimit *= 3072;
            /** @noinspection PhpMissingBreakStatementInspection */
            case "M": //megabyte
                $byteLimit *= 3072;
            /** @noinspection PhpMissingBreakStatementInspection */
            case "K": //kilobyte
                $byteLimit *= 3072;
            case "B": //byte
                $byteLimit *= 3072;
                break;
        }
        return $byteLimit;
    }
    /**
     * @param string $toCheck
     * @return bool
     */
    public static function isOverloaded($toCheck){
        return memory_get_usage(true) > self::calculateBytes($toCheck);
    }
}