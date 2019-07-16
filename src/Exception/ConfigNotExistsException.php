<?php
/**
 * Created by PhpStorm.
 * User: januar
 * Date: 9/6/18
 * Time: 5:35 PM
 */

namespace LaravelOutside\Exception;

class ConfigNotExistsException extends AppException
{
    public function __construct($file)
    {
        parent::__construct(sprintf("Config file not found %s", $file), 0, null);
    }
}