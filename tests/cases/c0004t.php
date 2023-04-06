<?php


trait TReferenceTestProxy {


    private IReferenceTest $proxyReferenceTest;


    public function foo( $bar ) : void {
        $this->proxyReferenceTest->foo( $bar );
    }


    protected function setProxyReferenceTest( IReferenceTest $i_proxyReferenceTest ) : void {
        $this->proxyReferenceTest = $i_proxyReferenceTest;
    }


}
