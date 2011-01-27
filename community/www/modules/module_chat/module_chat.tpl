{* smarty template for chat module *}

{if $T_CHAT_MODULE_STATUS == "ON"}




<script type="text/javascript">
 var modulechatbaselink = '{$T_CHAT_MODULE_BASELINK}';
 var modulechatbasedir = '{$T_CHAT_MODULE_BASEDIR}';
 var modulechatbaseurl = '{$T_CHAT_MODULE_BASEURL}';
 var ie = 0;
</script>
<link href="{$T_CHAT_MODULE_BASELINK}css/screen.css" rel="stylesheet" type="text/css">
<link href="{$T_CHAT_MODULE_BASELINK}css/chat.css" rel="stylesheet" type="text/css">
<!--[if IE ]>
<link type="text/css" rel="stylesheet" media="all" href="{$T_CHAT_MODULE_BASELINK}css/screen_ie.css" />
<![endif]-->
<script type="text/javascript" src="{$T_CHAT_MODULE_BASELINK}js/jquery.js"></script>
<script type="text/javascript" src="{$T_CHAT_MODULE_BASELINK}js/chat.js"></script>



<div id="chat_module">
 <div id="windowspace">
  <div id="windows"></div>
 </div>
 <div id="chat_bar" onclick="javascript:toggle_users()">

  <div id="user_list" >
   <div id="content" >
    <!-- Online Users displayed here -->
   </div>
  </div>
  <table width="100%" cellpadding="0" cellspacing="0">
  <tr>
  <td id="first">
   <span id="status" >
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   </span>
  </td>
  <td align="right">
  <a href="javascript:void(0)" onClick="javascript:on_off()"><img id="statusimg" src="{$T_CHAT_MODULE_BASELINK}img/onoff18.png"/></a>

  </td>
  </tr>
  </table>
 </div>
</div>
<comment>
<embed src="{$T_CHAT_MODULE_BASELINK}sound/msg.wav" autostart=false width=0 height=0 id="sound1"
enablejavascript="true">
</comment>
<!--[if IE]>
<bgsound id="sound">
<script>var ie=1;</script>
<![endif]-->
<script type="text/javascript">
 disableSelection(document.getElementById("chat_bar"))
</script>

{/if}
