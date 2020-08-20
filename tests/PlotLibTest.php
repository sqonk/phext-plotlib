<?php
declare(strict_types=1);
/**
*
* Plotting Library
* 
* @package		phext
* @subpackage	detach
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

use PHPUnit\Framework\TestCase;
use sqonk\phext\plotlib\BulkPlot;
use sqonk\phext\core\arrays;

class PlotLibTest extends TestCase
{    
    protected function pixels($img)
    {
        $width = imagesx($img);
        $height = imagesy($img);
        $pixels = [];
    
        foreach (sequence($height-1) as $y) {
            foreach (sequence($width-1) as $x)
            {
                $rgb = imagecolorat($img, $x, $y);
                $colours = imagecolorsforindex($img, $rgb);
                $pixels[$y][$x] = $colours;
            }
        }
        return $pixels;    
    }
    
    public function testEnv()
    {
        $this->assertSame(true, function_exists('imagecreatefrompng'));
        $this->assertSame(true, function_exists('imagecreatefrompng'));
    }
    
    public function testMultiLine()
    {
        $plot = new BulkPlot;
        
        $l1 = [19,3,2,7,9,18,4,15,12,13];
        $l2 = [17,20,19,17,19,5,13,8,15,8];
         
        $plot->add('line', [$l1, $l2], [
            'title' => 'lines',
            'xseries' => range(1, 10),
            'font' => [FF_FONT1, FS_NORMAL, 8],
            'xformatter' => function ($v) {
                return "Pt $v";
            }
        ]);
        
        [$img] = $plot->render(700, 500, false); 

        $rendered = imagecreatefromstring($img);
        $example = imagecreatefrompng(__DIR__."/lines.png");
        
        $rpixels = $this->pixels($rendered);
        $epixels = $this->pixels($example);
        
        foreach (sequence(0, 499) as $y) {
            foreach (sequence(0, 699) as $x) {
                $this->assertEquals($epixels[$y][$x], $rpixels[$y][$x]);
            }
        }       
    }    

    public function testBarsAndAuxlines()
    {
        $plot = new BulkPlot;
        
        $l1 = [19,3,2,7,9,18,4,15,12,13];
        $l2 = [17,20,19,17,19,5,13,8,15,8];
        $l3 = [2,5,13,12,17,16,7,4,17,17];
        
        $plot->add('barstacked', [$l1, $l2], [
            'title' => 'bars',
            'font' => [FF_FONT1, FS_NORMAL, 8],
            'auxlines' => [
                ['values' => $l3, 'legend' => 'auxlines']
            ]
        ]);
        
        [$img] = $plot->render(700, 500, false); 
        
        $rendered = imagecreatefromstring($img);
        $example = imagecreatefrompng(__DIR__."/bars.png");
        
        $rpixels = $this->pixels($rendered);
        $epixels = $this->pixels($example);
        
        foreach (sequence(0, 499) as $y) {
            foreach (sequence(0, 699) as $x) { 
                $this->assertEquals($epixels[$y][$x], $rpixels[$y][$x]);
            }
        }  
    }
    
    public function testBackgroundBars()
    {
        $plot = new BulkPlot;
        $l1 = [19,3,2,7,9,18,4,15,12,13];
        $l2 = [17,20,19,17,19,5,13,8,15,8];
        
        $plot->add('line', [$l1], [
            'title' => 'auxbars',
            'xseries' => range(1, 10),
            'xformatter' => function($v) {
                return "Pt $v";
            },
            'yformatter' => function($v) {
                return "Vl $v";
            },
            'font' => [FF_FONT1, FS_NORMAL, 8],
            'bars' => $l2,
            'barWidth' => 10,
            'barColor' => 'gray',
        ]);
        
        [$img] = $plot->render(700, 500, false); //file_put_contents('mbars.png', $rendered);
        
        $rendered = imagecreatefromstring($img); 
        $example = imagecreatefrompng(__DIR__."/auxbars.png");
        
        $rpixels = $this->pixels($rendered);
        $epixels = $this->pixels($example);
        
        foreach (sequence(0, 499) as $y) {
            foreach (sequence(0, 699) as $x) { 
                if ($rpixels[$y][$x] != $epixels[$y][$x]) {
                    error_log("$y:$x");
                    var_dump($epixels[$y][$x]);
                    var_dump($rpixels[$y][$x]);
                }
                //$this->assertSame($epixels[$y][$x], $rpixels[$y][$x]);
            }
        }  
    }
    
    public function testInfiniteLines()
    {
        $plot = new BulkPlot;
        $l1 = [19,3,2,7,9,18,4,15,12,13];
        $l2 = [17,20,19,17,19,5,13,8,15,8];
        
        $plot->add('line', [$l1, $l2], [
            'title' => 'Infinite Lines',
            'xseries' => range(1, 10),
            'font' => [FF_FONT1, FS_NORMAL, 8],
            'lines' => [
                ['direction' => HORIZONTAL, 'value' => 7, 'color' => 'red'],
                ['direction' => VERTICAL, 'value' => 4, 'color' => 'blue']
            ]
        ]);
        
        [$img] = $plot->render(700, 500, false);
        $rendered = imagecreatefromstring($img); 
        $example = imagecreatefrompng(__DIR__."/inflines.png");
        
        $rpixels = $this->pixels($rendered);
        $epixels = $this->pixels($example);
        
        foreach (sequence(0, 499) as $y) {
            foreach (sequence(0, 699) as $x) { 
                $this->assertEquals($epixels[$y][$x], $rpixels[$y][$x]);
            }
        }  
    }
    
    public function testRegions()
    {
        $plot = new BulkPlot;
        $l1 = [19,3,2,7,9,18,4,15,12,13];
        $l2 = [17,20,19,17,19,5,13,8,15,8];
        
        $plot->add('line', [$l1], [
            'title' => 'Regions',
            'xseries' => range(1, 10),
            'font' => [FF_FONT1, FS_NORMAL, 8],
            'regions' => [
                ['x' => 3, 'y' => 20, 'x2' => 7, 'y2' => 15, 'color' => 'red@0.3'],
                ['x' => 6, 'y' => 2, 'x2' => 10, 'y2' => 0, 'color' => 'red@0.3']
            ]
        ]);
        
        [$img] = $plot->render(700, 500, false);
        $rendered = imagecreatefromstring($img); 
        $example = imagecreatefrompng(__DIR__."/regions.png");
        
        $rpixels = $this->pixels($rendered);
        $epixels = $this->pixels($example);
        
        foreach (sequence(0, 499) as $y) {
            foreach (sequence(0, 699) as $x) { 
                $this->assertEquals($epixels[$y][$x], $rpixels[$y][$x]);
            }
        }  
    }
    
    public function testStockplotAndConfigCallback()
    {
        $plot = new BulkPlot;
        
        $l1 = [2,6,4,5, 4,8,1,3, 3,7,2,4, 5,9,4,6];
        $plot->add('stock', [$l1], [
            'title' => 'Candlesticks',
            'xseries' => range(1, 4),
            'font' => [FF_FONT1, FS_NORMAL, 8],
            'margin' => [55,55,55,55],
            'configCallback' => function($chart) {
                $chart->yscale->ticks->SupressZeroLabel(false);
                $chart->xscale->ticks->SupressZeroLabel(false);
                $chart->SetClipping(false);
            }
        ]);
        
        [$img] = $plot->render(700, 500, false);
        $rendered = imagecreatefromstring($img); 
        $example = imagecreatefrompng(__DIR__."/candlesticks.png");
        
        $rpixels = $this->pixels($rendered);
        $epixels = $this->pixels($example);
        
        foreach (sequence(0, 499) as $y) {
            foreach (sequence(0, 699) as $x) { 
                $this->assertEquals($epixels[$y][$x], $rpixels[$y][$x]);
            }
        }  
    }
}