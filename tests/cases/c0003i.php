<?php


declare( strict_types = 1 );


namespace This\that\these;


use Other\that\those\Fishy;

use Foo\Bar\Baz as Qux;

use function Foo\Bar\Bonk;

use const Foo\Bar\Bork;

use Foo\Bar\Wibble as BorkBork, Foo\Bar\Wobble;


interface IExample3 {


    public function getExample() : \Example;


    public function getFishy() : Fishy;


    public function setFishy( Fishy $i_fishy ) : void;


}
