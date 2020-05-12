<?php
namespace sqonk\phext\plotlib;

/**
*
* Plotting Library
* 
* @package		phext
* @subpackage	plotlib
* @version		1
* 
* @license		MIT see license.txt
* @copyright	2019 Sqonk Pty Ltd.
*
*
* This file is distributed
* on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
* express or implied. See the License for the specific language governing
* permissions and limitations under the License.
*/

/*
	The BulkPlot class can be used for quickly configuring and rendering common types
	of charts using JPGraph as an engine. While JPGraph supports a multitude
	of different chart types only a subset are currently supported by BulkPlot.

	Consider BulkPlot a convienience class that uses assumption to reduce the time
	required to otherwise build and render a graph.

	While it offers a variety of options to configure the data and display, if you
	otherwise need more fine-grained control then you should instead build a chart
	directly with the underlying engine.

	The DataFrame class in the datakit module hooks into this class to allow
	easy import, manipulation and rendering of statistical data.
*/

use Amenadiel\JpGraph\Graph as JPGraph;
use Amenadiel\JpGraph\Plot as JPPlot;
use sqonk\phext\core\{strings,arrays};

class BulkPlot
{
    protected $graphs = [];
    protected $scale = 'intlin';
    protected $title = '';
    protected $folderPath = 'plots';
	protected $builtCharts = null;
    
    static protected $cli_app;
    
	/* 
		Create a new BulkPlot object.
		
		$prefix		When set the prefix will be applied to the output file names of 
					the rendered charts.
	*/
    public function __construct(string $prefix = '')
    {
        $this->title = str_replace(' ', '-', strings::clean($prefix));
        if (! class_exists(JPGraph\Graph::class)) {
            throw new \Exception("PlotLib depends on the JPGraph library, you can include it in your project with: 'composer require amenadiel/jpgraph'.");
        }
        
        $rgb = new \Amenadiel\JpGraph\Util\RGB;
        $this->colours = array_keys($rgb->rgb_table);
    }
    
	/* 
		Set the scale used to render the chart. Must be a valid JPGraph type.
	
		Defaults to 'intlin'.
	*/
    public function scale(string $value = null)
    {
        if (! $value)
            return $this->scale;
        
        $this->scale = $value;
        return $this;
    }
    
    /*
        Set the location that the result plot images will be output to. If the location does
        not exist then it will attempt to create it at the time the plots are being made.
    
        By default it is set to a subfolder 'plots' in the current working directory.
    */
    public function output_path(string $path)
    {
        if (strings::ends_with($path, '/'))
            $path = substr($path, 0, -1);
        $this->folderPath = $path;
        return $this;
    }
    
	/*
		Add one or more series to the plot.
	
		$type 		Represents the type of chart (e.g line, box, bar etc). Possible values:
			- line: 		line chart.
			- linefill: 	line chart with filled area.
			- bar:			bar chart.
			- barstacked:	bar chart with each series stacked atop for each
							data point.
			- scatter:		scatter chart.
			- box:			Similar to a stock plot but with a fifth median value.
	
		$series		An array of multiple series (values) to be plotted.
	
		$options 	An associative arrat containing the chart configuration.
			- title:		Title of the rendered chart.
	
			- xseries: 		An array of values to use as the x-series. 
	
			- xformatter: 	A callback function used to format the labels of the x-series.
							function callback($value) -> $value
	
			- legend:		When set, will indicate the name of the series to display on the chart legend.
	
			- yformatter:	A callback function used to format the labels of the y-series.
							function callback($value) -> $value
			
			- regions:		An array of rectangular regions to be drawn onto the chart. Each
							item is an associative array containing the following options:
								- x: x-datapoint that the region starts from.
								- y: y-datapoint that the region starts from.
								- x2: x-datapoint that the region end at.
								- y2: y-datapoint that the region end at.
								- color: colour of the region, default is 'red'.
							Note that you can specify null for any of the co-ordinates to have them
							originate or extend to the infinate bounds of the chart.
	
			- lines:		Array of infinite lines to be drawn onto the chart. Each 
							item is an associative array containing the 
							the following options:
							- direction: Either VERTICAL or HORIZONTAL.
							- value: the numerical position on the respective axis that
								 the line will be rendered.
							- color: a colour name (e.g. red, blue etc) for the line 
								 colour. Default is red.
							- width: the stroke width of the line, default is 1.
			- labelangle:	Angular rotation of the x-axis labels, default is 0.	
			- bars:			A liniar array of values to represent an auxiliary/background
							bar chart dataset. This will plot on it's own Y axis.
			- barColor:		The colour of the bars dataset, default is 'lightgray'.
			- barWidth:		The width of each bar in the bars dataset, default is 7.
	
			- auxlines:		Array of auxiliary line plots, each item can contain the 
							following options:
								- values: numerical Y-values for the series.
								- color: optional colour name (e.g. red, blue etc) for the series, default is 'lightgrey'.
								- width: the stroke width of the line, default is 1.
								- legend: optional label to be displayed on the legend.	
	*/
    public function add(string $type, array $series, array $options = [])
    {
		foreach ($series as $item)
			if (! is_array($item))
				throw new \InvalidArgumentException('The value passed in for the series must be an array of arrays.');
		
        $data = array_merge(['type' => $type, 'series' => $series], $options);
        
        $this->graphs[] = $data;
    }
    
	// Internal method, builds a chart in the plot instance ready for output.
    protected function build(array $graphData, int $width, int $height)
    {
        $type = $graphData['type'];
        $fillers = ['linefill', 'bar', 'barstacked'];
        $blockPlots = ['bar', 'barstacked', 'box', 'stock'];
		$xseries = $graphData['xseries'] ?? null;
		$margin = $graphData['margin'] ?? null;
        
        $chart = new JPGraph\Graph($width, $height);
		if ($xseries && count($xseries) > 1)
			$chart->SetScale($this->scale, 1, 1, $xseries[0], arrays::last($xseries));
		else	
        	$chart->SetScale($this->scale);
		
		if ($margin)
			$chart->SetMargin(...$margin);

        $chart->SetClipping(arrays::get($graphData, 'clip', true));
		$min = arrays::get($graphData, 'min');
		if ($min)
        	$chart->yaxis->scale->SetAutoMin($min);
		$max = arrays::get($graphData, 'max');
		if ($max)
        	$chart->yaxis->scale->SetAutoMax($max);
		
		$angle = arrays::get($graphData, 'labelangle');
		if ($angle !== null)
	    	$chart->xaxis->SetLabelAngle($angle);
        $chart->xaxis->SetPos('min');
        
        if (arrays::get($graphData, 'hideAllTicks', false))
        {
            $chart->yaxis->HideLine();
            $chart->xaxis->HideLine();
            $chart->xaxis->HideTicks(); 
            $chart->xaxis->HideLabels();
        }        
        else 
        {
            $chart->legend->SetPos(0.15, 0.05, 'left', 'top');
            $chart->legend->SetColumns(4);
            $xtr = $graphData['xformatter'] ?? null;
            if (is_callable($xtr)) {
                $chart->xaxis->SetLabelFormatCallback(function($value) use ($xtr) {
                    return $xtr($value);
                }, true);
            }
            $ytr = $graphData['yformatter'] ?? null;
            if (is_callable($ytr)) {
                $chart->yaxis->SetLabelFormatCallback(function($value) use ($ytr) {
                    return $ytr($value);
                }, true);
            }
        }
        
        if (! empty($graphData['title']))
            $chart->title->Set($graphData['title']);
        
        $colours = &$this->colours;
        $ccount = count($colours);
        $classes = [
            'line' => 'LinePlot',
            'linefill' => 'LinePlot',
            'bar' => 'BarPlot',
            'barstacked' => 'BarPlot',
            'scatter' => 'ScatterPlot',
            'box' => 'BoxPlot',
			'stock' => 'StockPlot'
        ];
        
        $opacity = 6;
        $grouped = null;
        if (strpos($type, 'bar') === 0) 
            $grouped = []; 
		
		// -- auxiliary data sets first.

		$aux = $graphData['auxlines'] ?? null;
        if ($aux !== null)
		{
	        if (! is_array($aux))
	            throw new \Exception("'auxlines' key must be a liniar array of associative arrays containing the settings for each auxiliary series.");
			
			foreach ($aux as $auxseries) 
			{
				$auxPlot = new JPPlot\LinePlot($auxseries['values'], $xseries);
				$clr = arrays::get($auxseries, 'color', 'lightgray');
				$thickness = arrays::get($auxseries, 'width', 1);
				
				$auxPlot->SetColor($clr);
				$auxPlot->SetWeight($thickness);
				
				if ($auxlabel = arrays::get($auxseries, 'legend'))
					$auxPlot->SetLegend($auxlabel);
				
				$chart->Add($auxPlot);
			}
		}	
		
		$bars = $graphData['bars'] ?? null;
		if ($bars !== null)
		{
	        if (! is_array($bars))
	            throw new \Exception("'bars' key must be a liniar array of numerical data for display as an auxiliary series.");
			
			$barPlot = new JPPlot\BarPlot($bars, $xseries);
			$bclr = arrays::get($graphData, 'barColor', 'lightgray');
			$bwidth = arrays::get($graphData, 'barWidth', 1);
			$barPlot->SetColor("$bclr@0.2");
			$barPlot->SetFillColor("$bclr@0.6");
			$barPlot->SetWidth($bwidth);
			
			$barlabel = arrays::get($graphData, 'barLegend');
			if ($barlabel)
				$barPlot->SetLegend($barlabel);
			
			$chart->AddY(0, $barPlot);
			
			$chart->SetYScale(0, 'lin');
			$chart->ynaxis[0]->SetColor($bclr);
		}

	  	// -- then primary sets.
		foreach ($graphData['series'] as $i => $series)
        {
            $colour = ($i < $ccount) ? $colours[$i] : $colours[$i % $ccount];

            $class = new \ReflectionClass(sprintf("Amenadiel\\JpGraph\\Plot\\%s", $classes[$type]));
            $plot = $class->newInstance($series, $xseries);
            
            if (isset($graphData['legend'])) {
                $leg = is_array($graphData['legend']) ? $graphData['legend'][$i] : $graphData['legend'];
                $plot->SetLegend($leg);
            }
            
            $matchBorder = $graphData['matchBorder'] ?? false;
            if ($matchBorder)
                $plot->SetWeight(0);
			
			if ($type == 'box' or $type == 'stock')
				$plot->SetColor('black', 'green', 'red', 'red');
			else
            	$plot->SetColor("$colour@0.2");
            
            if (arrays::contains($fillers, $type))
                $plot->SetFillColor("$colour@0.6");
            else if ($type == 'scatter')
                $plot->mark->SetFillColor("$colour@0.$opacity");
            
            if (isset($graphData['width']) && arrays::contains($blockPlots, $type))
                $plot->SetWidth($graphData['width']);
            
            if (is_array($grouped))
                $grouped[] = $plot;
            else
                $chart->Add($plot);
        }
        
		// group plots
        if (strpos($type, 'bar') === 0) {
            if ($type == 'barstacked' && count($grouped) > 0)
                $g = new JPPlot\AccBarPlot($grouped);
            else 
                $g = new JPPlot\GroupBarPlot($grouped);
            
            if ($matchBorder)
                $g->SetWeight(0);
            if (isset($graphData['width']))
                $g->SetWidth($graphData['width']);
            $chart->Add($g);
        }
		
		// infininte lines along either X or Y axis.
        $lines = $graphData['lines'] ?? null;
        if ($lines !== null)
		{
	        if (! is_array($lines))
	            throw new \Exception("'lines' key must be a liniar array of associative arrays containing the settings for each line.");
			
			foreach ($lines as $line) {
				$dir = arrays::get($line, 'direction', 'h') == 'h' ? HORIZONTAL : VERTICAL;
				$v = arrays::get($line, 'value', 0);
				$clr = arrays::get($line, 'color', 'red');
				$thickness = arrays::get($line, 'width', 1);
				$chart->Add(new JPPlot\PlotLine($dir, $v, $clr, $thickness));
			}
		}
		
		// regions.
        $regions = $graphData['regions'] ?? null;
        if ($regions !== null)
		{
	        if (! is_array($regions))
	            throw new \Exception("'regions' key must be a liniar array of associative arrays containing the settings for each line.");
			
			foreach ($regions as $r) {
				[$x1, $y1, $x2, $y2] = [$r['x'], $r['y'], $r['x2'], $r['y2']];
				$clr = arrays::get($r, 'color', 'red');
				$chart->Add(new Region($x1, $y1, $x2, $y2, $clr));
			}
		}
        
		$img = jputils::render($chart);
		unset($chart);
		return $img;
    }
    
	/*
		Render the plot instance at the given dimensions. 
	
		If $writeToFile is TRUE and the script is running from the command line then 
		the resulting images are both returned and written to a file. The folder the 
		files are saved to can be changed using output_path() on the objects.
	*/
    public function render(int $width = 700, int $height = 500, bool $writeToFile = true)
    {
        if (count($this->graphs) == 0) 
            throw new \LengthException("No graphs registered in plot.");
        
        $out = [];
        $prefix = $this->title ?? '';
        if (php_sapi_name() == 'cli' && ! file_exists($this->folderPath))
            mkdir($this->folderPath, 0777, true);
        
        foreach ($this->graphs as $data)
        {
            $img = $this->build($data, $width, $height);
            if ($writeToFile && php_sapi_name() == 'cli')
            {   
                if ($prefix)
                {
                    // BulkPlot global name prefix has been set.
                    $legend = $data['legend'] ?? '';
                    if ($legend) {
                        if (is_array($legend))
                            $legend = implode(' ', $legend);
                        $legend = str_replace(' ', '_', strings::clean($legend));
                    }
                    else
                        $legend = uniqid();

                    $fileName = "{$prefix}_{$legend}";
                }   
                else
                {
                    /*
                        No global prefix set. File name will be in order of priority:
                            - title, if one is set.
                            - legend, if one is set.
                            - random string failing everything else.
                    */
                    if (isset($data['title']))
                        $chartName = $data['title'];
                    else if (isset($data['legend'])) {
                        $chartName = $data['legend'];
                        if (is_array($chartName))
                            $chartName = implode(' ', $chartName);
                    }
                    else
                        $chartName = uniqid();

                    $fileName = str_replace(' ', '_', $chartName);
                }  
                            
                if (strlen($fileName) > 253)
                    $fileName = strings::truncate($fileName, 253, 'c');
                
                file_put_contents("{$this->folderPath}/{$fileName}.png", $img);
            }
            
            $out[] = $img;
        }
		gc_collect_cycles();
        return $out;
    } 
}