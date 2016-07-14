<?php 
//Shortcode for Blog Posts
function kad_map_shortcode_function( $atts ) {
	extract(shortcode_atts(array(
		'height' => '300',
		'center' => '',
		'address' => 'USA',
		'title' => '',
		'address2' => '',
		'title2' => '',
		'address3' => '',
		'title3' => '',
		'address4' => '',
		'title4' => '',
		'zoom' => '15',
		'loadscripts' => 'true',
		'id' => (rand(10,100)),
		'maptype' => 'ROADMAP'
), $atts));
	if(empty($center)) {$center = $address;}

                	ob_start(); ?>
			<div id="map_address<?php echo esc_attr($id);?>" class="kad_google_map" style="height:<?php echo esc_attr($height);?>px; margin-bottom:20px;"></div>
			<?php if($loadscripts != 'false'){ ?>
		    <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
		    <?php } ?>
		    <script type="text/javascript">
					jQuery(window).load(function() {
						var isDraggable = jQuery(document).width() > 480 ? true : false;
			function kad_build_gmap() {
					jQuery('#map_address<?php echo $id;?>').gmap3({
			map: {
			    address:"<?php echo esc_attr($center);?>",
				options: {
              		zoom:<?php echo esc_attr($zoom);?>,
					draggable: isDraggable,
					mapTypeControl: true,
					mapTypeId: google.maps.MapTypeId.<?php echo esc_attr($maptype);?>,
					scrollwheel: false,
					panControl: true,
					rotateControl: false,
					scaleControl: true,
					streetViewControl: true,
					zoomControl: true
				}
			},
			marker:{
	            values:[
	            	{ address: "<?php echo $address;?>",
				 	    	data:"<div class='mapinfo'><?php if($title) echo '<h5>'.$title.'</h5>';?> <?php echo $address;?></div>",
				 	},
				 	<?php if($address2) {?> { address: "<?php echo $address2;?>",
				 	    	data:"<div class='mapinfo'><?php if($title2) echo '<h5>'.$title2.'</h5>';?> <?php echo $address2;?></div>",
				 	},
				 	<?php } ?>
				 	<?php if($address3) {?> { address: "<?php echo $address3;?>",
				 	    	data:"<div class='mapinfo'><?php if($title3) echo '<h5>'.$title3.'</h5>';?> <?php echo $address3;?></div>",
				 	},
				 	<?php } ?>
				 	<?php if($address4) {?> { address: "<?php echo $address4;?>",
				 	    	data:"<div class='mapinfo'><?php if($title4) echo '<h5>'.$title4.'</h5>';?> <?php echo $address4;?></div>",
				 	},
				 	<?php } ?>

				],
            options:{
              draggable: false,
            },
				events:{
	              click: function(marker, event, context){
	                var map = jQuery(this).gmap3("get"),
	                  infowindow = jQuery(this).gmap3({get:{name:"infowindow"}});
	                if (infowindow){
	                  infowindow.open(map, marker);
	                  infowindow.setContent(context.data);
	                } else {
	                  jQuery(this).gmap3({
	                    infowindow:{
	                      anchor:marker, 
	                      options:{content: context.data}
	                    }
	                  });
	                }
	              },
	              closeclick: function(){
	                var infowindow = jQuery(this).gmap3({get:{name:"infowindow"}});
	                if (infowindow){
	                  infowindow.close();
	                }
				  }
				}
	          }
	        });
		}
		kad_build_gmap();
		jQuery('.woocommerce-tabs ul.tabs li a' ).click( function() {
				jQuery('#map_address<?php echo esc_attr($id);?>').gmap3('destroy');
			    setTimeout(kad_build_gmap, 200);
			});
        
      });
			</script>
            		
	<?php  $output = ob_get_contents();
		ob_end_clean();
	return $output;
}