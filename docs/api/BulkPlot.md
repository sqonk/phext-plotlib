###### PHEXT > [Plotlib](../README.md) > [API Reference](index.md) > BulkPlot
------
### BulkPlot
The BulkPlot class can be used for quickly configuring and rendering common types of charts using JPGraph as an engine. While JPGraph supports a multitude of different chart types only a subset are currently supported by BulkPlot.

Consider BulkPlot a convienience class that uses assumption to reduce the time required to otherwise build and render a graph.

While it offers a variety of options to configure the data and display, if you otherwise need more fine-grained control then you should instead build a chart directly with the underlying engine.

The DataFrame class in the datakit module hooks into this class to allow easy import, manipulation and rendering of statistical data.
#### Methods
[__construct](#__construct)
[scale](#scale)
[output_path](#output_path)
[add](#add)
[render](#render)

------
##### __construct
```php
public function __construct(string $prefix = '') 
```
Create a new BulkPlot object.

- **$prefix** When set the prefix will be applied to the output file names of the rendered charts.


------
##### scale
```php
public function scale(string $value = null) 
```
Set the scale used to render the chart. Must be a valid JPGraph type.

Defaults to 'intlin'.


------
##### output_path
```php
public function output_path(string $path) 
```
Set the location that the result plot images will be output to. If the location does not exist then it will attempt to create it at the time the plots are being made.

By default it is set to a subfolder 'plots' in the current working directory.


------
##### add
```php
public function add(string $type, array $series, array $options = []) 
```
Add one or more series to the plot.

- **$type** Represents the type of chart (e.g line, box, bar etc). Possible values:
	- line: line chart.
	- linefill: line chart with filled area.
	- bar: bar chart.
	- barstacked: bar chart with each series stacked atop for each data point.
	- scatter: scatter chart.
	- box: Similar to a stock plot but with a fifth median value.
- **$series** An array of multiple series (values) to be plotted.
- **$options**  An associative arrat containing the chart configuration.
	- title: Title of the rendered chart.
	- xseries: An array of values to use as the x-series.
	- xformatter: A callback function used to format the labels of the x-series. `function callback($value) -> string`
	- legend: When set, will indicate the name of the series to display on the chart legend.
	- yformatter: A callback function used to format the labels of the y-series. `function callback($value) -> string`
	- regions: An array of rectangular regions to be drawn onto the chart. Each item is an associative array containing the following options:
		- x: x-datapoint that the region starts from.
		- y: y-datapoint that the region starts from.
		- x2: x-datapoint that the region end at.
		- y2: y-datapoint that the region end at.
		- color: colour of the region, default is 'red'.
		- Note that you can specify null for any of the co-ordinates to have them originate or extend to the infinate bounds of the chart.
	- lines: Array of infinite lines to be drawn onto the chart. Each item is an associative array containing the the following options:
		- direction: Either 'h' or 'v'.
		- value: the numerical position on the respective axis that the line will be rendered.
		- color: a colour name (e.g. red, blue etc) for the line colour. Default is red.
		- width: the stroke width of the line, default is 1.
	- labelangle: Angular rotation of the x-axis labels, default is 0.
	- bars: A liniar array of values to represent an auxiliary/background bar chart dataset. This will plot on it's own Y axis.
	- barColor: The colour of the bars dataset, default is 'lightgray'.
	- barWidth: The width of each bar in the bars dataset, default is 7.
	- auxlines: Array of auxiliary line plots, each item can contain the following options:
		- values: numerical Y-values for the series.
		- color: optional colour name (e.g. red, blue etc) for the series, default is 'lightgrey'.
		- width: the stroke width of the line, default is 1.
		- legend: optional label to be displayed on the legend.


------
##### render
```php
public function render(int $width = 700, int $height = 500, bool $writeToFile = true) 
```
Render the plot instance at the given pixel dimensions.

If $writeToFile is `TRUE` and the script is running from the command line then the resulting images are both returned and written to a file. The folder the files are saved to can be changed using output_path() on the objects.


------
