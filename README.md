# PHEXT PlotLib

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)
[![License](https://sqonk.com/opensource/license.svg)](license.txt)

PlotLib is a wrapper for the PHP charting library JPGraph. It is designed for quickly outputting basic charts in bulk. Many options can be configured as needed but all have defaults.

You should ideally have a general understanding of how JPGraph works in order to get the most out of this library.


## About PHEXT

The PHEXT package is a set of libraries for PHP that aim to solve common problems with a syntax that helps to keep your code both concise and readable.

PHEXT aims to not only be useful on the web SAPI but to also provide a productivity boost to command line scripts, whether they be for automation, data analysis or general research.

## Install

Via Composer

``` bash
$ composer require sqonk\phext-plotlib
```

## Graph types supported

* line
* bar
* linefill - line graph with filled area
* barstacked - stacked bars.
* scatter
* box
* stock - candle stick graph of open,high,low,close sets.

## Documentation

Forthcoming, refer to class comments and functions for the interim.

## Example

``` php
use sqonk\phext\plotlib\Plot;

$plot = new BulkPlot;

// add a chart with two line plots.
$plot->add('line', [
		array_map(fn($v) => rand(1, 20), range(1, 10)),
		array_map(fn($v) => rand(1, 20), range(1, 10))
	], [
	'title' => 'lines',
	'xseries' => range(1, 10),
	'xformatter' => fn($v) => "Point $v",
	'labelangle' => 45
]);

// add a stacked bar chart.
$plot->add('barstacked', [
		array_map(fn($v) => rand(1, 20), range(1, 10)),
		array_map(fn($v) => rand(1, 20), range(1, 10))
	], [
	'title' => 'bars',
	'auxlines' => [
		['values' => array_map(fn($v) => rand(1, 20), range(1, 10)), 'legend' => 'auxlines']
	]
]);

// output all charts to a subfolder called 'plotlibtests'.
// this will output two files called 'lines.png' and 'bars.png'.
$plot->output_path('plotlibtests')->render();
```
 
## Credits

Theo Howell
 
## License

The MIT License (MIT). Please see [License File](license.txt) for more information. 
 
Please see applicable project pages for all external dependancies and thier own licensing agreements.
 
## JPGrapgh

This library utilises the excellent JPGraph library as its charting engine.

Both free and pro versions are available. Please see the <a href="https://jpgraph.net">JPGraph</a> web site for more information. 



