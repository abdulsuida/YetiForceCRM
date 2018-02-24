<?php
/**
 * Settings Password Encryption View.
 *
 * @copyright YetiForce Sp. z o.o
 * @license YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author Tomasz Kur <t.kur@yetiforce.com>
 */

/**
 * View to configuration of encryption.
 */
class Settings_Password_Encryption_View extends Settings_Vtiger_Index_View
{
	/**
	 * {@inheritdoc}
	 */
	public function process(\App\Request $request)
	{
		$viewer = $this->getViewer($request);
		$encryptionInstance = App\Encryption::getInstance();
		$methods = App\Encryption::getMethods();
		$lengthVectors = [];
		foreach ($methods as $methodName) {
			$lengthVectors[$methodName] = \App\Encryption::getLengthVector($methodName);
		}
		$viewer->assign('ENCRYPT', $encryptionInstance);
		$viewer->assign('CRON_TASK', \vtlib\Cron::getInstance('LBL_BATCH_METHODS'));
		$viewer->assign('AVAILABLE_METHODS', $methods);
		$viewer->assign('MAP_LENGTH_VECTORS_METHODS', $lengthVectors);
		$viewer->assign('IS_RUN_ENCRYPT', Settings_Password_Record_Model::isRunEncrypt());
		$viewer->view('Encryption.tpl', $request->getModule(false));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFooterScripts(\App\Request $request)
	{
		$headerScriptInstances = parent::getFooterScripts($request);
		$moduleName = $request->getModule();
		$jsFileNames = [
			"modules.Settings.$moduleName.resources.Encryption",
		];
		$jsScriptInstances = $this->checkAndConvertJsScripts($jsFileNames);
		return array_merge($headerScriptInstances, $jsScriptInstances);
	}
}
