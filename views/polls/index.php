<? if (count(FastPollPlugin::$msg)) {
    foreach (FastPollPlugin::$msg as $msg) {
        $type = $msg[0];
        $message = $msg[1];
        echo MessageBox::$type($message);
    }
} ?>

<h1><?= _("Schnellumfragen") ?></h1>
<table class="default">
    <thead>
        <tr>
            <th><?= _("Name") ?></th>
            <th><?= _("Zusagen") ?></th>
            <th><?= _("Serienbrieflink") ?></th>
            <th><?= _("Erstellt") ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <? if (count($polls)) : ?>
        <? foreach ((array) $polls as $poll) : ?>
        <tr data-poll_id="<?= $poll->getId() ?>">
            <td><?= htmlReady($poll['name']) ?></td>
            <td>
                <a href="<?= PluginEngine::getLink($plugin, array('poll_id' => $poll->getId()), "polls/show_users") ?>"
                   data-dialog>
                    <?= count($poll->getUsers()) ?>
                </a>
            </td>
            <td>
                <a href="<?= URLHelper::getLink("plugins.php/serienbriefe/write/overview", array('message' => $GLOBALS['ABSOLUTE_URI_STUDIP']."plugins.php/fastpollplugin/polls/yes/".$poll->getId()."?u={{username}}")) ?>">
                    <?= $GLOBALS['ABSOLUTE_URI_STUDIP']."plugins.php/fastpollplugin/polls/yes/".$poll->getId()."?u={{username}}" ?>
                </a>
            </td>
            <td><?= date("G:i j.n.Y", $poll['mkdate']) ?></td>
            <td>
                <form action="?" method="post" onSubmit="return window.confirm('<?= _("Wirklich löschen?") ?>');">
                    <input type="hidden" name="delete" value="<?= htmlReady($poll->getId()) ?>">
                    <button type="submit" style="border: none; background:none; cursor: pointer;">
                        <?= Icon::create("trash", "clickable")->asImg(20, array('class' => "text-bottom")) ?>
                    </button>
                </form>
            </td>
        </tr>
        <? endforeach ?>
        <? else : ?>
        <tr>
            <td style="text-align: center;" colspan="5">
                <?= _("Keine aktuellen Schnellumfragen ") ?>
            </td>
        </tr>
        <? endif ?>
    </tbody>
</table>

<?
$actions = new ActionsWidget();
$actions->addLink(
    _("Neue Schnellumfrage erstellen"),
    PluginEngine::getURL($plugin, array(), "polls/create"),
    Icon::create("search", "clickable"),
    array(
        'data-dialog' => "1"
    )
);
Sidebar::Get()->addWidget($actions);
