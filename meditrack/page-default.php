<?php
	if (@$_POST['getnewaddress'])
		no_displayed_error_result($getnewaddress, multichain('getnewaddress'));
?>
<section id="main-content">
	<section class="wrapper">
        <div class="row">
        	<div class="table-agile-info col-lg-5" style="margin: 0px 40px">
                <div class="panel panel-default">
                    <div class="panel-heading" style="padding-top: 12px;">
                        <h2>  My Node  </h2>
                    </div>
                </div>
                <?php
                    $getinfo=multichain_getinfo();
                    if (is_array($getinfo)) {
                ?>
                <div class="row">
                    <div class="col-lg-5">
                        <p class="text-area"><b>Name</b></p>
                    </div>
                    <div class="col-lg-7" style="padding-left: 0px">
                        <p class="text-data"><?php echo html($getinfo['chainname'])?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5">
                        <p class="text-area"><b>Version</b></p>
                    </div>
                    <div class="col-lg-7" style="padding-left: 0px">
                        <p class="text-data"><?php echo html($getinfo['version'])?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5">
                        <p class="text-area"><b>Protocol</b></p>
                    </div>
                    <div class="col-lg-7" style="padding-left: 0px">
                        <p class="text-data"><?php echo html($getinfo['protocolversion'])?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5">
                        <p class="text-area"><b>Node Address</b></p>
                    </div>
                    <div class="col-lg-7" style="padding-left: 0px">
                        <p class="text-data"><?php echo html($getinfo['nodeaddress'])?></p>
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-lg-5">
                        <p class="text-area"><b>Blocks</b></p>
                    </div>
                    <div class="col-lg-7" style="padding-left: 0px">
                        <p class="text-data"><?php echo html($getinfo['blocks'])?></p>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-lg-5">
                        <p class="text-area"><b>Peers</b></p>
                    </div>
                    <div class="col-lg-7" style="padding-left: 0px">
                        <p class="text-data"><?php echo html($getinfo['connections'])?></p>
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
            <div class="table-agile-info col-lg-5 address-style" style="overflow: auto; height: 400px;">
                <div class="panel panel-default">
                    <div class="panel-heading" style="padding-top: 12px;">
                        <h2>  My Address  </h2>
                    </div>
                </div>
                <?php
                    if (no_displayed_error_result($getaddresses, multichain('getaddresses', true))) {
                        $addressmine=array();
                        
                        foreach ($getaddresses as $getaddress)
                            $addressmine[$getaddress['address']]=$getaddress['ismine'];
                        
                        $addresspermissions=array();
                        
                        if (no_displayed_error_result($listpermissions,
                            multichain('listpermissions', 'all', implode(',', array_keys($addressmine)))
                        ))
                            foreach ($listpermissions as $listpermission)
                                $addresspermissions[$listpermission['address']][$listpermission['type']]=true;
                        
                        no_displayed_error_result($getmultibalances, multichain('getmultibalances', array(), array(), 0, true));
                        
                        $labels=multichain_labels();
                    
                        foreach ($addressmine as $address => $ismine) {
                            if (count(@$addresspermissions[$address]))
                                $permissions=implode(', ', @array_keys($addresspermissions[$address]));
                            else
                                $permissions='none';
                                
                            $label=@$labels[$address];
                            $cansetlabel=$ismine && @$addresspermissions[$address]['send'];
			
            			if ($ismine && !$cansetlabel)
	            			$permissions.=' (cannot set label)';
                        ?>
                        <div class="row">
                        <?php
                			if (isset($label) || $cansetlabel) {
                        ?>
                            <div class="col-lg-5">
                                <p class="text-area"><b>Label</b></p>
                            </div>
                            <div class="col-lg-7" style="padding-left: 0px">
                                <p class="text-data"><?php echo html(@$label)?><?php
                    				if ($cansetlabel)
					                    echo (isset($label) ? ' &ndash; ' : '').
                                        '<a href="'.chain_page_url_html($chain, 'label', array('address' => $address)).'">'.
                                        (isset($label) ? 'change label' : 'Set label').
                                        '</a>';
								?></p>
                            </div>
                        <?php
                            }
                        ?>
                        </div>
                        <div class="row">
                            <div class="col-lg-5">
                                <p class="text-area"><b>Address</b></p>
                            </div>
                            <div class="col-lg-7" style="padding-left: 0px">
                                <p class="text-data" style="font-size: 11px"><?php echo html($address)?><?php echo $ismine ? '' : ' (watch-only)'?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-5">
                                <p class="text-area"><b>Permissions</b></p>
                            </div>
                            <div class="col-lg-7" style="padding-left: 0px">
                                <p class="text-data"><?php echo html($permissions)?><?php
            					echo ' &ndash; <a href="'.chain_page_url_html($chain, 'permissions', array('address' => $address)).'">change</a>';
							?></p>
                            <?php
                                if (isset($getmultibalances[$address])) {
                                    foreach ($getmultibalances[$address] as $addressbalance) {
                            ?>
                            <tr>
                            <th><?php echo html($addressbalance['name'])?></th>
                            <td><?php echo html($addressbalance['qty'])?></td>
                            </tr>
                            <?php
                                    }
                                }
                            ?>
                          </div>
                        </div>
                    <!-- <button class="btn-light">Get New Address</button> -->
                <?php
                        }
                    }
                ?>
                <form class="form-horizontal" method="post" action="<?php echo chain_page_url_html($chain)?>">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="btn btn-default adr-btn" style="margin-top: 20px;" id="b" name="getnewaddress" type="submit" value="Get new address">
                        </div>
                    </div>
                </form>
            </div>
		</div>
		<div class="table-agile-info" style="background-color: white;padding-left: 1em">
            <div class="panel panel-default">
                <div class="panel-heading" style="padding-top: 12px;background-color: #f1f1f1;margin-bottom: 10px">
                    <h2>  Connected Nodes  </h2>
                </div>
            </div>
            <?php
                if (no_displayed_error_result($peerinfo, multichain('getpeerinfo'))) {
            ?>
                    <div class="row">
                        <?php
                            foreach ($peerinfo as $peer) {
                        ?>
                                <div class="table-agile-info col-lg-5" style="margin: 0px 40px">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" style="padding-top: 12px;">
                                            <h3>  Node  </h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <p class="text-area"><b>Node IP Address</b></p>
                                        </div>
                                        <div class="col-lg-7" style="padding-left: 0px">
                                            <p class="text-data"><?php echo html(strtok($peer['addr'], ':'))?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <p class="text-area"><b>Hand Shake Address</b></p>
                                        </div>
                                        <div class="col-lg-7" style="padding-left: 0px">
                                            <p class="text-data" style="font-size: 11px"><?php echo html($peer['handshake'])?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5">
                                            <p class="text-area"><b>Latency</b></p>
                                        </div>
                                        <div class="col-lg-7" style="padding-left: 0px">
                                            <p class="text-data"><?php echo html(number_format($peer['pingtime'], 3))?> sec</p>
                                        </div>
                                    </div>
                                </div>
                        <?php
                            }
                        ?>
                    </div>
            <?php
                }
            ?>
        </div>
	</section>
	<div class="footer" style="margin-top: 50px"></div>
</section>