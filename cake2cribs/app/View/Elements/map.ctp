<? if (Configure::read('CURRENT_ENVIRONMENT') !== 'ENVIRONMENT_PRODUCTION') { ?>
	<?= $this->Html->script('src/Map', array('inline' => false)) ?>
<? } ?>

<?=$this->Html->css('/less/map.less?','stylesheet/less', array('inline' => false))?>

<div id="map_region" data-listing-type="<?= $active_listing_type ?>" data-university-name="<?= $university["name"] ?>" data-university-id="<?= $university["id"] ?>" data-latitude="<?= $university["latitude"] ?>" data-longitude="<?= $university["longitude"] ?>" data-city="<?= $university["city"] ?>" data-state="<?= $university["state"] ?>">
	<?= $this->element('filter', array('active_listing_type' => $active_listing_type)) ?>
	<?= $this->element('legend') ?>

	<div id="map_canvas"></div>
</div>

<!-- Popups important for the mapview -->
<div class="hide">
	<?= $this->element('small-bubble') ?>
</div>
<?= $this->element('large-bubble', array('active_listing_type' => $active_listing_type)) ?>