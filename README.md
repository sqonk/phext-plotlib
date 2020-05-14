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
$ composer require sqonk/phext-plotlib
```

## Usage

The <code>BulkPlot</code> class is the primary mechanism of the package and has the following public methods:

```php
/* 
    Create a new BulkPlot object.

    When set the $prefix will be applied to the output file names of the rendered charts.
*/
public function __construct(string $prefix = '');

/* 
    Set the scale used to render the chart. Must be a valid JPGraph type.

    Defaults to 'intlin'.
*/
public function scale(string $value = null);

/*
    Set the location that the result plot images will be output to. If the location does
    not exist then it will attempt to create it at the time the plots are being made.

    By default it is set to a subfolder 'plots' in the current working directory.
*/
public function output_path(string $path);

/*
    Add one or more series to the plot.

    NOTE: refer to legend of configuration options further down.
*/
public function add(string $type, array $series, array $options = []);

/*
    Render the plot instance at the given dimensions. 

    If $writeToFile is TRUE and the script is running from the command line then 
    the resulting images are both returned and written to a file. The folder the 
    files are saved to can be changed using output_path() on the objects.
*/
public function render(int $width = 700, int $height = 500, bool $writeToFile = true);
```

## Plot Configuration Options

These are the various options available when adding a plot to the object:

* <code>$type</code> Represents the type of chart (e.g line, box, bar etc). Possible values:
    - line: line chart.
    - linefill: line chart with filled area.
    - bar: bar chart.
    - barstacked: bar chart with each series stacked atop for each data point.
    - scatter: scatter chart.
    - box:	Similar to a stock plot but with a fifth median value.
    - stock: Candle stick plot with each data point consisting of a open, high, low and close value.

* <code>$series</code> An array of multiple series (values) to be plotted.

* <code>$options</code> An associative array containing the chart configuration.
    - title: Title of the rendered chart.
    - xseries: An array of values to use as the x-series. 
    - xformatter: A callback function used to format the labels of the x-series. Should take one paremeter (the value) and return the transformed value.
    - legend: When set, will indicate the name of the series to display on the chart legend.
    - yformatter: A callback function used to format the labels of the y-series. Should take one paremeter (the value) and return the transformed value.	
    - regions: An array of rectangular regions to be drawn onto the chart. Each item is an associative array containing the following options:
        * x: x-datapoint that the region starts from.
        * y: y-datapoint that the region starts from.
        * x2: x-datapoint that the region end at.
        * y2: y-datapoint that the region end at.
        * color: colour of the region, default is 'red'.
				Note that you can specify null for any of the co-ordinates to have them
				originate or extend to the infinate bounds of the chart.    
    - lines: Array of infinite lines to be drawn onto the chart. Each item is an associative array containing the the following options:
	    * direction: Either <span style="color:#FF2F92">VERTICAL</span> or <span style="color:#FF2F92">HORIZONTAL</span>.
	    * value: the numerical position on the respective axis that the line will be rendered.
	    * color: a colour name (e.g. red, blue etc) for the line colour. Default is red.
	    * width: the stroke width of the line, default is 1.
    - labelangle: Angular rotation of the x-axis labels, default is 0.	
    - bars: A liniar array of values to represent an auxiliary/background bar chart dataset. This will plot on it's own Y axis.
    - barColor: The colour of the bars dataset, default is 'lightgray'.
    - barWidth: The width of each bar in the bars dataset, default is 7.
    - auxlines:	Array of auxiliary line plots, each item can contain the following options:
		* values: numerical Y-values for the series.
		* color: optional colour name (e.g. red, blue etc) for the series, default is 'lightgrey'.
		* width: the stroke width of the line, default is 1.
		* legend: optional label to be displayed on the legend.	

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



