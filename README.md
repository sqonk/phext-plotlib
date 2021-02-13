# PHEXT PlotLib

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.3-8892BF.svg)](https://php.net/)
[![License](https://sqonk.com/opensource/license.svg)](license.txt) [![Build Status](https://travis-ci.org/sqonk/phext-plotlib.svg?branch=master)](https://travis-ci.org/sqonk/phext-plotlib)

PlotLib is a wrapper for the PHP charting library JPGraph. It is designed for quickly outputting basic charts in bulk. Many options can be configured as needed but all have defaults. 

The main interface to the  library is through the BulkPlot class. While JPGraph supports a multitude of different chart types only a subset are currently supported by BulkPlot. Consider BulkPlot a convenience class that uses assumption to reduce the time required to otherwise build and render a graph.

You should ideally have a general understanding of how [JPGraph](https://jpgraph.net) works in order to get the most out of this library.

You can also combine it with [Visualise](https://github.com/sqonk/phext-visualise) to hook up real-time visual output when working from the command line. 


## About PHEXT

The PHEXT package is a set of libraries for PHP that aim to solve common problems with a syntax that helps to keep your code both concise and readable.

PHEXT aims to not only be useful on the web SAPI but to also provide a productivity boost to command line scripts, whether they be for automation, data analysis or general research.

## Install

Via Composer

``` bash
$ composer require sqonk/phext-plotlib
```



## PHP 8 Compatibility

*The PlotLib library is compatible with PHP 8*. However, due to a [minor problem in the underlying JPGraph library](https://github.com/HuasoFoundries/jpgraph/issues/99) it will raise an error on versions 4.3.4 and earlier. 

Due to this, as of version 0.5, PlotLib no longer depends on any composer release of JPGraph and instead supplies its own patched version.

However, also as of version 0.5, it is possible to run PlotLib with your own installed JPGraph version by [specifying a different namespace](docs/api/jputils.md).

## Documentation

[API Reference](docs/api/index.md) now available here.



## Example

``` php
use sqonk\phext\plotlib\BulkPlot;

$plot = new BulkPlot;

// add a chart with two line plots.
$plot->add('line', [
    	array_map(fn($v) => rand(1, 20), range(1, 10)),
    	array_map(fn($v) => rand(1, 20), range(1, 10))
    ], [
    'title' => 'lines',
    'xseries' => range(1, 10),
    'xformatter' => fn($v) => "Pt $v",
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

$l1 = [19,3,2,7,9,18,4,15,12,13];
$l2 = [17,20,19,17,19,5,13,8,15,8];

// A graph with horizontal and vertical rules.
$plot->add('line', [$l1, $l2], [
    'title' => 'Infinite Lines',
    'xseries' => range(1, 10),
    'font' => [FF_FONT1, FS_NORMAL, 8],
    'lines' => [
        ['direction' => 'h', 'value' => 7, 'color' => 'red'],
        ['direction' => 'v', 'value' => 4, 'color' => 'blue']
    ]
]);

// Finally, a plot with highlighted regions.
$plot->add('line', [$l1], [
    'title' => 'Regions',
    'xseries' => range(1, 10),
    'font' => [FF_FONT1, FS_NORMAL, 8],
    'regions' => [
        ['x' => 3, 'y' => 20, 'x2' => 7, 'y2' => 15, 'color' => 'red@0.3'],
        ['x' => 6, 'y' => 2, 'x2' => 10, 'y2' => 0, 'color' => 'red@0.3']
    ]
]);

// output all charts to a subfolder called 'plotlibtests'.
// this will output two files called 'lines.png' and 'bars.png'.
$plot->output_path('plotlibtests')->render();
```

![Lines](https://sqonk.com/opensource/phext/plotlib/docs/images/lines.png)

![Bars](https://sqonk.com/opensource/phext/plotlib/docs/images/bars.png)

![Bars](https://sqonk.com/opensource/phext/plotlib/docs/images/infinite_lines.png)

![Bars](https://sqonk.com/opensource/phext/plotlib/docs/images/regions.png)







## Credits

Theo Howell



## License

**PlotLib** is released under the MIT License (MIT). Please see the [License File](license.txt) for more information. This licence <u>does not</u> extend to the supplied copy of JPGraph, which has its own licensing agreement.

*From the JPGraph package and web site:*

**JpGraph** is released under a dual license. [QPL 1.0 (Qt Free License)](http://www.opensource.org/licenses/qtpl.php) For non-commercial, open-source or educational use and JpGraph Professional License for commercial use. [The professional version](https://jpgraph.net/pro/) also includes additional features and support.

Please see applicable project pages for all external dependancies and thier own licensing agreements.



## JPGraph

This library utilises the excellent JPGraph as its charting engine. 

Both free and pro versions are available. Please see the <a href="https://jpgraph.net">JPGraph</a> web site for more information. 

The author/maintainer of this projtect is not associated in any way with the development of JPGraph.



