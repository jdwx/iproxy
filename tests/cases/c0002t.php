<?php


trait TInwardProxy {


    private InwardInterface $proxyInward;


    public function getInward() : string {
        return $this->proxyInward->getInward();
    }


    public function setInward( string $i_nstInward ) : void {
        $this->proxyInward->setInward( $i_nstInward );
    }


    public function exampleMethod( ExampleClass $i_nstExampleClass ) : void {
        $this->proxyInward->exampleMethod( $i_nstExampleClass );
    }


    public function getExample() : ExampleClass {
        return $this->proxyInward->getExample();
    }


    protected function setProxyInward( InwardInterface $i_proxyInward ) : void {
        $this->proxyInward = $i_proxyInward;
    }


}
