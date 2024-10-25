<?php
require_once("../config.php");
require_once("configAtk.php");

$tpl = new myTemplate("templates/halUtama.html");

if($userIdLogin != "")
{
	$tpl->AssignInclude("CONTENT_TENGAH","templates/halAtkRequest.html");
}

if($aksiGet == "atkRequest")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halCartAndItem.html");
}

if($aksiGet == "masterItem")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halMasterItem.html");
}

if($aksiGet == "stockReport")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halStockReport.html");
}

if($aksiGet == "request")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halRequest.html");
}

if($aksiGet == "trans")
{
	$tpl->AssignInclude("CONTENT_BAWAH","templates/halTrans.html");
}

if($userIdLogin == "" || $aksiGet == "sessionExpired")
{
	header("location:../archives/");
	exit;
}

$tpl->prepare();
$tpl->Assign("idleAfter", $idleAfter);

if($aksiPost == "cekLogin")
{
	$tpl->Assign("aksiEcho", $CLogin->cekLogin($_POST, $CHistory, $CPublic));
}

if($userIdLogin != "")
{
	$tpl->Assign("userIdLogin", $userIdLogin);
	$tpl->Assign("welcomeUsername", $welcomeUsername );
	$tpl->Assign("spanLogout", "<span class=\"spanLogout\" onClick=\"formLogout.submit();\" title=\"Logout from Andhika Portal\">(&nbsp;Logout&nbsp;)</span>");
	
	$tpl->Assign("menuHome", $CPublic->menuHome(""));
	$tpl->Assign("menuNews", $CPublic->menuNews(""));
	$tpl->Assign("menuEmployee", $CPublic->menuEmployee(""));
	$tpl->Assign("menuArchives", $CPublic->menuArchives(""));
	$tpl->Assign("menuSetting", "");
}

if($aksiGet == "sessionExpired")
{
	$tpl->Assign("wrongPass", "<img src=\"../../picture/exclamation-red.png\" />&nbsp;Your session is expired!");
}

if($userJenis == "admin")
{
	//$tpl->Assign("menuSetting", "<li><a class=\"firLink\" href=\"../setting/\" title=\"Andhika Portal Admin Site\">Setting</a></li>");
	$tpl->Assign("menuSetting", $CPublic->menuSetting(""));
	$menuApplication = "<li class=\"has-sub\"><a href=\"#\"><span class=\"on\">Application</span></a>
					<ul>
						".$COtherApp->menuOtherApp($userIdLogin)."
					</ul>
				</li>";
	$tpl->Assign("menuApplication", $menuApplication);
}

else if($userJenis == "user")
{
	if($userMenuSetting == "Y")
	{
		$tpl->Assign("menuSetting", $CPublic->menuSetting(""));
	}
	if($userMenuApplication == "Y")
	{
		$menuApplication = "<li class=\"has-sub\"><a href=\"#\"><span class=\"on\">Application</span></a>
					<ul>
						".$COtherApp->menuOtherApp($userIdLogin)."
					</ul>
				</li>";
		$tpl->Assign("menuApplication", $menuApplication);
	}
}

if($adminAtk == "Y")
{
	$tpl->Assign("tipeUser", "adminAtk");
	if($aksiGet == "atkRequest")
	{
		$tpl->Assign("btnQtyMasterxxx", "<button class=\"btnStandar\" onclick=\"parent.openThickboxWindow('', 'new','btnQtyMaster')\" style=\"width:45px;height:29px;\" title=\"Qty Master\">
				<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				  <tr>
					<td align=\"right\" width=\"20\"><img src=\"../picture/toggle-expand.png\" height=\"17\"/> </td>
					<td align=\"center\">Qty</td>
				  </tr>
				</table>
			</button>");
			
		$tpl->Assign("btnNewAtkxxx", "<button class=\"btnStandar\" onclick=\"parent.openThickboxWindow('', '','btnNewAtk')\" style=\"width:80px;height:29px;\" title=\"Input New Item\">
				<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
				  <tr>
					<td align=\"right\" width=\"20\"><img src=\"../picture/wooden-box--plus.png\"/> </td>
					<td align=\"center\">New ATK</td>
				  </tr>
				</table>
			</button>");
	}
	
	$tpl->Assign("btnMaster", "<button class=\"btnStandar\" onclick=\"loadUrl('index.php?aksi=masterItem'); return false;\" title=\"Master Item\">
            <table height=\"27\">
              <tr>
                <td align=\"center\" width=\"20\"><img src=\"picture/key--pencil.png\"/> </td>
                <td align=\"left\">Master&nbsp;</td>
              </tr>
            </table>
        </button>");
	
	$tpl->Assign("btnStock", "<button class=\"btnStandar\" onclick=\"loadUrl('index.php?aksi=stockReport'); return false;\" title=\"Stock Report\">
            <table height=\"27\">
              <tr>
                <td align=\"center\" width=\"20\"><img src=\"../picture/drawer-open.png\"/> </td>
                <td align=\"left\">Stock&nbsp;</td>
              </tr>
            </table>
        </button>");
	
	$tpl->Assign("btnRequestxxx", "<button class=\"btnStandar\" onclick=\"loadUrl('index.php?aksi=request'); return false;\" style=\"width:78px;height:29px;\" title=\"Request New Item\">
        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
          <tr>
            <td align=\"right\" width=\"20\"><img src=\"../picture/Gnome-Applications-Other-32.png\" height=\"17\"/> </td>
            <td align=\"center\">Request</td>
          </tr>
        </table>
        </button>");
								
	$tpl->Assign("btnGive", "<button id=\"btnGive\" class=\"btnStandarDisabled\" onclick=\"give();\" title=\"Ready item to order\" disabled>
                                    <table height=\"27\">
                                      <tr>
                                        <td align=\"center\" width=\"20\"><img src=\"picture/present.png\"/> </td>
                                        <td align=\"left\">Ready&nbsp;</td>
                                      </tr>
                                    </table>
                                </button>");
	$tpl->Assign("btnApprv", "<button id=\"btnApprv\" class=\"btnStandarDisabled\" onclick=\"aprvTrans();\" title=\"Approve Transaction\" disabled>
                                    <table height=\"27\">
                                      <tr>
                                        <td align=\"center\" width=\"20\"><img src=\"picture/tick-button.png\"/> </td>
                                        <td align=\"left\">Approve&nbsp;</td>
                                      </tr>
                                    </table>
                                </button>");
	$tpl->Assign("btnRefund", "<button id=\"btnRefund\" class=\"btnStandarDisabled\" onclick=\"refundTrans();\" title=\"Refund Some Transaction\" disabled>
		<table height=\"27\">
		  <tr>
			<td align=\"center\" width=\"20\"><img src=\"picture/arrow-return-180-left.png\"/></td>
			<td align=\"left\">Refund&nbsp;</td>
		  </tr>
		</table>
	</button>");
	
	$tpl->Assign("linkTransList", "templates/halTransList.php");
	$tpl->Assign("disReq", "disabled");
}
if($adminAtk == "N")
{
	$tpl->Assign("tipeUser", "userAtk");
	$tpl->Assign("btnRequestxxx", "<button class=\"btnStandar\" onclick=\"parent.openThickboxWindow('', '', 'newReq')\" style=\"width:78px;height:29px;\" title=\"Request New Item\">
        <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"100%\">
          <tr>
            <td align=\"right\" width=\"20\"><img src=\"../picture/Gnome-Applications-Other-32.png\" height=\"17\"/> </td>
            <td align=\"center\">Request</td>
          </tr>
        </table>
        </button>");
		
	$tpl->Assign("btnReturn", "<button id=\"btnReturn\" class=\"btnStandarDisabled\" onclick=\"cancelSts('return');return false;\" title=\"Return to Cart\" disabled>
                                    <table height=\"27\">
                                      <tr>
                                        <td align=\"center\" width=\"20\"><img src=\"picture/truck.png\"/></td>
                                        <td align=\"left\">Return&nbsp;</td>
                                      </tr>
                                    </table>
                                </button>");
	$tpl->Assign("btnReceived", "<button id=\"btnReceived\" class=\"btnStandarDisabled\" onclick=\"RcvTrans();\" title=\"Return to Cart\" disabled>
                                    <table height=\"27\">
                                      <tr>
                                        <td align=\"center\" width=\"20\"><img src=\"picture/box-share.png\"/></td>
                                        <td align=\"left\">Received&nbsp;</td>
                                      </tr>
                                    </table>
                                </button>");
		
	$tpl->Assign("linkTransList", "templates/halTransListUser.php");
}

if($aksiGet == "atkRequest")
{
	$tpl->Assign("halaman","ATK");
	
	$tpl->Assign("btnAtkReq", "<img src=\"../picture/Search2-32.png\" width=\"25\" style=\"vertical-align:bottom;\"/>
        <input type=\"text\" class=\"elementSearch\" id=\"paramText\" size=\"41\" onclick=\";\" onfocus=\"\" onkeyup=\"\" placeholder=\"Search . . .\" title=\"Search Item\">
       
		<select id=\"category\" class=\"elementMenu\" style=\"width:170px;height:29px;\" title=\"Choose Category\">
        	<option value=\"0\">-- ALL CATEGORY --</option>
            <option value=\"ATK\" selected=\"selected\"> ATK </option>
        </select>
        
        <button id=\"btnCari\" class=\"btnStandar\" onclick=\"cariAtk();\" title=\"Display Item\">
            <table height=\"27\">
              <tr>
                <td align=\"center\" width=\"20\"><img src=\"picture/arrow.png\"/></td>
                <td align=\"left\">GO&nbsp;</td>
              </tr>
            </table>
        </button>");
}

if($aksiGet != "atkRequest")
{
	$tpl->Assign("btnBack", "<button class=\"btnStandar\" onclick=\"loadUrl('index.php?aksi=atkRequest'); return false;\" title=\"Display Item\">
            <table height=\"27\">
              <tr>
                <td align=\"center\" width=\"20\"><img src=\"picture/arrow-180.png\"/> </td>
                <td align=\"left\">Back to Main Menu&nbsp;</td>
              </tr>
            </table>
        </button>");
}

if($aksiGet == "masterItem")
{
	$tpl->Assign("halaman","MASTER ITEM");
}

if($aksiGet == "stockReport")
{
	$tpl->Assign("halaman","STOCK REPORT");
	
	$tpl->Assign("optionMonth",$CReqAtk->optionMonth($CPublic));
	$tpl->Assign("yearMonth",$CReqAtk->yearMonthNow($CPublic));
	$tpl->Assign("stockMonth",$CReqAtk->monthBeforeNow($CPublic));
	
	$tpl->Assign("blnDisp",$CReqAtk->blnDisplay($CPublic));
}

if($aksiGet == "request")
{
	$tpl->Assign("halaman","REQUEST NEW ITEM");
	$tpl->Assign("menuThnReq", $CReqAtk->menuThnReq($adminAtk,$userIdLogin));
	$tpl->Assign("menuBlnReq", $CReqAtk->menuBlnReq($adminAtk,$userIdLogin));
	$tpl->Assign("menuTglReq", $CReqAtk->menuTglReq($adminAtk,$userIdLogin));
	$tpl->Assign("menuStatusReq", $CReqAtk->menuStatusReq($adminAtk,$userIdLogin));
	$tpl->Assign("btnNewReq", "<button class=\"btnStandar\" id=\"btnAdd\" onclick=\"openThickboxWindowReq('', '', 'newReq');\" title=\"Add a New Request\">
									<table height=\"27\">
									  <tr>
										<td align=\"center\" width=\"20\"><img src=\"picture/plus-button.png\"/> </td>
										<td align=\"left\">New Request&nbsp;</td>
									  </tr>
									</table>
								</button>");
	
	if($adminAtk == "Y")
	{
		$tpl->Assign("nameRequestReq", "<select id=\"nama\" class=\"elementMenu\" style=\"width:150px;height:29px;\" title=\"Choose Category\">
						<option value=\"00000\">All Requestor</option>
						".$CReqAtk->menuNameReq()."
					</select>");
		$tpl->Assign("btnNewReq", "<button class=\"btnStandarDisabled\" id=\"btnAdd\" onclick=\"openThickboxWindowReq('', '', 'newReq');\" title=\"Add a New Request\" disabled>
									<table height=\"27\">
									  <tr>
										<td align=\"center\" width=\"20\"><img src=\"picture/plus-button.png\"/> </td>
										<td align=\"left\">New Request&nbsp;</td>
									  </tr>
									</table>
								</button>");
	}
}

if($aksiGet == "trans")
{
	$tpl->Assign("halaman","TRANSACTION");
	$tpl->Assign("menuThn", $CReqAtk->menuThn($adminAtk,$userIdLogin));
	$tpl->Assign("menuBln", $CReqAtk->menuBln($adminAtk,$userIdLogin));
	$tpl->Assign("menuTgl", $CReqAtk->menuTgl($adminAtk,$userIdLogin));
	$tpl->Assign("menuStatus", $CReqAtk->menuStatus($adminAtk,$userIdLogin));
	
	if($adminAtk == "Y")
	{
		$tpl->Assign("nameTrans", "<select id=\"nama\" class=\"elementMenu\" style=\"width:150px;height:29px;\" title=\"Choose User\">
        	<option value=\"00000\">All Name</option>
            ".$CReqAtk->menuName()."
        </select>");
		
		$tpl->Assign("legendTransDt", "<div style=\"width:14px;height:14px;border:solid 1px #ccc;background-color:#FF464A;float:left;\">&nbsp;</div>
                                &nbsp;Cannot process. Not enough stock.&nbsp;");
	}
}

$tpl->printToScreen();
?>