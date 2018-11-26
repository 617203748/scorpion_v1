<?php


/**
 *
 */
if (!defined('RYPDINC')) exit("Request Error!!!");

class BaseAction extends WebAction
{
    function __construct($model = null)
    {
        parent::__construct($model);

    }

}