<?php
/**
* Build the setup options form.
*
* @package smooth
* @subpackage build
*/
/* set some default values */

/* do output html */
$output = '
<h2>Smooth Manager theme</h2>
<p>Thanks for installing this theme.</p>
<br />

<label for="set_default">Do you want to use the theme as default for the manager ? :</label>
<input type="checkbox" name="set_default" id="set_default" value="1" checked="checked"/>
<br />
';

return $output;