<?php
	$max_upload_size=multichain_max_data_size()-512; // take off space for file name and mime type
	$allow_multi_keys=multichain_has_multi_item_keys();

	if (@$_POST['publish']) {

		$upload=@$_FILES['upload'];
		$upload_file=@$upload['tmp_name'];

		if (strlen($upload_file)) {
			$upload_size=filesize($upload_file);

			if ($upload_size>$max_upload_size) {
				output_html_error('Uploaded file is too large ('.number_format($upload_size).' > '.number_format($max_upload_size).' bytes).');
				return;

			} else
				$data=bin2hex(file_to_txout_bin($upload['name'], $upload['type'], file_get_contents($upload_file)));

		} elseif (multichain_has_json_text_items()) { // use native JSON and text objects in MultiChain 2.0
			if (strlen($_POST['json'])) {
				$json=json_decode($_POST['json']);
				
				if ($json===null) {
					output_html_error('The entered JSON structure does not appear to be valid');
					return;
				} else
					$data=array('json' => $json);
					
			} else
				$data=array('text' => $_POST['text']);
		
		} else // convert entered text to binary for MultiChain 1.0
			$data=bin2hex(string_to_txout_bin($_POST['text']));
			
		$keys=preg_split('/\n|\r\n?/', trim($_POST['key']));
		if (count($keys)<=1) // convert to single key parameter if only one key
			$keys=$keys[0];
		
		$result=multichain('publishfrom', $_POST['from'], $_POST['name'], $keys, $data);
		
		if (no_displayed_error_result($publishtxid, $result))
			output_success_text('Item successfully published in transaction'.$publishtxid);
	}

	$labels=multichain_labels();

	no_displayed_error_result($liststreams, multichain('liststreams', '*', true));

	if (no_displayed_error_result($getaddresses, multichain('getaddresses', true))) {
		foreach ($getaddresses as $index => $address)
			if (!$address['ismine'])
				unset($getaddresses[$index]);
				
		if (no_displayed_error_result($listpermissions,
			multichain('listpermissions', 'send', implode(',', array_get_column($getaddresses, 'address')))
		))
			$sendaddresses=array_get_column($listpermissions, 'address');
	}
	
?>
<section id="main-content">
	<section class="wrapper">
		<div class="table-agile-info">
			<div class="panel panel-default">
                <div class="panel-heading" style="padding-top: 12px;">
                    <h3>  Publish to Stream  </h3>
                </div>
            </div>
					
					<form class="form-horizontal" method="post" enctype="multipart/form-data"  action="./?chain=<?php echo html($_GET['chain'])?>&page=<?php echo html($_GET['page'])?>">
						<div class="form-group">
						<div class="row row-width">
							<label for="from" class="col-lg-2 control-label">From address:</label>
							<div class="col-lg-10">
							<select class="form-control col-sm-8" name="from" id="from">
<?php
	foreach ($sendaddresses as $address) {
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
							<label for="name" class="col-lg-2 control-label">To stream:</label>
							<div class="col-lg-10">
							<select class="form-control col-sm-8" name="name" id="name">
<?php
	foreach ($liststreams as $stream) 
		if ($stream['name']!='root') {
?>
								<option value="<?php echo html($stream['name'])?>"><?php echo html($stream['name'])?></option>
<?php
		}
?>						
							</select>
							</div>
						</div>
						</div>
						<div class="form-group">
						<div class="row row-width">
<?php
	if ($allow_multi_keys) {
?>

							<label for="key" class="col-lg-2 control-label">Optional keys:</label>
							<div class="col-lg-10">
								<textarea class="form-control col-sm-8" rows="3" name="key" id="key"></textarea>
								<span id="helpBlock" class="help-block small">To use multiple keys, enter one per line.</span>
							</div>

<?php
	} else {
?>

							<label for="key" class="col-lg-2 control-label">Optional key:</label>
							<div class="col-lg-10">
								<input class="form-control col-sm-8" name="key" id="key">
							</div>

<?php	
	}
?>
						</div>
						</div>
						<div class="form-group">
						<div class="row row-width">
							<label for="upload" class="col-lg-2 control-label">Upload file:<br/><span style="font-size:75%; font-weight:normal;">Max <?php echo floor($max_upload_size/1024)?> KB</span></label>
							<div class="col-lg-10">
								<input class="form-control col-sm-8" type="file" name="upload" id="upload">
							</div>
						</div>
						</div>

<?php
	if (multichain_has_json_text_items()) {
?>

						<!-- <div class="form-group">
							<label for="json" class="col-sm-2 control-label">Or JSON:</label>
							<div class="col-sm-9">
								<textarea class="form-control" rows="4" name="json" id="json"></textarea>
							</div>
						</div> -->

<?php
	}
?>

						<div class="form-group">
						<div class="row row-width">
							<label for="text" class="col-lg-2 control-label">Text:</label>
							<div class="col-lg-10">
								<textarea class="form-control col-sm-8" rows="4" name="text" id="text"></textarea>
							</div>
						</div>
						</div>

						<div class="form-group">
							<div class="row row-width">
								<div class="col-sm-3"></div>
								<div class="col-sm-offset-2 col-sm-6">
									<input class="btn btn-primary" style="float:right;" type="submit" name="publish" value="Publish Item">
								</div>
							</div>
						</div>
					</form>

				</div>
			</section>
			<div class="footer" style="margin-top: 50px"></div>
			</section>