<?php


trait TFooProxy {


    private IFoo $proxyFoo;


    public function bar() : baz {
        return $this->proxyFoo->bar();
    }


    protected function setProxyFoo( IFoo $i_proxyFoo ) : void {
        $this->proxyFoo = $i_proxyFoo;
    }


}
