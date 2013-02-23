<?php
$query_rel_img = "SELECT * FROM bpl_img WHERE bpl_img.r_id = $r_id ORDER BY sort ASC";	//alle billeder tilknyttet udgivelsen
$rel_img = mysql_query($query_rel_img, $db_purplelodge_net) or die(mysql_error());
$row_rel_img = mysql_fetch_assoc($rel_img);
$totalRows_rel_img = mysql_num_rows($rel_img);
?>
<table width="98%" border="0" cellpadding="1" cellspacing="1" 
<?php if (substr_count(strtolower($_SERVER['PHP_SELF']),"test")) { 
	echo "style=\"background:url(test_bg.gif)\""; 
} else {

} ?> class="updrel" id="pic_table">
	<tr>
		<td>
		    <?php if($totalRows_rel_img) { ?>
			<table border="0" cellpadding="1" cellspacing="1" width="100%">
			  <tr>
			    <td class="xxsmall col_pop" align="right">id</td>
				<td class="xxsmall col_act" align="right">act</td>
				<td class="xxsmall col_spa"> </td>
				<td class="xxsmall col_thu">thumbnail</td>
				<td class="xxsmall col_img">image</td>
				<td class="xxsmall col_des">description</td>
				<td class="xxsmall col_sor">sort</td>
				<td class="xxsmall col_tou">touched</td>
				<td class="xxsmall col_upd">update</td>			
			  </tr>
			  <?php do { ?>
				  <?php if (isset($_GET['edit_rel_img']) && ($_GET['edit_rel_img']==$row_rel_img['id'])) { //showform ?>
				   <tr>
				    <td colspan="9">
					 <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" name="upd_rel_img">
					  <table border="0" cellpadding="1" cellspacing="1" width="100%">
						<tr>
						  <td class="xxsmall col_pop" align="right"><?php echo $row_rel_img['id']; ?></td>
						  <td class="xxsmall col_act" align="right">
						  <!-- slette -->
						  <a href="<?php echo $_SERVER['PHP_SELF']."?del_img=".$row_rel_img['id']."&amp;rid=".$r_id ?>">
							<img src="../syspics/drop.png" id="delpic_img<?php echo $row_rel_img['id']; ?>" alt="drop" width="16" height="16" border="0" title="Click to delete image record <?php echo $row_rel_img['id']; ?>" />						  </a>
						  <!-- slette -->						  </td>
						  <td class="col_spa"><input name="rid" type="hidden" value="<?php echo $r_id; ?>" /> </td>
						  <td class="col_thu">
						  <input id="thumb_input" name="thumb_input" type="text" value="<?php echo $row_rel_img['thumb']; ?>" size="30" onfocus='javascript:ofi(this.id)' <?php echo chkthval($row_rel_img['thumb']) ?> />
					      <a href="javascript:open_filelist('img_popup_upload_thumbx.php','upd_rel_img.thumb_input');">
						  <img src="../syspics/folder.gif" alt="folder" border="0" onclick="javascript:ofi('thumb_input')" title="Click here to upload file or select from uploaded files" />
						  </a>						  
						  </td>
						  <td class="col_img">
						  	<input id="image_input" name="image_input" type="text" value="<?php echo $row_rel_img['image']; ?>" size="30" onfocus='javascript:ofi(this.id)' />
							<a href="javascript:open_filelist('img_popup_upload_imagez.php','upd_rel_img.image_input',document.getElementById('image_input').value);">
							<img src="../syspics/folder.gif" alt="folder" border="0" onclick="javascript:ofi('image_input')" title="Click here to upload file or select from uploaded files"  />
							</a>
						  </td>
						  <td class="col_des">
						  	<input id="descr_input" name="descr_input" type="text" value="<?php echo $row_rel_img['descr']; ?>" size="30" onfocus='javascript:ofi(this.id)' />						  </td>
						  <td class="xxsmall col_sor">
						  	<input id="sort_input" name="sort_input" type="text" value="<?php echo $row_rel_img['sort']; ?>" size="2" onfocus='javascript:ofi(this.id)' />						  </td>
						  <td class="xxsmall col_tou"><?php echo shortsdate($row_rel_img['touch']); ?></td>
						  <td class="xxsmall col_upd" align="left"> 
					      <acronym title="Click to update the values of image record <?php echo $row_rel_img['id']; ?>">
						  <input id="Submit_uri" name="Submit_uri" type="submit" 
						  value="<?php echo $row_rel_img['id']; ?>" style="display:none" />
						  </acronym>						  </td>			
						</tr>
					   </table>	
					 </form>					
					 </td>
				   </tr>	
				<?php } else { // end showform begin normal state ?>
					<tr>
					  <td align="right" class="xxsmall">
					  <?php
					  $edit_pic = $_SERVER['PHP_SELF'];
					  $edit_pic .= "?" . htmlentities("act=updrel&id=".$r_id."&edit_rel_img=".$row_rel_img['id']); 
					  ?>
					  <acronym title="Click here edit image record <?php echo $row_rel_img['id']; ?>">
					  <a href="<?php echo $edit_pic ?>"><?php echo $row_rel_img['id']; ?></a>					  </acronym>					  </td>
					  <td align="right" class="xxsmall">					  
					  <!-- slette -->
					  <a href="<?php echo $_SERVER['PHP_SELF']."?del_img=".$row_rel_img['id']."&amp;rid=".$r_id ?>">
						<img src="../syspics/drop.png" id="delpic_img<?php echo $row_rel_img['id']; ?>" alt="drop" width="16" height="16" border="0" title="Click to delete image record <?php echo $row_rel_img['id']; ?>" />
						</a>
					  <!-- slette -->
					  </td>
					  <td> </td>
					  <td><?php chkth($row_rel_img['thumb']); ?></td>
					  <td><?php chkpic($row_rel_img['image']); ?></td>
					  <td><?php echo $row_rel_img['descr']; ?></td>
					  <td class="xxsmall"><?php echo $row_rel_img['sort']; ?></td>
					  <td class="xxsmall"><?php echo shortsdate($row_rel_img['touch']); ?></td>
					  <td class="xxsmall col_upd"> </td>			
					</tr>
				  <?php } // end normal state ?>
				<?php } while ($row_rel_img = mysql_fetch_assoc($rel_img)); ?>
					<tr>
					  <td align="right" class="xxsmall">
						  <acronym title="Click here to add a new image record">
						  <a href="<?php echo $_SERVER['PHP_SELF']."?add_img=true&amp;rid=".$r_id; ?>">
						  +
						  </a>
						  </acronym>
					  </td>
					  <td align="right" class="xxsmall"></td>
					  <td></td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					  <td class="xxsmall">&nbsp;</td>
					  <td class="xxsmall">&nbsp;</td>
					  <td class="xxsmall">&nbsp;</td>
			  </tr>		  
		  </table>
			<?php } else { // end if pics ?>
			<p>No image records yet</p>
			<p><acronym title="Click here to add a new image record"><a href="<?php echo $_SERVER['PHP_SELF']."?add_img=true&amp;rid=".$r_id; ?>">Add image record</a></acronym></p>
			<?php } ?>
        </td>
	</tr>
</table>
