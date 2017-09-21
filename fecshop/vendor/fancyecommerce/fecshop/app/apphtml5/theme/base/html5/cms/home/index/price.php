<?php
/**
 * FecShop file.
 *
 * @link http://www.fecshop.com/
 * @copyright Copyright (c) 2016 FecShop Software LLC
 * @license http://www.fecshop.com/license/
 */
?>
<p class="proPrice">
	<?php if(isset($special_price) && !empty($special_price)):  ?>
		<span class="bizhong"><?= $special_price['code'] ?></span><span orgp="<?= $special_price['value'] ?>" class="my_shop_price f14"><span class="icon"><?= $special_price['symbol'] ?></span><?= $special_price['value'] ?>/日</span>
		<span class="bizhong"><?= $price['code'] ?></span><del orgp="<?= $price['value'] ?>" class="my_shop_price"><span class="icon"><?= $price['symbol'] ?></span><?= $price['value'] ?>／日</del>
	
	<?php else: ?>
		<span class="bizhong"><?= $price['code'] ?></span><span orgp="<?= $price['value'] ?>" class="my_shop_price f14"><span class="icon"><?= $price['symbol'] ?></span><?= $price['value'] ?>／日</span>
		
	<?php endif; ?>
</p>
