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
    static protected $namespace = "\sqonk\phext\plotlib\internal\jpgraph";
    
    /**
     * Set or Return the namespace of the underlying JPGraph library that will be used by libary
     * to render charts.
     * 
     * By default it returns the internal copy of JPGraph provided by the library.
     */
    static public function namespace(?string $newNamespace = null)
    {
        if ($newNamespace === null)
            return self::$namespace;
        
        self::$namespace = $newNamespace;
    }
    
    static public function class(string $className): \ReflectionClass
    {
        $path = self::namespace()."\\$className";
        return new \ReflectionClass($path);
    }
    
    /**
     * Force the provided JPGraph object to render its contents, capturing
     * the output and returning it to the caller.
     */
    static public function render($chart): ?string
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