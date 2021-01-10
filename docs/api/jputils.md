###### PHEXT > [Plotlib](../README.md) > [API Reference](index.md) > jputils
------
### jputils
A utilities class for working on a JPGraph object.

Current use is for reliably outputting the chart render while catching internal errors.
#### Methods
[namespace](#namespace)
[class](#class)
[render](#render)

------
##### namespace
```php
static public function namespace(string $newNamespace = null) 
```
Set or return the namespace of the underlying JPGraph library that will be used by libary to render charts.

You can use this method to effectively change the version of JPGraph being used to render the output.

NOTE: When setting your own namespace, this will currently only work with copies of the library where all classes and methods exist within the <u>one</u> namespace. The various unoffical composer packages that split the classes into sub-namespaces are <u>not</u> compatible.

Also note that as this method is responsible for loading the internal library on demand the namespace can only be changed if the internal copy has not already been used.

By default it returns the internal copy of JPGraph provided by the library.


------
##### class
```php
static public function class(string $className) : ReflectionClass
```
Used by BulkPlot to instanciate both the graph and the various plot classes from within whatever namespace has been set for the JPGraph library.


------
##### render
```php
static public function render($chart) : ?string
```
Force the provided JPGraph object to render its contents, capturing the output and returning it to the caller.


------
