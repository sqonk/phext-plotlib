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
Set or Return the namespace of the underlying JPGraph library that will be used by libary to render charts.

By default it returns the internal copy of JPGraph provided by the library.


------
##### class
```php
static public function class(string $className) : ReflectionClass
```
No documentation available.


------
##### render
```php
static public function render($chart) : ?string
```
Force the provided JPGraph object to render its contents, capturing the output and returning it to the caller.


------
