<?php
/**
 * Created by PhpStorm.
 * User: andreas
 * Date: 08/03/2017
 * Time: 14:41
 */


class ProjectMetric
{
    public $name;
    public $total;
    public $rank;

    function __construct( $name, $total, $rank )
    {
        $this -> name = $name;
        $this -> total = $total;
        $this -> rank = $rank;
    }
}