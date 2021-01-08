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

require_once __DIR__.'/TCFuncs.php';

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
        $this->assertSame(true, function_exists('imagecreatefromstring'));
    }
    
    public function testMultiLine()
    {
        $rendered = imagecreatefromstring(multiLines());
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
        $rendered = imagecreatefromstring(barsAndAuxlines());
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
        $rendered = imagecreatefromstring(backgroundBars()); 
        $example = imagecreatefrompng(__DIR__."/auxbars.png");
        
        $rpixels = $this->pixels($rendered);
        $epixels = $this->pixels($example);
        
        foreach (sequence(0, 499) as $y) {
            foreach (sequence(0, 699) as $x) { 
                $this->assertSame($epixels[$y][$x], $rpixels[$y][$x]);
            }
        }  
    }
    
    public function testInfiniteLines()
    {
        $rendered = imagecreatefromstring(infiniteLines()); 
        $example = imagecreatefrompng(__DIR__."/Infinite_Lines.png");
        
        $rpixels = $this->pixels($rendered);
        $epixels = $this->pixels($example);
        
        foreach (sequence(0, 499) as $y) {
            foreach (sequence(0, 699) as $x) { 
                $this->assertEquals($epixels[$y][$x], $rpixels[$y][$x], "RGB Mismatch @ $x,$y");
            }
        }  
    }
    
    public function testRegions()
    {
        $rendered = imagecreatefromstring(regions()); 
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
        $rendered = imagecreatefromstring(stockplot()); 
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