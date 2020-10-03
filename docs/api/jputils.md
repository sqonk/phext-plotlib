###### PHEXT > [Plotlib](../README.md) > [API Reference](index.md) > jputils
------
### jputils
A utilities class for working on a JPGraph object.

Current use is for reliably outputting the chart render while catching internal errors.
#### Methods
[render](#render)

------
##### render
```php
static public function render(Amenadiel\JpGraph\Graph\Graph $chart) 
```
Force the provided JPGraph object to render its contents, capturing the output and returning it to the caller.


------
