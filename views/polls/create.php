<form action="<?= PluginEngine::getLink($plugin, array(), "polls/index") ?>" method="post" class="default">
    <input type="hidden" name="create" value="1">
    <input type="text" name="name" placeholder="<?= _("Name der Schnellumfrage") ?>" style="font-size: 1.4em; width: 100%;">
    <div data-dialog-button>
        <?= \Studip\Button::create(_("Erstellen"), "erstellen") ?>
    </div>
</form>