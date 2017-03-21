<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>TurboDbAdmin</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script language="JavaScript" type="text/javascript" src="turbo/config.js"></script>
<script language="JavaScript" type="text/javascript" src="turbo/turbo.js"></script>

<style type="text/css">
body { 
	display: none;
}
</style>
<link href="turboDbAdmin.css" rel="stylesheet" type="text/css">
</head>
<body> 

<div id="bbevel" turboalign="top"></div>
<div turboalign="left" style="width: 180px;"><div turboalign="top" style="padding: 2px;">
	<a href="javascript:tda.tree.refresh()">Refresh</a></div><input turboalign="client" dojotype="TurboTree2" id="tree" onNodeSelected="tda.app.nodeSelected()" type="button"/></div> 
<div turboalign="left" dojoType="TurboSplitter"></div> 
<div turboalign="client">
	<div turboalign="client" dojoType="TurboPagebar" id="tabs" contentId="pages" canSelectTab="tda.app.canChangeSelection" onSelectTab="tda.app.selectTab">
		<input type="button" value="Data" group="TabsA" state="1" dojotype="TurboTab"/><input type="button" value="Schema" group="TabsA" dojoType="TurboTab"/><input type="button" value="SQL" group="TabsA" dojoType="TurboTab"/><input type="button" value="Export" group="TabsA" dojoType="TurboTab"/><input type="button" value="Options" group="TabsA" dojoType="TurboTab"/>
	</div>
	<div turboalign="client" id="pages">
		<div turboalign="client" widgetId="notebook" dojoType="TurboNotebook">
			<div class="noscroll" turboalign="client" dojoType="TurboModule" src="modules/data.html">loading...</div>
			<div class="noscroll" turboalign="client" dojoType="TurboModule" src="modules/schema.html">loading...</div>
			<div class="noscroll" turboalign="client" dojoType="TurboModule" src="modules/sql.html" delayed="true">loading...</div>
			<div class="noscroll" turboalign="client" dojoType="TurboModule" src="modules/exprt.html" delayed="true">loading...</div>
			<div class="noscroll" turboalign="client" dojoType="TurboModule" src="modules/options.html">loading...</div>
		</div>
	</div>
</div>
<div id="debugSplitter" style="display: none;" turboalign="bottom" dojoType="TurboSplitter"></div> 
<div id="debugWindow" turboalign="bottom" style="height: 100px; display: none;">
	<div turboalign="top" style="height: 18px; line-height: 18px;"><a href="javascript:clearDebug();">Clear Debug</a></div>
	<div turboalign="client" style="overflow: auto;"><div id="dojoDebug"></div></div>
</div>
<div turboalign="bottom" style="width: 100%; border: 2px solid #dedede;"></div> 
<div turboalign="bottom" style="height: 44px;">
	<div turboalign="client" style="overflow: auto; padding: 6px 4px 0 2px;"><div id="messages"></div></div>
</div>
</body>
</html>