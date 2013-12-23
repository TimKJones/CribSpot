<? function removeForm($id) {?>
  <form style="display: inline;" id="remove-<?echo($id);?>" method="post" action="/friends/hotlist/remove">
    <input type="hidden" name="friend_id" value="<?echo($id);?>"></input>
    <a href="#" onclick="document.getElementById('remove-<?echo($id);?>').submit(); return false;">x</a>
  </form>
<?}?>
<ul>
<? foreach ($response as $friend) {?>
  <li>
    <span><?echo($friend['first_name'] . ' ' . $friend['last_name']);?></span> <? removeForm($friend['id']); ?>
  </li>
<?}?>
</ul>

<form action="/friends/hotlist/add" method="POST">
  <select name="friend_id">
    <? foreach ($users as $user) { ?>
    <option value="<?echo($user['User']['id']);?>"><?echo($user['User']['first_name'] . ' ' . $user['User']['last_name']);?></option>
    <?}?>
  </select>
  <input type="submit"></input>
</form>

