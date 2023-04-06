<?php



/**
 * This tests generating a trait for an interface that uses references.
 */
interface IReferenceTest {

    public function foo( $bar ) : void;

    public function foo0( int & $bar ) : void;


    public function foo1( & $bar ) : void;


    public function & foo2() : Baz;


}
