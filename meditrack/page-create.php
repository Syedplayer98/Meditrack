<?php

	$success=false; // set default value

	if (@$_POST['createstream']) {
		$success=no_displayed_error_result($createtxid, multichain('createfrom',
			$_POST['from'], 'stream', $_POST['name'], true));
				
		if ($success)
			output_success_text('Stream successfully created in transaction '.$createtxid);
	}
	
	$labels=multichain_labels();

	if (no_displayed_error_result($getaddresses, multichain('getaddresses', true))) {
		foreach ($getaddresses as $index => $address)
			if (!$address['ismine'])
				unset($getaddresses[$index]);
				
		if (no_displayed_error_result($listpermissions,
			multichain('listpermissions', 'create', implode(',', array_get_column($getaddresses, 'address')))
		))
			$createaddresses=array_unique(array_get_column($listpermissions, 'address'));
	}
	
	no_displayed_error_result($liststreams, multichain('liststreams', '*', true));

?>
<section id="main-content">
	<section class="wrapper">
		<div class="table-agile-info">
			<div class="panel panel-default">
                <div class="panel-heading" style="padding-top: 12px;">
                    <h3>  Create Stream  </h3>
                </div>
            </div>
			<form class="form-horizontal" method="post" action="./?chain=<?php echo html($_GET['chain'])?>&page=<?php echo html($_GET['page'])?>">
				<div class="form-group">
					<div class="row row-width">
						<label for="from" class="col-lg-2 control-label">From address:</label>
						<div class="col-lg-10">
							<select class="form-control col-sm-8" name="from" id="from">
<?php
	foreach ($createaddresses as $address) {
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
						<label for="name" class="col-lg-2 control-label">Stream name:</label>
						<div class="col-lg-10">
							<input class="form-control col-sm-8" name="name" id="name" placeholder="stream1">
							<span id="helpBlock" class="help-block small text-muted"> &nbsp In this demo, the stream will be open, so anyone can write to it.</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row row-width">
						<div class="col-sm-3"></div>
						<div class="col-sm-offset-2 col-sm-6">
							<input class="btn btn-primary" style="float:right;" type="submit" name="createstream" value="Create Stream">
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
	<section class="wrapper">
		<div class="table-agile-info">
			<div class="panel panel-default">
                <div class="panel-heading" style="padding-top: 12px;">
                    <h3>  Streams  </h3>
                </div>
            </div>
			
<?php
	foreach ($liststreams as $stream) {
?>
			<table class="table table-bordered table-condensed table-break-words <?php echo ($success && ($stream['name']==@$_POST['name'])) ? 'bg-success' : 'table-striped'?>">
				<tr>
					<th style="width:30%;">Name</th>
					<td><?php echo html($stream['name'])?></td>
				</tr>
				<tr>
					<th>Opened by</th>
					<td class="td-break-words small"><?php echo format_address_html($stream['creators'][0], false, $labels)?></td>
				</tr>
				<tr>
					<th>Items</th>
					<td><?php
						if ($stream['subscribed']) { 

							?><?php echo html($stream['items'])?><?php

						} else {

							?>not subscribed<?php

							}
					?></td>
				</tr>
			</table>
<?php
	}
?>
		</div>
	</section>
	<div class="footer" style="margin-top: 50px"></div>
</section>