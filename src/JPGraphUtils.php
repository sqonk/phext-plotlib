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

use Amenadiel\JpGraph\Graph as JPGraph;

class jputils
{
    static public function render(JPGraph\Graph $chart)
    {
        ob_start();
		try {
			$chart->Stroke();
	        $img = ob_get_contents();
		}
		finally {
			ob_end_clean();
		}
        return $img;
    }
}