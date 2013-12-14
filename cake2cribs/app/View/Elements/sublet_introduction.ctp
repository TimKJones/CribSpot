<?=$this->Html->css('/less/sublet_introduction.less?','stylesheet/less', array('inline' => false))?>

<? if (strpos($university["name"], 'Detroit') === false) { ?>
<div id="sublet_introduction">
  <button type="button" class="close" onclick="$('#sublet_introduction').fadeOut()">&times;</button>
  <div>
    <? if (empty($university['sublets_launch_date']) || $university['sublets_launch_date'] > date('Y-m-d')) {
      $date_string = $this->Time->nice($university['sublets_launch_date'], null, '%A, %b %eS');
    ?>
      Sublets will be launching <?= $date_string ?>!
    <? } else { ?>
      Sublets have launched!
    <? } ?>
  </div>
  <a href="/sublet/welcome" target="_blank" class="btn">Post my sublet today</a>
</div>
<? } ?>
