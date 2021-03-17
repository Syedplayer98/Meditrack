<?php

	include 'issue-page.php';

	define('const_issue_custom_fields', 10);
	
	// $max_upload_size=multichain_max_data_size()-512; // take off space for file name and mime type

	$success=false; // set default value

	if (@$_POST['issueasset']) {
		$multiple=(int)round(1/$_POST['units']);
		
		$addresses=array( // array of addresses to issue units to
			$_POST['to'] => array(
				'issue' => array(
					'raw' => (int)($_POST['qty']*$multiple)
				)
			)
		);
		
		$custom=array();
		for ($index=0; $index<const_issue_custom_fields; $index++)
			if (strlen(@$_POST['key'.$index]))
				$custom[$_POST['key'.$index]]=$_POST['value'.$index];

		$datas=array( // to create array of data items
			array( // metadata for issuance details
				'create' => 'asset',
				'name' => $_POST['name'],
				'multiple' => $multiple,
				'open' => true,
				'details' => $custom,
			)
		);
		if (!count($datas[0]['details'])) // to ensure it's converted to empty JSON object rather than empty JSON array
			$datas[0]['details']=new stdClass();
		
		$success=no_displayed_error_result($issuetxid, multichain('createrawsendfrom', $_POST['from'], $addresses, $datas, 'send'));
				
		// if ($success)
		// 	output_success_text('Package successfully issued in transaction '.$issuetxid);
		issuePackage();
	}

	$getinfo=multichain_getinfo();

	$issueaddresses=array();
	$keymyaddresses=array();
	$receiveaddresses=array();
	$labels=array();

	if (no_displayed_error_result($getaddresses, multichain('getaddresses', true))) {

		if (no_displayed_error_result($listpermissions,
			multichain('listpermissions', 'issue', implode(',', array_get_column($getaddresses, 'address')))
		))
			$issueaddresses=array_get_column($listpermissions, 'address');
		
		foreach ($getaddresses as $address)
			if ($address['ismine'])
				$keymyaddresses[$address['address']]=true;
				
		if (no_displayed_error_result($listpermissions, multichain('listpermissions', 'receive')))
			$receiveaddresses=array_get_column($listpermissions, 'address');

		$labels=multichain_labels();
	}	
?>

<section id="main-content">
	<section class="wrapper">
		<div class="table-agile-info">
			<div class="panel panel-default">
                <div class="panel-heading" style="padding-top: 12px;">
                    <h3>  Issue Package  </h3>
                </div>
            </div>
					
			<form class="form-horizontal" method="post" enctype="multipart/form-data" action="./?chain=<?php echo html($_GET['chain'])?>&page=<?php echo html($_GET['page'])?>">
				<div class="form-group">
					<div class="row row-width">
						<label for="from" class="col-lg-2 control-label">From address:</label>
						<div class="col-lg-10">
							<select class="form-control col-sm-8" name="from" id="from">
							<?php
								foreach ($issueaddresses as $address) {
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
						<label for="name" class="col-lg-2 control-label">Package name:</label>
						<div class="col-lg-10">
							<input class="form-control col-sm-8" name="name" id="name" placeholder="Package">
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row row-width">
						<label for="qty" class="col-lg-2 control-label">Quantity:</label>
						<div class="col-lg-10">
							<input class="form-control col-sm-8" name="qty" id="qty" placeholder="1">
							<!-- <span id="helpBlock" class="help-block small text-muted"> &nbsp In this demo, the package will be open, allowing further issues in future.</span> -->
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="row row-width">
						<label for="units" class="col-lg-2 control-label">Units:</label>
						<div class="col-lg-10">
							<input class="form-control col-sm-8" name="units" id="units" placeholder="0.01">
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
				<!-- <div class="form-group">
					<div class="row row-width">
						<label for="upload" class="col-lg-2 control-label">Upload file:<br/><span style="font-size:75%; font-weight:normal;">Max <?php echo floor($max_upload_size/1024)?> KB</span></label>
						<div class="col-lg-10">
							<input class="form-control col-sm-8" type="file" name="upload" id="upload">
						</div>
					</div>
				</div> -->
				<?php
					for ($index=0; $index<const_issue_custom_fields; $index++) {
				?>
						<div class="form-group">
							<div class="row row-width">
								<div class="col-lg-3"><label for="key<?php echo $index?>" class="control-label"><?php echo $index ? '' : 'Custom fields:'?></label></div>
								<div class="col-lg-9">
									<div class="col-sm-3">
										<input class="form-control input-sm" name="key<?php echo $index?>" id="key<?php echo $index?>" placeholder="key">
									</div>
									<div class="col-sm-6">
										<input class="form-control input-sm" name="value<?php echo $index?>" id="value<?php echo $index?>" placeholder="value">
									</div>
								</div>
							</div>
						</div>
				<?php
					}
				?>
				<div class="form-group">
					<div class="row row-width">
						<div class="col-sm-3"></div>
						<div class="col-sm-offset-2 col-sm-5">
							<input class="btn btn-primary" style="float:right" type="submit" name="issueasset" value="Issue Package">
						</div>
					</div>
				</div>
			</form>
		</div>
	</section>
	<section class="wrapper">
		<div style="margin-bottom: 20px" class="table-agile-info">
			<div class="panel panel-default">
	            <div class="panel-heading" style="padding-top: 12px;">
                    <h3>  Issued Packages  </h3>
                </div>
            </div>
			
			<?php
				if (no_displayed_error_result($listassets, multichain('listassets', '*', true))) {

					foreach ($listassets as $asset) {
						$name=$asset['name'];
						$issuer=$asset['issues'][0]['issuers'][0];
						$h = $name." ".$asset['issueqty']." ".$asset['units']." ".format_address_html($issuer, @$keymyaddresses[$issuer], $labels);
					    $hash = hash('sha256',$h);
			?>
						<table class="table table-bordered table-condensed table-break-words <?php echo ($success && ($name==@$_POST['name'])) ? 'bg-success' : 'table-striped'?>">
							<tr>
								<th style="width:30%;">Name</th>
								<td><?php echo html($name)?> <?php echo $asset['open'] ? '' : '(closed)'?></td>
							</tr>
							<tr>
								<th>Quantity</th>
								<td><?php echo html($asset['issueqty'])?></td>
							</tr>
							<tr>
								<th>Units</th>
								<td><?php echo html($asset['units'])?></td>
							</tr>
							<tr>
								<th>Issuer</th>
								<td class="td-break-words small"><?php echo format_address_html($issuer, @$keymyaddresses[$issuer], $labels)?></td>
							</tr>
							<tr>
								<th>Hash</th>
								<td class="td-break-words small"><?php echo html($hash)?></td>
							</tr>
							<?php
								$details=array();
								$detailshistory=array();
								foreach ($asset['issues'] as $issue)
									foreach ($issue['details'] as $key => $value) {
										$detailshistory[$key][$issue['txid']]=$value;
										$details[$key]=$value;
									}
									if (count(@$detailshistory['@file'])) {
							?>
							<tr>
								<th>File:</th>
								<td><?php
								
									$countoutput=0;
									$countprevious=count($detailshistory['@file'])-1;

									foreach ($detailshistory['@file'] as $txid => $string) {
										$fileref=string_to_fileref($string);
										if (is_array($fileref)) {
										
											$file_name=$fileref['filename'];
											$file_size=$fileref['filesize'];
											
											if ($countoutput==1) // first previous version
												echo '<br/><small>('.$countprevious.' previous '.(($countprevious>1) ? 'files' : 'file').': ';
											
											echo '<a href="./download-file.php?chain='.html($_GET['chain']).'&txid='.html($txid).'&vout='.html($fileref['vout']).'">'.
												(strlen($file_name) ? html($file_name) : 'Download').
												'</a>'.
												( ($file_size && !$countoutput) ? html(' ('.number_format(ceil($file_size/1024)).' KB)') : '');
											
											$countoutput++;
										}
									}
									if ($countoutput>1)
										echo ')</small>';
								?></td>
							</tr>	
								<?php
									}
									foreach ($details as $key => $value) {
										if ($key=='@file')
											continue;
								?>
							<tr>
								<th><?php echo html($key)?></th>
								<td><?php echo html($value)?><?php
												
								if (count($detailshistory[$key])>1)
									echo '<br/><small>(previous values: '.html(implode(', ', array_slice(array_reverse($detailshistory[$key]), 1))).')</small>';
								
								?></td>
							</tr>
								<?php
									}
								?>
						</table>
			<?php
					}
				}
			?>
		</div>
	</section>
	<div class="footer" style="margin-top: 50px"></div>
</section>