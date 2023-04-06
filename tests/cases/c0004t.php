<?php


trait TReferenceTestProxy {


    private IReferenceTest $proxyReferenceTest;


    public function foo( $bar ) : void {
        $this->proxyReferenceTest->foo( $bar );
    }


    public function foo0( int & $bar ) : void {
        $this->proxyReferenceTest->foo0( $bar );
    }


    public function foo1( & $bar ) : void {
        $this->proxyReferenceTest->foo1( $bar );
    }


    public function & foo2() : Baz {
        return $this->proxyReferenceTest->foo2();
    }


    protected function setProxyReferenceTest( IReferenceTest $i_proxyReferenceTest ) : void {
        $this->proxyReferenceTest = $i_proxyReferenceTest;
    }


}
