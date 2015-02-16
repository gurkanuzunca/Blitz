<?php

namespace Library\Pagination;


use Library\Support\LibraryInterface;

class Paging implements LibraryInterface
{

    private $current;
    private $total;
    private $limit;
    private $offset;
    private $prev;
    private $next;
    private $route;
    private $pages;


    public static function version()
    {
        return '1.0';
    }


    public function __construct($current, $total, $limit)
    {
        $this->current = (int) $current;
        $this->total = (int) ceil($total / $limit);
        $this->limit = $limit;

        if ($this->current === 0) {
            $this->current = 1;
        }

        if ($this->current > $total) {
            $this->current = $total;
        }

        $this->offset = $this->total > 1 ? ($this->current - 1) * $this->limit : 0;

        $this->prev = $this->current > 1 ? $this->current - 1 : false;
        $this->next = $this->current < $this->total ? $this->current + 1 : false;
    }

    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }



    public function generate()
    {
        $pages = array();

        $number = 1;
        $index = $this->current - 2;
        $diff = $this->total - $this->current;

        if ($diff < 2) {
            $index = $index - (2 - $diff);
        }

        while (($number <= 5) && ($index <= $this->total)) {
            if ($index >= 1){
                $pages[] = $index;
                $number++;
            }
            $index++;
        }

        $this->pages = $pages;
    }




    public function getPrev()
    {
        return $this->prev;
    }


    public function getNext()
    {
        return $this->next;
    }


    public function getFirst()
    {
        if ($this->prev > 2) {
            return 1;
        }

        return false;
    }


    public function getLast()
    {
        if ($this->next > 0 && $this->next < ($this->total - 1)) {
            return $this->total;
        }

        return false;
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function getCurrent()
    {
        return $this->current;
    }


    public function getLimit()
    {
        return $this->limit;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function moreThanOnePage()
    {
        if ($this->total > 1) {
            return true;
        }
        return false;
    }


}