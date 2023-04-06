# iproxy

This automatically generates the code for a PHP proxy trait for a given 
interface. The Proxy pattern allows incorporating functionality into a class 
without using inheritance.  This is useful when extending an interface
with multiple implementations that may need to be swapped out at runtime.

This is a very limited implementation of the "20% of the effort gives 80% of the 
results" variety.  It works well for my needs, but there are known limitations,
and probably some unknown ones.

* Does not (yet) support DNF types for arguments and return types.
* Does not (yet) support references.
* Does not (yet) support variadic functions.
* Does not (yet) support default values for arguments.
* Does not support interfaces that inherit from other interfaces. (This would be very challenging.)

## Installation

Via composer:

```composer require jdwx/iproxy```

This requires the [PHP AST extension](https://github.com/nikic/php-ast) to run,
but generates new PHP source files that have no external dependencies.

## Usage

Create a file (e.g. IFoo.php) with an interface to proxy:

```php
<?php


interface IFoo {


    public function bar() : baz;


}

```

Run the command:

```bash
YourPrompt$ vendor/bin/iproxy.php IFoo.php >FooProxy.php
```

This will create a file (e.g. FooProxy) with a trait that implements the
interface:

```php
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
```

Adding this trait to a class will should be sufficient to allow "implements 
FooInterface" to be added to the class definition.  The trait will implement 
the interface proxy, and adds a `setProxy{Name}()` method that can be used 
from the constructor to set the proxy target.

The proxy generator will attempt to preserve the `namespace`, `declare`, 
and `use` statements (but not `use function` or `use const`) from the original
file.

If the first character of the interface name is a capital I and the second 
letter is also a capital letter, the leading I will be removed.  If 
"Interface" appears in the interface name, it will be removed.  In all cases, 
a capital T will be added to the trait name.
