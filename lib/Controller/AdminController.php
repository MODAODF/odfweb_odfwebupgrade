<?php
namespace OCA\OdfwebUpgrade\Controller;

use OCA\OdfwebUpgrade\ResetTokenBackgroundJob;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\IJobList;
use OCP\IConfig;
use OCP\IL10N;
use OCP\IRequest;
use OCP\Security\ISecureRandom;
use OCP\Files\IRootFolder;

class AdminController extends Controller {
	/** @var IJobList */
	private $jobList;
	/** @var ISecureRandom */
	private $secureRandom;
	/** @var IConfig */
	private $config;
	/** @var ITimeFactory */
	private $timeFactory;
	/** @var IRootFolder */
	private $rootFolder;

	/**
	 * @param string $appName
	 * @param IRequest $request
	 * @param IJobList $jobList
	 * @param ISecureRandom $secureRandom
	 * @param IConfig $config
	 * @param ITimeFactory $timeFactory
	 * @param IRootFolder $rootFolder
	 */
	public function __construct($appName,
								IRequest $request,
								IJobList $jobList,
								ISecureRandom $secureRandom,
								IConfig $config,
								ITimeFactory $timeFactory,
								IRootFolder $rootFolder) {
		parent::__construct($appName, $request);
		$this->jobList = $jobList;
		$this->secureRandom = $secureRandom;
		$this->config = $config;
		$this->timeFactory = $timeFactory;
		$this->rootFolder = $rootFolder;
	}

	/**
	 * @return DataResponse
	 */
	public function createCredentials(): DataResponse {
		// Create a new job and store the creation date
		$this->jobList->add(ResetTokenBackgroundJob::class);
		$this->config->setAppValue('core', 'updater.secret.created', $this->timeFactory->getTime());

		// Create a new token
		$newToken = $this->secureRandom->generate(64);
		$this->config->setSystemValue('updater.secret', password_hash($newToken, PASSWORD_DEFAULT));

		return new DataResponse($newToken);
	}

	/**
	 * @return DataResponse
	 */
	public function uploadZip(): DataResponse {

		try {
			// Check zip
			$zipFile = $this->request->getUploadedFile('uploadZip');
			if (!$zipFile) {
				throw new \Exception('沒有上傳檔案');
			}
			if (mime_content_type($zipFile['tmp_name']) !== "application/zip") {
				throw new \Exception('非 Zip 檔');
			}
			if ($zipFile['size'] === 0)  {
				throw new \Exception('size too small');
			}

			$folderTmp = '/updaterTmp-' . $this->config->getSystemValue('instanceid');

			// rm old tmp dir
			if ($this->rootFolder->nodeExists($folderTmp)) {
				$this->rootFolder->get($folderTmp)->delete();
			}

			// create Tmp folder
			$statNewFolder = $this->rootFolder->newFolder($folderTmp);
			if (!$statNewFolder) {
				throw new \Exception('can not create tmp folder');
			}

			// Move upload file into data/updaterTmp/
			$filePath = 'data/' . $folderTmp . '/' . $zipFile['name'];
			$statMove = move_uploaded_file($zipFile['tmp_name'], $filePath);
			if(!$statMove) {
				throw new \Exception('Could not move_uploaded_file into data/');
			}

		} catch (\Exception $th) {
			return new DataResponse([
				'data' => [ 'message' => $th->getMessage()],
				'result' => false,
			]);
		}

		return new DataResponse([
			'data' => [ 'message' => 'Zip Uploaded!'],
			'result' => true,
		]);
	}

}
