<script language=javascript>
var myPage = window.opener;

window.onload = setValues;

// var cellBgColor = myTable.selectedTD.bgColor;
var imageWidth = myPage.selectedImage.width;
var imageHeight = myPage.selectedImage.height;
var imageAlign = myPage.selectedImage.align;
var imageBorder = myPage.selectedImage.border;
var imageAltTag = myPage.selectedImage.alt;
var imageHspace = myPage.selectedImage.hspace;
var imageVspace = myPage.selectedImage.vspace;

function setValues() {

	imageForm.image_width.value = imageWidth;
	imageForm.image_height.value = imageHeight;

	if (imageBorder == "") {
		imageBorder = "0"
	}

	imageForm.border.value = imageBorder;
	imageForm.alt_tag.value = imageAltTag;
	imageForm.hspace.value = imageHspace;
	imageForm.vspace.value = imageVspace;
	// tableForm.cell_width.value = cellWidth;
	this.focus();
}

function doModify() {

	var error = 0;
	if (isNaN(imageForm.image_width.value) || imageForm.image_width.value < 0) {
		alert("[sTxtImageWidthErr]")
		error = 1
		imageForm.image_width.select()
		imageForm.image_width.focus()
	} else if (isNaN(imageForm.image_height.value) || imageForm.image_height.value < 0) {
		alert("[sTxtImageHeightErr]")
		error = 1
		imageForm.image_height.select()
		imageForm.image_height.focus()
	} else if (isNaN(imageForm.border.value) || imageForm.border.value < 0 || imageForm.border.value == "") {
		alert("[sTxtImageBorderErr]")
		error = 1
		imageForm.border.select()
		imageForm.border.focus()
	} else if (isNaN(imageForm.hspace.value) || imageForm.hspace.value < 0) {
		alert("[sTxtHorizontalSpacingErr]")
		error = 1
		imageForm.hspace.select()
		imageForm.hspace.focus()
	} else if (isNaN(imageForm.vspace.value) || imageForm.vspace.value < 0) {
		alert("[sTxtVerticalSpacingErr]")
		error = 1
		imageForm.vspace.select()
		imageForm.vspace.focus()
	}

	if (error != 1) {
        	myPage.selectedImage.width = imageForm.image_width.value
			myPage.selectedImage.height = imageForm.image_height.value
			myPage.selectedImage.alt = imageForm.alt_tag.value
			myPage.selectedImage.border = imageForm.border.value
    
	if (imageForm.hspace.value != "") {
			myPage.selectedImage.hspace = imageForm.hspace.value
	} else {
			myPage.selectedImage.removeAttribute('hspace',0)
	}

	if (imageForm.vspace.value != "") {
			myPage.selectedImage.vspace = imageForm.vspace.value
	} else {
			myPage.selectedImage.removeAttribute('vspace',0)
	}

	if (imageForm.align[imageForm.align.selectedIndex].text != "None") {
       		myPage.selectedImage.align = imageForm.align[imageForm.align.selectedIndex].text
	} else {
       		myPage.selectedImage.removeAttribute('align',0)
	}
        
    window.close()
	}
}

function printAlign() {
	if ((imageAlign != undefined) && (imageAlign != "")) {
		document.write('<option selected>' + imageAlign)
		document.write('<option>None')
	} else {
		document.write('<option selected>None')
	}
}

function printvAlign() {
	if ((imagevAlign != undefined) && (imagevAlign != "")) {
		document.write('<option selected>' + imagevAlign)
		document.write('<option>None')
	} else {
		document.write('<option selected>None')
	}
}

document.onkeydown = function () { 
			if (event.keyCode == 13) {	// ENTER
				doModify()			
			}
}

document.onkeypress = onkeyup = function () { 				
	if (event.keyCode == 13) {	// ENTER
	event.cancelBubble = true;
	event.returnValue = false;
	return false;				
	}
};

</script>

<form name=imageForm>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td width="15"><img src="_images/1x1.gif" width="15" height="1"></td>
	  <td class="heading1">[sTxtModifyImage]</td>
  </tr>
  <tr>
	<td>&nbsp;</td>
	  <td class="body">[sTxtModifyImageInst]<br>
		[sTxtCloseWin]</td>
  </tr>
  <tr>
	<td>&nbsp;</td>
	<td class="body">&nbsp;</td>
  </tr>
  <tr>
	<td>&nbsp;</td>
	<td class="body">
	  <table width="98%" border="0" cellspacing="0" cellpadding="0" class="bevel1">
  		<tr>
		    <td>&nbsp;&nbsp;[sTxtModifyImage]</td>
		</tr>
	  </table>
	</td>
  </tr>
  <tr>
	<td colspan="2"><img src="de_images/1x1.gif" width="1" height="10"></td>
  </tr>
  <tr>
	<td>&nbsp;</td>
	<td class="body">  
		<table border="0" cellspacing="0" cellpadding="5" width="98%" class="bevel2">
		  <tr>
		    <td class="body" width="80">[sTxtAltText]:</td>
			<td class="body" colspan="3">
			  <input type="text" name="alt_tag" size="50" class="Text220">
			</td>
		  </tr>
		  <tr>
			<td class="body" width="80">[sTxtImageWidth]:</td>
			<td class="body">
			  <input type="text" name="image_width" size="3" class="Text50" maxlength="3">
		  </td>
			<td class="body" width="80">[sTxtImageHeight]:</td>
			<td class="body">
			  <input type="text" name="image_height" size="3" class="Text50" maxlength="3">
			</td>
		  </tr>
		  <tr>
			<td class="body" width="80">[sTxtAlignment]:</td>
			<td class="body">
			  <SELECT class=text70 name=align>
			    <script>printAlign()</script>
			    <option>Baseline
			    <option>Top
			    <option>Middle
			    <option>Bottom
			    <option>TextTop
			    <option>ABSMiddle
			    <option>ABSBottom
			    <option>Left
			    <option>Right</option>
			  </select>
		  </td>
			<td class="body" width="80">[sTxtBorder]:</td>
			<td class="body">
			  <input type="text" name="border" size="3" class="Text50" maxlength="3">
			</td>
		  </tr>
		  <tr>
			<td class="body" width="80">[sTxtHorizontalSpacing]:</td>
			<td class="body">
			  <input type="text" name="hspace" size="3" class="Text50" maxlength="3">
			</td>
			<td class="body" width="80">[sTxtVerticalSpacing]:</td>
			<td class="body">
			  <input type="text" name="vspace" size="3" class="Text50" maxlength="3">
			</td>
		  </tr>
		  
	    </table>
	</td>
  </tr>
  <tr>
	<td colspan="2"><img src="de_images/1x1.gif" width="1" height="10"></td>
  </tr>
  <tr>
	<td>&nbsp;</td>
	<td>
	    <input type="button" name="modifyImage" value="[sTxtModifyImage]" class="Text120" onClick="javascript:doModify();">
	<input type="button" name="Submit" value="[sTxtCancel]" class="Text50" onClick="javascript:window.close()">
	</td>
  </tr>
</table>
</form>
