
<table class="default">
    <tbody>
        <? if (count($users)) : ?>
        <? foreach ($users as $user_id) : ?>
        <tr>
            <td>
                <?= Avatar::getAvatar($user_id)->getImageTag(Avatar::MEDIUM, array('style' => "width: 50px; height: 50px;")) ?>
            </td>
            <td>
                <?= htmlReady(get_fullname($user_id)) ?>
            </td>
        </tr>
        <? endforeach ?>
        <? else : ?>
        <tr>
            <td style="text-align: center;" colspan="2">
                <?= _("Noch hat niemand teilgenommen.") ?>
            </td>
        </tr>
        <? endif ?>
    </tbody>
</table>

<div data-dialog-button>
    <?= \Studip\LinkButton::create(
        _("Exportieren"),
        URLHelper::getURL("plugins.php/fastpollplugin/polls/export_users", array('poll_id' => Request::option("poll_id")))
    ) ?>

</div>