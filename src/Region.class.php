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

/**
 * A visual rectangular region on a graph that represents an area between data points.
 * 
 * This class is used by BulkPlot, which provides an interface to creating regions through
 * the configuration options when adding a plot. 
 * 
 * You do not need to work with this class directly when working with BulkPlot.
 */
class Region
{
    protected $xleft;
    protected $ytop;
    protected $xright;
    protected $ybottom;
    protected $colour;
    
    /**
     * Construct a new Region at the given co-ordinates.
     * 
     * -- parameters:
     * @param $xstart left-most point x-axis.
     * @param $ystart top-most point on the y-axis.
     * @param $xend right-most point on the x-axis.
     * @param $yend bottom-most point on the y-axis.
     */
    public function __construct($xstart, $ystart, $xend, $yend, $colour)
    {
        $this->xleft = $xstart;
        $this->ytop = $ystart;
        $this->xright = $xend;
        $this->ybottom = $yend;
        $this->colour = $colour;
    }
    
    // Framework function to allow the object to adjust the scale
    public function PrescaleSetup($aGraph) {
        // Nothing to do
    }
    
    public function PreStrokeAdjust($aGraph) {
        // Nothing to do
    }
    
    public function DoLegend($graph) {
        
    }
    
    public function Min() {
        return array(null,null);
    }

    public function Max() {
        return array(null,null);
    }
    
    public function StrokeMargin($aImg) {
        // Nothing to do
    }
    
    public function Stroke($img, $xscale, $yscale) 
    {
        $img->SetColor($this->colour);
        $img->SetLineWeight(1);
        
        $ytop = ($this->ytop !== null) ? $this->ytop : $yscale->GetMaxVal();
        $ybottom = ($this->ybottom !== null) ? $this->ybottom : $yscale->GetMinVal();
        
        $x1 = $xscale->Translate($this->xleft);
        $x2 = $xscale->Translate($this->xright);
        $y1 = $yscale->Translate($ytop);
        $y2 = $yscale->Translate($ybottom);
        $img->FilledRectangle($x1, $y1, $x2, $y2);
    }
}