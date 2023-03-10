<?php
namespace OCA\OdfwebUpgrade\Settings;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\Settings\ISettings;
use OCP\IURLGenerator;

class Admin implements ISettings {
	/** @var IConfig */
	private $config;
	/** @var IURLGenerator */
	private $urlGenerator;

	public function __construct(IConfig $config, IURLGenerator $urlGenerator) {
		$this->config = $config;
		$this->urlGenerator = $urlGenerator;
	}

	/**
	 * @return TemplateResponse
	 */
	public function getForm(): TemplateResponse {
		$uploadRoute = $this->urlGenerator->linkToRoute('odfwebupgrade.Admin.uploadZip');

		// 伺服器限制提示
		$iniWrapper = \OC::$server->get(\bantu\IniGetWrapper\IniGetWrapper::class);
		$uploadMax = $iniWrapper->getBytes('upload_max_filesize');
		$postMax = $iniWrapper->getBytes('post_max_size');
		$serverLimit = [
			'maxByte' => min($uploadMax, $postMax),
			'maxString' => ($uploadMax < $postMax) ? $iniWrapper->getString('upload_max_filesize') : $iniWrapper->getString('post_max_size'),
		];
		return new TemplateResponse('odfwebupgrade', 'admin', ['uploadRoute'=>$uploadRoute, 'serverLimit'=>$serverLimit]);
	}

	/**
	 * @return string the section ID, e.g. 'sharing'
	 */
	public function getSection(): string {
		return 'overview';
	}

	/**
	 * @return int whether the form should be rather on the top or bottom of
	 * the admin section. The forms are arranged in ascending order of the
	 * priority values. It is required to return a value between 0 and 100.
	 *
	 * E.g.: 70
	 */
	public function getPriority(): int {
		return 11;
	}
}
