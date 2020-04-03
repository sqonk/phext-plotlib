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
    A visual rectangular region on a graph that represents an area between datapoints.
*/
class Region
{
    protected $xleft;
    protected $ytop;
    protected $xright;
    protected $ybottom;
    protected $colour;
    
    public function __construct($xstart, $ystart, $xend, $yend, $colour)
    {
        $this->xleft = $xstart;
        $this->ytop = $ystart;
        $this->xright = $xend;
        $this->ybottom = $yend;
        $this->colour = $colour;
    }
    
    // Framework function to allow the object to adjust the scale
    function PrescaleSetup($aGraph) {
        // Nothing to do
    }
    
    function PreStrokeAdjust($aGraph) {
        // Nothing to do
    }
    
    function DoLegend($graph) {
        
    }
    
    function Min() {
        return array(null,null);
    }

    function Max() {
        return array(null,null);
    }
    
    function StrokeMargin($aImg) {
        // Nothing to do
    }
    
    function Stroke($img, $xscale, $yscale) 
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