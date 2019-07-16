<?php
/**
 * Created by PhpStorm.
 * User: januar
 * Date: 9/6/18
 * Time: 5:39 PM
 */

namespace LaravelOutside\Exception;


class AppException extends \RuntimeException
{
    public function handle(){
        echo(sprintf("%s : %s \n", get_class($this), $this->getMessage()));
        echo($this->getTraceAsString());
    }
}