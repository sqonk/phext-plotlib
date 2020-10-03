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

function _jputils_handle_exception($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting
        return;
    }
    throw new \ErrorException($message, 0, $severity, $file, $line);
}

/**
 * A utilities class for working on a JPGraph object.
 * 
 * Current use is for reliably outputting the chart render while catching
 * internal errors.
 */
class jputils
{
    /**
     * Force the provided JPGraph object to render its contents, capturing
     * the output and returning it to the caller.
     */
    static public function render(JPGraph\Graph $chart)
    {
        $error = null;
        set_error_handler("\sqonk\phext\plotlib\_jputils_handle_exception");
        ob_start();
		try {
			$chart->Stroke();
	        $img = ob_get_contents();
		}
        catch (\Throwable $e) {
            $error = $e;
        }
		finally {
			ob_end_clean();
            restore_error_handler();
		}
        
        if ($error) {
            println($error);
            throw new \Exception($error->getMessage());
        }
        
        return $img;
    }
}