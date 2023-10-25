<?php
script('odfwebupgrade', 'uploadZip');
/** @var array $_ */
?>
<div id="odfwebupgrade" class="section">
    <h3><b>Odfweb <?php p($l->t('Upgrade')) ?></b></h3>
    <p class="settings-hint">
        <input type="hidden" id="serverLimit" value="<?php p($_['serverLimit']['maxByte']) ?>">
        (<?php p($l->t('The maximum upload size of the server')) ?> : <?php p($_['serverLimit']['maxString']) ?>)
    </p>
    <form class="uploadForm" method="post" action="<?php p($_['uploadRoute']) ?>" style="display: inline-block;">
        <span style="font-size:15px;"><?php p($l->t('Select a file (.zip)')) ?></span>
		<input id="uploadZip" class="fileupload" name="uploadZip" type="file" aria-label="<?php p($l->t('Upload File')) ?>">
	</form>
    <button class="button openUpdater" disabled><?php p($l->t('Open updater')) ?></button>
    <span class="msg"></span>
</div>
