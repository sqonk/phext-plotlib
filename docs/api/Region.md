###### PHEXT > [Plotlib](../README.md) > [API Reference](index.md) > Region
------
### Region
A visual rectangular region on a graph that represents an area between data points.

This class is used by BulkPlot, which provides an interface to creating regions through the configuration options when adding a plot.

You do not need to work with this class directly when working with BulkPlot.
#### Methods
[__construct](#__construct)
[PrescaleSetup](#prescalesetup)
[PreStrokeAdjust](#prestrokeadjust)
[DoLegend](#dolegend)
[Min](#min)
[Max](#max)
[StrokeMargin](#strokemargin)
[Stroke](#stroke)

------
##### __construct
```php
public function __construct($xstart, $ystart, $xend, $yend, $colour) 
```
Construct a new Region at the given co-ordinates.

- **$xstart** left-most point x-axis.
- **$ystart** top-most point on the y-axis.
- **$xend** right-most point on the x-axis.
- **$yend** bottom-most point on the y-axis.


------
##### PrescaleSetup
```php
public function PrescaleSetup($aGraph) 
```
No documentation available.


------
##### PreStrokeAdjust
```php
public function PreStrokeAdjust($aGraph) 
```
No documentation available.


------
##### DoLegend
```php
public function DoLegend($graph) 
```
No documentation available.


------
##### Min
```php
public function Min() 
```
No documentation available.


------
##### Max
```php
public function Max() 
```
No documentation available.


------
##### StrokeMargin
```php
public function StrokeMargin($aImg) 
```
No documentation available.


------
##### Stroke
```php
public function Stroke($img, $xscale, $yscale) 
```
No documentation available.


------
