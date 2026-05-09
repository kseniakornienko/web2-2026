<h2>Список файлов</h2>
<?=$message?><br>
<?php foreach ($files as $file):?>
    <a href="/doc/<?=$file?>"><?=$file?></a><br>
<?php endforeach;?>
<br>
<form>
    <input type="file">
    <input type="submit" value="Загрузить">
</form>