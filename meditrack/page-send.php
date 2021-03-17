<?php	
	if (@$_POST['sendasset']) {
		$success=no_displayed_error_result($sendtxid, multichain('sendassetfrom',
			$_POST['from'], $_POST['to'], $_POST['asset'], floatval($_POST['qty'])));
				
		if ($success)
			output_success_text('Package successfully sent in transaction '.$sendtxid);
	}
?>
<section id="main-content">
	<section class="wrapper">
		<div class="table-agile-info">
			<div class="panel panel-default">
                <div class="panel-heading" style="padding-top: 12px;">
                    <h3>  Available Balances  </h3>
                </div>
            </div>
			<?php
				$sendaddresses=array();
				$usableaddresses=array();
				$keymyaddresses=array();
				$keyusableassets=array();
				$haslocked=false;
				$getinfo=multichain_getinfo();
				$labels=array();
				
				if (no_displayed_error_result($getaddresses, multichain('getaddresses', true))) {
					
					if (no_displayed_error_result($listpermissions,
						multichain('listpermissions', 'send', implode(',', array_get_column($getaddresses, 'address')))
					))
						$sendaddresses=array_get_column($listpermissions, 'address');
						
					foreach ($getaddresses as $address)
						if ($address['ismine'])
							$keymyaddresses[$address['address']]=true;

					$labels=multichain_labels();

					if (no_displayed_error_result($listpermissions, multichain('listpermissions', 'receive')))
						$receiveaddresses=array_get_column($listpermissions, 'address');
					
					foreach ($sendaddresses as $address) {
						if (no_displayed_error_result($allbalances, multichain('getaddressbalances', $address, 0, true))) {

							if (count($allbalances)) {
								$assetunlocked=array();
								
								if (no_displayed_error_result($unlockedbalances, multichain('getaddressbalances', $address, 0, false))) {
									if (count($unlockedbalances))
										$usableaddresses[]=$address;
										
									foreach ($unlockedbalances as $balance)
										$assetunlocked[$balance['name']]=$balance['qty'];
								}
								
								$label=@$labels[$address];

			?>
			<table class="table table-bordered table-condensed table-break-words <?php echo ($address==@$getnewaddress) ? 'bg-success' : 'table-striped'?>">
			<?php
				if (isset($label)) {
			?>
					<tr>
						<th style="width:25%;">Label</th>
						<td><?php echo html($label)?></td>
					</tr>
			<?php
				}
			?>
				<tr>
					<th style="width:20%;">Address</th>
					<td class="td-break-words small"><?php echo html($address)?></td>
				</tr>
			<?php
				foreach ($allbalances as $balance) {
					$unlockedqty=floatval($assetunlocked[$balance['name']]);
					$lockedqty=$balance['qty']-$unlockedqty;
					
					if ($lockedqty>0)
						$haslocked=true;
					if ($unlockedqty>0)
						$keyusableassets[$balance['name']]=true;
			?>
					<tr>
						<th><?php echo html($balance['name'])?></th>
						<td><?php echo html($unlockedqty)?><?php echo ($lockedqty>0) ? (' ('.$lockedqty.' locked)') : ''?></td>
					</tr>
			<?php
				}
			?>
			</table>
			<?php
							}
						}
					}
				}
			?>
		</div>
	</section>
	<section class="wrapper">
		<div style="margin-bottom: 50px;" class="table-agile-info">
			<div class="panel panel-default">
                <div class="panel-heading" style="padding-top: 12px;">
                    <h3>  Send Package  </h3>
                </div>
            </div>	
			<form class="form-horizontal" method="post" action="./?chain=<?php echo html($_GET['chain'])?>&page=<?php echo html($_GET['page'])?>">
				<div class="form-group">
					<div class="row row-width">
						<label for="from" class="col-lg-2 control-label">From address:</label>
						<div class="col-lg-10">
							<select class="form-control col-sm-8" name="from" id="from">
<?php
	foreach ($usableaddresses as $address) {
?>
									<option value="<?php echo html($address)?>"><?php echo format_address_html($address, true, $labels)?></option>
<?php
	}
?>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row row-width">
						<label for="asset" class="col-lg-2 control-label">Package name:</label>
						<div class="col-lg-10">
							<select class="form-control col-sm-8" name="asset" id="asset">
							<?php
								foreach ($keyusableassets as $asset => $dummy) {
							?>
									<option value="<?php echo html($asset)?>"><?php echo html($asset)?></option>
							<?php
								}
							?>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row row-width">
						<label for="to" class="col-lg-2 control-label">To address:</label>
						<div class="col-lg-10">
							<select class="form-control col-sm-8" name="to" id="to">
							<?php
								foreach ($receiveaddresses as $address) {
									if ($address==$getinfo['burnaddress'])
										continue;
							?>
										<option value="<?php echo html($address)?>"><?php echo format_address_html($address, @$keymyaddresses[$address], $labels)?></option>
							<?php
								}
							?>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row row-width">
						<label for="qty" class="col-lg-2 control-label">Quantity:</label>
						<div class="col-lg-10">
							<input class="col-sm-8 form-control" name="qty" id="qty" placeholder="0.0">
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row row-width">
						<div class="col-sm-3"></div>
						<div class="col-sm-offset-3 col-sm-6">
							<input class="btn btn-primary" style="float:right;" type="submit" name="sendasset" value="Send Package">
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
	<div class="footer" style="margin-top: 50px"></div>
</section>