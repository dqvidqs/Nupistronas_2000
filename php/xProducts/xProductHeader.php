<?php

class xProductHeader{

    public $order;
    public $value;

    public function __toString(): string{
        return $this->value;
    }
}
