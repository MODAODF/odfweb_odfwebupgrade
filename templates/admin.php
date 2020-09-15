<?php
script('odfwebupgrade', 'uploadZip');
/** @var array $_ */
?>
<div id="odfwebupgrade" class="section">
    <h3><b>Odfweb <?php p($l->t('Upgrade')) ?></b></h3>
    <form id="updaterForm" method="post" enctype="multipart/form-data">
        <span style="font-size:15px;"><?php p($l->t('Select a zip file')) ?></span>
        <input id="updaterSecret" name="updater-secret-input" type="hidden">
        <input id="zipFile" name="zipFile" type="file" class="uploadButton">
        <a ref="#" class="button"><?php p($l->t('Open updater')) ?></a>
    </form>
</div>
