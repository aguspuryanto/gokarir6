

		  	<div id="sidebar" class="col-md-4 col-xs-12">
		  		<?php 
					$post_type = get_post_type();
					if($post_type != 'blog') : ?>
					<ul class="list-group">
						<li class="list-group-item active">IKLAN PREMIUM PLACEMENT</li>
						<?php
						//$exp = 30;
						$labelbook = '';
                        $booked = array('','','','','');

						$options = get_option('tp_option');
						for($i= 1; $i<= 9; $i++) {
							
							if($options["id_".$i]){
								$p = get_post($options["id_$i"]);
								
								$compny = str_replace("Futures", "", get_post_meta($p->ID, 'company', true));
								$expired_date = get_post_meta($p->ID, 'expired_date', true);							
								if($expired_date){
									$pfx_date = get_the_date( 'Y-m-d', $p->ID );
									$pfx_date = date('d, M Y', strtotime('+'.$expired_date.' days', strtotime($pfx_date)));
								}else{
									$pfx_date = get_the_date( 'Y-m-d', $p->ID );
									$pfx_date = date('d, M Y', strtotime('+30 days', strtotime($pfx_date)));
								}
								
								if($p->ID):
									if(in_array($i,$booked)) $labelbook = '<span class="label label-primary">Booked</span >';

									echo '<li class="list-group-item list-group-item-warning">
										<h4 class="list-group-item-heading"><a href="'.esc_url( get_permalink($p->ID) ).'">'.ordinal($i).'. '.strtoupper($compny).'</a></h4>
										<p class="list-group-item-text">FULL s/d '.$pfx_date.' '.premium_booked($i,$booked).' <span class="pull-right">Rp. '.hargaPaket($i).'</span></p>
									</li>';
								endif;
								
							}else{
								echo '<li class="list-group-item">
									<h4 class="list-group-item-heading"><a href="'.get_page_link(140).'">'.$i.'. PASANG IKLAN ANDA!</a></h4>
									<p class="list-group-item-text">TERSEDIA <span class="pull-right">Rp. '.hargaPaket($i).'</span></p>
								</li>';
							}
						}
						?>
						<li class="list-group-item list-group-item-success">
							<h4 class="list-group-item-heading"><a href="<?php echo get_page_link(140); ?>">PREMIUM</a></h4>
							<p class="list-group-item-text">TERSEDIA <span class="pull-right">Rp. 75.000</span></p>
						</li>
						<li class="list-group-item">
							<h4 class="list-group-item-heading"><a href="<?php echo get_page_link(140); ?>">REGULER (FREE)</a></h4>
							<p class="list-group-item-text">TERSEDIA <span class="pull-right">Rp. 0</span></p>
						</li>
						<li class="list-group-item text-center">
							<a href="<?php echo get_page_link(140); ?>" class="btn btn-lg btn-block btn-info"><i class="glyphicon glyphicon-send"></i> PASANG LOWONGAN</a>
							<a href="https://api.whatsapp.com/send?phone=6282244492100&&text=Hallo%20Surabayajobfair!%20Saya%20ingin%20pasang%20iklan%20lowongan" class="btn btn-lg btn-block btn-info" target="_blank">CHAT WHATSAPP ONLY: <br>0822-4449-2100</a>
						</li>
					</ul>
					<?php endif; ?>
		  	</div>