<?php
/**
 * Ask if we set the new theme as current theme for the manager
 *
 * @package smooth
 * @subpackage build
 */
 
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
		case xPDOTransport::ACTION_UPGRADE:
		
			$modx =& $object->xpdo;
			
			if( isset($options['set_default']) ){
		
				$modx->log(modX::LOG_LEVEL_INFO,'Set Smooth Manager Theme as default for the manager...');
				
				/* Set the themme as default */
				$setting = $modx->getObject('modSystemSetting',array(
					'key' => 'manager_theme',
				));
				
				$value = $setting->get('value');
				if($value == 'default'){
					$setting->set('value', 'smooth');
					$setting->save();
				}
									
				unset($settings, $setting, $value);
				
				$modx->log(modX::LOG_LEVEL_INFO,'Refresh your browser to view the Smooth Manager Theme in action!');
			}		
		break;
		/* Does not work */
		case xPDOTransport::ACTION_UNINSTALL:
		
			$modx =& $object->xpdo;
					
			$setting = $modx->getObject('modSystemSetting',array(
				'key' => 'manager_theme',
			));			
			$value = $setting->get('value');
			if($value == 'smooth'){
				$setting->set('value', 'default');
				$setting->save();
			}						
			unset($settings, $setting, $value);
			
			$modx->log(modX::LOG_LEVEL_INFO,'Refresh your browser to reload the default Manager Theme');
		break;
    }
}
return true;
		