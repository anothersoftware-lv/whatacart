<?php
use usni\UsniAdaptor;
use usni\library\utils\ArrayUtil;

$folder     = UsniAdaptor::app()->getSession()->get('installerFolder');
$configData = require $folder . '/install.php';
$compatibleVersions = ArrayUtil::getValue($configData, 'product_version');
if ($compatibleVersions != null)
{
    $compatibleVersions = implode(',', $compatibleVersions);
}
$extensionData = ArrayUtil::getValue($configData, 'data');

$this->params['breadcrumbs'] =  [
                                    [
                                        'label' => UsniAdaptor::t('application', 'Manage') . ' ' .
                                                    UsniAdaptor::t('extension', 'Extensions'),
                                        'url' => ['/extension/default/index']
                                    ],
                                    [
                                        'label' => UsniAdaptor::t('extension', 'Extension Summary')
                                    ]
                                ];
?>
<div class='panel panel-default'>
    <div class='panel-heading'>
        <h6 class='panel-title'><?php echo UsniAdaptor::t('extension', 'Extension Summary'); ?></h6>
    </div>
    <div class='panel-body'>
        <h3><?php echo UsniAdaptor::t('extension', 'Overview'); ?></h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <tr>
                    <td><?php echo UsniAdaptor::t('application', 'Name'); ?></td>
                    <td><?php echo $configData['name']; ?></td>
                </tr>
                <tr>
                    <td><?php echo UsniAdaptor::t('application', 'Category'); ?></td>
                    <td><?php echo $configData['category']; ?></td>
                </tr>
                <tr>
                    <td><?php echo UsniAdaptor::t('application', 'Author'); ?></td>
                    <td><?php echo ArrayUtil::getValue($configData, 'author'); ?></td>
                </tr>
                <tr>
                    <td><?php echo UsniAdaptor::t('application', 'Version'); ?></td>
                    <td><?php echo ArrayUtil::getValue($configData, 'version'); ?></td>
                </tr>
                <tr>
                    <td><?php echo UsniAdaptor::t('application', 'Product Version'); ?></td>
                    <td><?php echo $compatibleVersions; ?></td>
                </tr>
            </table>
        </div>
        <?php
        $subView = UsniAdaptor::getAlias('@common/modules/extension/views/_data.php');
        echo UsniAdaptor::app()->getView()->renderPhpFile($subView, ['extensionData' => $extensionData]);
        ?>
    </div>
    <div class="panel-footer">
        <div class="form-actions text-right">
            <a href="<?php echo UsniAdaptor::createUrl('extension/upload/complete'); ?>" class="btn btn-primary"><?php echo UsniAdaptor::t('application', 'Continue'); ?></a>
            <a href="<?php echo UsniAdaptor::createUrl('extension/upload/create'); ?>" class="btn btn-default"><?php echo UsniAdaptor::t('application', 'Cancel'); ?></a>
        </div>
    </div>
</div>
