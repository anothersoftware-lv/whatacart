<?php
use usni\UsniAdaptor;
use usni\library\utils\ArrayUtil;
use usni\library\utils\FileUtil;
$copyFolders = ArrayUtil::getValue($extensionData, 'copyFolders', null);
$copyFiles = ArrayUtil::getValue($extensionData, 'copyFiles', null);
$deleteFiles = ArrayUtil::getValue($extensionData, 'deleteFiles', null);
$modifiedFiles = ArrayUtil::getValue($extensionData, 'modifiedFiles', null);
$sqls = ArrayUtil::getValue($extensionData, 'sqls', null);
?>
<?php
if (!empty($copyFolders))
{
    ?>
    <br/>
    <h3><?php echo UsniAdaptor::t('extension', 'Copy Folders'); ?></h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><?php echo UsniAdaptor::t('extension', 'Source Folder'); ?></th>
                    <th><?php echo UsniAdaptor::t('extension', 'Target Folder'); ?></th>
                </tr>
            </thead>
            <?php
            foreach ($copyFolders as $copyFolder)
            {
                ?>
                <tr>
                    <td><?php echo $copyFolder['sourceFolder']; ?></td>
                    <td><?php echo FileUtil::normalizePath(UsniAdaptor::getAlias($copyFolder['targetFolder'])); ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php
}
if (!empty($copyFiles))
{
    ?>
    <br/>
    <h3><?php echo UsniAdaptor::t('extension', 'Copy Files'); ?></h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><?php echo UsniAdaptor::t('extension', 'Source File'); ?></th>
                    <th><?php echo UsniAdaptor::t('extension', 'Target Folder'); ?></th>
                </tr>
            </thead>
            <?php
            foreach ($copyFiles as $copyFile)
            {
                ?>
                <tr>
                    <td><?php echo $copyFile['sourceFile']; ?></td>
                    <td><?php echo FileUtil::normalizePath(UsniAdaptor::getAlias($copyFile['targetFolder'])); ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php
}
if (!empty($deleteFiles))
{
    ?>
    <br/>
    <h3><?php echo UsniAdaptor::t('extension', 'Delete Files'); ?></h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><?php echo UsniAdaptor::t('extension', 'Source File'); ?></th>
                    <th><?php echo UsniAdaptor::t('extension', 'Target Folder'); ?></th>
                </tr>
            </thead>
            <?php
            foreach ($deleteFiles as $deleteFile)
            {
                ?>
                <tr>
                    <td><?php echo $deleteFile['sourceFile']; ?></td>
                    <td><?php echo FileUtil::normalizePath(UsniAdaptor::getAlias($deleteFile['targetFolder'])); ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php
}
if (!empty($modifiedFiles))
{
    ?>
    <br/>
    <h3><?php echo UsniAdaptor::t('extension', 'Modified Files'); ?></h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><?php echo UsniAdaptor::t('extension', 'Source File'); ?></th>
                    <th><?php echo UsniAdaptor::t('extension', 'Target Folder'); ?></th>
                    <th><?php echo UsniAdaptor::t('extension', 'Operations'); ?></th>
                </tr>
            </thead>
            <?php
            foreach ($modifiedFiles as $modifiedFile)
            {
                ?>
                <tr>
                    <td><?php echo $modifiedFile['sourceFile']; ?></td>
                    <td><?php echo FileUtil::normalizePath(UsniAdaptor::getAlias($modifiedFile['targetFolder'])); ?></td>
                    <td>
                        <table class="table table-striped table-bordered">
                            <thead>
                            <th>
                                <?php echo UsniAdaptor::t('application', 'Action'); ?>
                            </th>
                            <th>
                                <?php echo UsniAdaptor::t('application', 'Details'); ?>
                            </th>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($modifiedFile['operations'] as $operation)
                                {
                                    ?>
                                    <tr>
                                        <td><?php echo $operation['action']; ?></td>
                                        <td>
                                            <?php echo UsniAdaptor::t('application', 'Content') . ': ' . $operation['content']; ?>
                                            <br/>
                                            <?php echo UsniAdaptor::t('application', 'Data') . ': ' . $operation['data']; ?>
                                            <?php
                                            if ($operation['action'] == 'replace')
                                            {
                                                ?>
                                                <br/>
                                                <?php
                                                $index = ArrayUtil::getValue($operation, 'index', null);
                                                if ($index != null)
                                                {
                                                    echo UsniAdaptor::t('extension', 'Index') . ': ' . $operation['index'];
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php
}
if (!empty($sqls))
{
    ?>
    <br/>
    <h3><?php echo UsniAdaptor::t('extension', 'Sqls'); ?></h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th><?php echo UsniAdaptor::t('extension', 'Sql'); ?></th>
                </tr>
            </thead>
            <?php
            foreach ($sqls as $sql)
            {
                ?>
                <tr>
                    <td><?php echo $sql; ?></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php
}
    
