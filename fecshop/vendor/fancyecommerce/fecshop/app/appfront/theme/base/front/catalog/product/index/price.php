<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<?php  $price_info = $parentThis['price_info'];   ?>
<?php if(isset($price_info['special_price']['value'])):  ?>			
	<div class="special_price special_active">
		<?= $price_info['special_price']['symbol']  ?><?= $price_info['special_price']['value'] ?>元/日
	</div>
	<div class="price special_active">
		<?= $price_info['price']['symbol']  ?>
		<?= $price_info['price']['value'] ?>元/日
	</div>
	<div class="clear"></div>
<?php else:  ?>
	<div class="price no-special">
		<?= $price_info['price']['symbol']  ?><?= $price_info['price']['value'] ?>元/日
	</div>
<?php endif; ?>
