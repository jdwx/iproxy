<?php


declare( strict_types = 1 );


namespace This\that\these;


use Other\that\those\Fishy;
use Foo\Bar\Baz as Qux;
use Foo\Bar\Wibble as BorkBork, Foo\Bar\Wobble;


trait TExample3Proxy {


    private IExample3 $proxyExample3;


    public function getExample() : \Example {
        return $this->proxyExample3->getExample();
    }


    public function getFishy() : Fishy {
        return $this->proxyExample3->getFishy();
    }


    public function setFishy( Fishy $i_fishy ) : void {
        $this->proxyExample3->setFishy( $i_fishy );
    }


    protected function setProxyExample3( IExample3 $i_proxyExample3 ) : void {
        $this->proxyExample3 = $i_proxyExample3;
    }


}
