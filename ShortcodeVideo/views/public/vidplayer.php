<?php 
$float = isset($params['float'])
	? html_escape($params['float'])
	: 'left';
$width = isset($params['width'])
	? html_escape($params['width'])
	: '100%';
$height = isset($params['height'])
	? html_escape($params['height'])
	: '330';
$current = isset($params['current'])?html_escape($params['current']) : true;
$external = isset($params['ext'])?html_escape($params['ext']) : false;
?>
<?php 
	foreach($items as $item):
	set_current_record('Item',$item);
	if (metadata($item,'has files')){
	$files = $item->Files;
	    foreach($files as $file) {
	        if($file->hasThumbnail()) {
				$poster[]= file_display_url($file,'thumbnail');	
	        }
		}
	}
	endforeach;
	?>
	<?php if ($current && metadata('item',array('Streaming Video','Show Item'))=="True") { ?>
		<div class="displaySegmentLeftColumn">
	<?php }; ?>
		<div id="vid_player-<?php echo $id_suffix;?>" style="width:<?php echo $width;?>; height:<?php echo $height;?>;  float:<?php echo $float;?>; padding: 0 7% 0% 3%;">

			<video id="video<?php echo $id_suffix;?>" title="Video Player <?php echo $id_suffix;?>" class="video-js vjs-default-skin" 
			<?php if (!$external) {?>
			  controls 
			<?php };?>
			autoplay preload="auto" width=100% height=<?php echo $height;?>
			ytcontrols playsInline
			<?php if (isset($poster[0])){?>
			poster="<?php echo $poster[0];?>"
			<?php }?>
			<?php if (metadata('item', array('Streaming Video','Segment Type')) == 'youtube'){?>
				data-setup='{ "inactivityTimeout": 2000, "techOrder": ["youtube"], "sources": [{ "type": "video/youtube", "src": "<?php echo metadata('item',array('Streaming Video','HTTP Streaming Directory'));?><?php echo metadata('item',array('Streaming Video','HTTP Video Filename'));?>"}], "youtube": { "iv_load_policy": 1 }, "youtube": { "ytControls": 2 } }'>
			<?php } else { ?>
		  		data-setup='{"example_option":true, "inactivityTimeout": 2000, "nativeControlsForTouch": false}'>
				<?php if (metadata('item',array('Streaming Video','Video Streaming URL'))){?>
					<source src="<?php echo metadata('item',array('Streaming Video','Video Streaming URL'));?>/<?php echo metadata('item',array('Streaming Video','Video Type'));?><?php echo metadata('item',array('Streaming Video','Video Filename'));?>" type='rtmp/mp4'/>
				<?php } ?>
				<?php if (metadata('item',array('Streaming Video','HLS Streaming Directory'))){?>
					<source src="<?php echo metadata('item',array('Streaming Video','HLS Streaming Directory'));?><?php echo metadata('item',array('Streaming Video','HLS Video Filename'));?>" type='application/x-mpegurl'/>
				<?php } ?>
				<?php if (metadata('item',array('Streaming Video','HTTP Streaming Directory'))){?>
					<source src="<?php echo metadata('item',array('Streaming Video','HTTP Streaming Directory'));?><?php echo metadata('item',array('Streaming Video','HTTP Video Filename'));?>" type='video/mp4'/>
				<?php } ?>
			 <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
			<?php } ?>
			</video>
			</div>
			<?php if ($current && metadata('item',array('Streaming Video','Show Item'))=="True") { ?>
				</div>
			<?php }; ?>				
				<?php if ($current && metadata('item',array('Streaming Video','Show Item'))=="True"){?>
						<div class="displaySegmentRightColumn">
							<div class="roundMe" style="height: 320px;">
							<div class="tabContent" style="position:relative; top:0px; display:block;" >
								<h4>media Segments</h4>
						        <?php $orig_item=get_current_record('item');
								$orig_video = metadata("item", array("Streaming Video","Video Filename"));?>
							<?php
							foreach($items as $item):
							$estarttime = metadata('item',array('Streaming Video','Segment Start'));	
							set_current_record('Item',$item);
					        if (metadata('item',array('Streaming Video','Show Item'))=="True"){ ?>
							<ul id="eventList" style="-webkit-padding-start:10px;">	    								<li id="<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" class="liBullet <?php echo metadata('item',array('Streaming Video','Segment Type'));?>">
									<span class="bullet"></span>
									<div class="event<?php echo $id_suffix;?>" stime="<?php echo metadata('item',array('Streaming Video','Segment Start'));?>" etime="<?php echo metadata('item',array('Streaming Video','Segment End'));?>">
									<div class="eventLabel"><?php echo metadata('item',array('Streaming Video','Segment Type'));?> 
										<span class="timecode"><?php echo $this->getFormattedTimeString(metadata('item',array('Streaming Video','Segment Start')));?> - <?php echo $this->getFormattedTimeString(metadata('item',array('Streaming Video','Segment End')));?></span>
									</div>
							        <strong> <?php echo metadata('item',array('Dublin Core','Title'));?></strong><br>
							        <a class="header<?php echo $id_suffix;?>" title="Show Description" href='#'>Show Description | </a>
									<a id="doplay<?php echo $id_suffix;?>" title="Play Video Segment" stime="<?php echo metadata('item',array('Streaming Video','Segment Start'));?>" etime="<?php echo metadata('item',array('Streaming Video','Segment End'));?>" href='#'> Play Segment</a>
									<div class="hierarchyDesc" title="Video Segment Description" id="hiearchy_<?php echo metadata('item', array('Dublin Core', 'Identifier'));?>" style="display:none; margin-top: 10px; border-top:1px dotted #333; border-bottom:1px dotted #333; padding: 5%; width: 90%;">
										<?php echo metadata('item',array('Dublin Core', 'Description'));?> 
									</div>
									<div class="play<?php echo $id_suffix;?>" title="Play Video Segment" stime="<?php echo metadata('item',array('Streaming Video','Segment Start'));?>" etime="<?php echo metadata('item',array('Streaming Video','Segment End'));?>" style="color:darkblue; display:none;">
										<strong> Playing Segment </strong></font></div>
									</div>
								</li>
						    </ul> <!-- end of loop ul for display -->
					        <?php } else { ?>
										<p style="float:left"><?php echo $item["caption"];?></p>
									<?php };?>			
					        <?php
					 		endforeach;?>
					        	<hr style="color:lt-gray;"/>
							</div>
							</div>
						</div>
					<?php }; ?>
			</div>
<script type="text/javascript">
var startTime<?php echo $id_suffix;?> = new Array();
var endTime<?php echo $id_suffix;?> = new Array();
var finalendTime<?php echo $id_suffix;?> = 0;
<?php $a=0;
foreach($items as $vitem):
	set_current_record('Item',$vitem); ?>
		startTime<?php echo $id_suffix;?>[<?php echo $a;?>] = calculateTime("<?php echo metadata('item', array('Streaming Video','Segment Start'));?>");
		endTime<?php echo $id_suffix;?>[<?php echo $a;?>] = calculateTime("<?php echo metadata('item', array('Streaming Video','Segment End'));?>") ;
		if ((calculateTime("<?php echo metadata('item', array('Streaming Video','Segment End'));?>")) > (finalendTime<?php echo $id_suffix;?>)){
			finalendTime<?php echo $id_suffix;?> = calculateTime("<?php echo metadata('item', array('Streaming Video','Segment End'));?>") ;
		};
		<?php $a++; ?>	
<?php endforeach;?>
videojs("video<?php echo $id_suffix;?>").ready(function(){
  var myPlayer<?php echo $id_suffix;?> = this;
	jQuery('#video<?php echo $id_suffix;?> > div.vjs-control-bar > div.vjs-duration.vjs-time-controls.vjs-control > div').text(getFormattedTimeString(finalendTime<?php echo $id_suffix;?>));
	jQuery("#CurrentPos<?php echo $id_suffix;?>").val(getFormattedTimeString(myPlayer<?php echo $id_suffix;?>.currentTime()));
  // EXAMPLE: Start playing the video.
  myPlayer<?php echo $id_suffix;?>.currentTime(startTime<?php echo $id_suffix;?>[0]);
  myPlayer<?php echo $id_suffix;?>.pause();

});
var checkTime<?php echo $id_suffix;?> = function(){
  var myPlayer<?php echo $id_suffix;?> = videojs("video<?php echo $id_suffix;?>");
  var ctime = "0:00:00";
  var scenes;
  var sel;
  var i = 0;
  ctime = calculateTime(videojs("video<?php echo $id_suffix;?>").currentTime());
	jQuery('#video<?php echo $id_suffix;?> > div.vjs-control-bar > div.vjs-duration.vjs-time-controls.vjs-control > div').text(getFormattedTimeString(finalendTime<?php echo $id_suffix;?>));
	jQuery("#CurrentPos<?php echo $id_suffix;?>").val(getFormattedTimeString(myPlayer<?php echo $id_suffix;?>.currentTime()));
	if (myPlayer<?php echo $id_suffix;?>.currentTime() > finalendTime<?php echo $id_suffix;?>) {
		myPlayer<?php echo $id_suffix;?>.pause();
		myPlayer<?php echo $id_suffix;?>.on("pause",newEndTime<?php echo $id_suffix;?>);
		};
	if (myPlayer<?php echo $id_suffix;?>.currentTime() < startTime<?php echo $id_suffix;?>[0]) {
		myPlayer<?php echo $id_suffix;?>.pause();
		myPlayer<?php echo $id_suffix;?>.on("pause",newStartTime<?php echo $id_suffix;?>);
		};
		<?php if ($current) {?>
        	scenes = document.getElementsByClassName("play<?php echo $id_suffix;?>");
        	for (i; i < scenes.length; i++) {
            	sel = scenes[i];
            	if (calculateTime(sel.getAttribute('stime')) < ctime && calculateTime(sel.getAttribute('etime')) > ctime) 		
				{
					//$(sel).addClass('borderClass');
					$(sel).show();
					//$(sel).html("<b>Playing Segment</b>");
            	} else {
					//$(sel).removeClass('borderClass');
					$(sel).hide();				
            	}
        	}
		<?php 
		}
		?>
	};

var newStartTime<?php echo $id_suffix;?> = function(){
	var myPlayer<?php echo $id_suffix;?> = videojs("video<?php echo $id_suffix;?>");
	jQuery('#video<?php echo $id_suffix;?> > div.vjs-control-bar > div.vjs-duration.vjs-time-controls.vjs-control > div').text(getFormattedTimeString(finalendTime<?php echo $id_suffix;?>));
	jQuery("#CurrentPos<?php echo $id_suffix;?>").val(getFormattedTimeString(myPlayer<?php echo $id_suffix;?>.currentTime()));
	myPlayer<?php echo $id_suffix;?>.currentTime(startTime<?php echo $id_suffix;?>[0]);
	myPlayer<?php echo $id_suffix;?>.off("pause",newStartTime<?php echo $id_suffix;?>);
	myPlayer<?php echo $id_suffix;?>.off("pause",newEndTime<?php echo $id_suffix;?>);
		myPlayer<?php echo $id_suffix;?>.play();
};
var newEndTime<?php echo $id_suffix;?> = function(){
	var myPlayer<?php echo $id_suffix;?> = videojs("video<?php echo $id_suffix;?>");
	jQuery('#video<?php echo $id_suffix;?> > div.vjs-control-bar > div.vjs-duration.vjs-time-controls.vjs-control > div').text(getFormattedTimeString(finalendTime<?php echo $id_suffix;?>));
	jQuery("#CurrentPos<?php echo $id_suffix;?>").val(getFormattedTimeString(myPlayer<?php echo $id_suffix;?>.currentTime()));  
	myPlayer<?php echo $id_suffix;?>.currentTime(finalendTime<?php echo $id_suffix;?>);
	myPlayer<?php echo $id_suffix;?>.off("pause",newEndTime<?php echo $id_suffix;?>);
	myPlayer<?php echo $id_suffix;?>.off("pause",newStartTime<?php echo $id_suffix;?>);
	
};
function getElementsByClass(searchClass, domNode, tagName)
{
    if (domNode == null) {
        domNode = document;
    }
    if (tagName == null) {
        tagName = '*';
    }
    var el = new Array();
    var tags = domNode.getElementsByTagName(tagName);
    var tcl = " "+searchClass+" ";
	    for (i=0,j=0; i<tags.length; i++) {
	        var test = " " + tags[i].className + " ";
	        if (test.indexOf(tcl) != -1) {
	            el[j++] = tags[i];
	        }
	    }
	    return el;
	};
	                $(".header<?php echo $id_suffix;?>").click(function (event) {
					event.preventDefault();
					$header = $(this);
	                if ($header.html()=="Show Description | ") {
	                $header.html("Hide Description | ");
					$header.attr("title", "Hide Description");
	                } else {
	                $header.html("Show Description | ");
					$header.attr("title","Show Description");
	                };
	               //getting the next element
	                $content = $header.next().next();
	                //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
	                $content.toggle();
	                });

	                $("a#doplay<?php echo $id_suffix;?>").on("click", function (event) {
						event.preventDefault();
						$player = $(this);
						videojs("video<?php echo $id_suffix;?>").currentTime(calculateTime($(this).attr("stime")));
						videojs("video<?php echo $id_suffix;?>").play();
					});

	  		videojs("video<?php echo $id_suffix;?>").on("timeupdate",checkTime<?php echo $id_suffix;?>);
	
		</script>