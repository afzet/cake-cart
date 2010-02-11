function lz_chat_release_frame(_name)
{
	lz_chat_data.PermittedFrames--;
    if(lz_chat_data.PermittedFrames==-1)
		lz_chat_close();
	if(lz_chat_data.PermittedFrames == 0 && lz_chat_data.Status.Status == lz_chat_data.STATUS_START)
	{
		lz_chat_set_parentid();
		setTimeout("lz_chat_detect_sound();",30);
		if(!lz_chat_data.SetupError)
		{
			if(lz_geo_resolution_needed && lz_chat_data.ExternalUser.Session.GeoResolved.length != 7)
				lz_chat_geo_resolute();
			else
			{
				lz_chat_data.GeoResolution.SetStatus(7);
				setTimeout("lz_chat_startup();",500);
			}
		}
		else
			lz_chat_release(false,lz_chat_data.SetupError);
	}
	else if(lz_chat_data.PermittedFrames == 0 && lz_chat_data.Status.Status == lz_chat_data.STATUS_INIT)
		lz_chat_loaded();
}

function lz_chat_switch_file_upload()
{
	var frame_rows = frames['lz_chat_frame.3.2'].document.getElementById('lz_chat_frameset_chat').rows.split(",");
	if(frame_rows[2] != 0)
		frame_rows[2] = 0;
	if(frame_rows[3] != 0)
		frame_rows[3] = 0;
		
	if(frame_rows[1] == 0 && lz_chat_data.Status.Status == lz_chat_data.STATUS_STOPPED)
	{
		lz_chat_chat_alert(lz_chat_data.Language.RepresentativeLeft,frames['lz_chat_frame.3.2'].frames['lz_chat_frame.3.2.chat.4.0']);
		return;
	}
		
	if(!lz_chat_data.InternalUser.Available)
	{
		lz_chat_chat_alert(lz_chat_data.Language.WaitForRepresentative,frames['lz_chat_frame.3.2'].frames['lz_chat_frame.3.2.chat.4.0']);
		return;
	}
	
	frame_rows[1] = (frame_rows[1] == 0) ? 57 : 0;
	frames['lz_chat_frame.3.2'].document.getElementById('lz_chat_frameset_chat').rows = frame_rows.join(",");
}

function lz_chat_switch_rating()
{
	if(!lz_chat_data.InternalUser.Id.length > 0)
	{
		lz_chat_chat_alert(lz_chat_data.Language.WaitForRepresentative,frames['lz_chat_frame.3.2'].frames['lz_chat_frame.3.2.chat.4.0']);
		return;
	}
		
	var frame_rows = frames['lz_chat_frame.3.2'].document.getElementById('lz_chat_frameset_chat').rows.split(",");
	if(frame_rows[1] != 0)
		frame_rows[1] = 0;
	if(frame_rows[3] != 0)
		frame_rows[3] = 0;
	frame_rows[2] = (frame_rows[2] == 0) ? 57 : 0;
	frames['lz_chat_frame.3.2'].document.getElementById('lz_chat_frameset_chat').rows = frame_rows.join(",");
}

function lz_chat_switch_smiley_box()
{
	var frame_rows = frames['lz_chat_frame.3.2'].document.getElementById('lz_chat_frameset_chat').rows.split(",");
	if(frame_rows[1] != 0)
		frame_rows[1] = 0;
	if(frame_rows[2] != 0)
		frame_rows[2] = 0;
	
	frame_rows[3] = (frame_rows[3] == 0) ? 57 : 0;
	frames['lz_chat_frame.3.2'].document.getElementById('lz_chat_frameset_chat').rows = frame_rows.join(",");	
}

function lz_chat_release(_groupAvailable, _groupError)
{	
	lz_chat_set_status(lz_chat_data.STATUS_START);
	lz_chat_data.Status.Loaded = true;
	
	
	var goMessage = (!_groupAvailable && <!--offline_message_mode--> != 2 && !lz_chat_data.NoPreChatMessages);
	if(goMessage && lz_chat_data.GetParameters.indexOf("reset") != -1)
		goMessage = false;
	
	if(_groupError.length == 0)
	{
		if(lz_chat_data.DirectLogin)
			lz_chat_login(lz_chat_data.Groups.GetGroupById(frames['lz_chat_frame.3.2'].frames['lz_chat_frame.3.2.login.1.0'].document.getElementById('lz_chat_login_groups').value).Id);
		else if(!goMessage)
		{
			frames['lz_chat_frame.3.2'].frames['lz_chat_frame.3.2.login.1.0'].document.getElementById('lz_chat_login').style.visibility = 'visible';
			frames['lz_chat_frame.3.2'].frames['lz_chat_frame.3.2.login.1.0'].document.getElementById('lz_chat_login_button').disabled = false;
		}
	}
	
	if(!goMessage || _groupError.length != 0)
	{
		frames['lz_chat_frame.3.2'].frames['lz_chat_frame.3.2.login.1.0'].document.body.removeChild(frames['lz_chat_frame.3.2'].frames['lz_chat_frame.3.2.login.1.0'].document.getElementById('lz_chat_loading'));
		frames['lz_chat_frame.3.2'].frames['lz_chat_frame.3.2.login.1.0'].document.getElementById('lz_chat_login_details').style.display = "block";

		if(_groupError.length != 0)
			lz_chat_chat_alert(_groupError,frames['lz_chat_frame.3.2'].frames['lz_chat_frame.3.2.login.1.0'],-1);
	}
	else
		lz_chat_goto_message(false);
}

function lz_chat_change_url(_url)
{
	lz_chat_remove_from_parent();
	lz_chat_data.WindowNavigating = true;
	window.location.href = _url;
}