{{* Frameset for default design and normal left-to-right direction *}}

<frameset rows="72,*" border="0">
<frame name="HEADER" src="main/headerbanner.php"  scrolling="no">
<frameset cols="{{$gui_frame_left_nav_width}},*" border="{{$gui_frame_left_nav_border}}">

	<FRAME  NAME = "STARTPAGE" {{$sStartFrameSource}} MARGINHEIGHT="5"	MARGINWIDTH  ="5" SCROLLING="auto" frameborder="no">
	<FRAME NAME = "CONTENTS" {{$sContentsFrameSource}} frameborder="no">
</frameset>
</frameset>