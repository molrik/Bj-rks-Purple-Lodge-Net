<?php 
$origin = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $origin .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$origin = ($origin);
// vis kun test-udgivelser frem hvis admin er logget ind
if (!isset($_SESSION['user'])) {
	$relstat = " AND reltype<>'test'";	//kun aktive
} 

?>
	<?php if ($totalRows_songs) { //only if songs found ?>
	<table width="98%" border="0" cellspacing="0" cellpadding="2" class="all_sides">
	  <tr>
		<td class="header_row title_col">Title</td>
		<td class="header_row comment_col">Version</td>
		<td align="right" class="header_row time_col">Time</td>
		<td class="header_row artist_col">Artist</td>
		<td class="header_row loc_col">Location</td>
		<td align="right" class="header_row rel_col">Initially released</td>
        <!-- Editlink for admins begin -->
		<?php if (isset($_SESSION['user'])) { ?>
        <td align="right" class="header_row xxsmall"></td>
		<?php } ?> 
        <!-- Editlink for admins end -->

	  </tr>	
      <?php 
	  $rownum = 1; //init counter
	  do { //once pr found song 
		//$query_sr = "SELECT * FROM bpl_rel_song WHERE s_id=".intval($row_songs['id'])." ORDER BY id"; //simple version
		$query_sr = "SELECT DISTINCT rs.r_id, rs.s_id, bpl_rel.title, bpl_rel.reldate, bpl_rel.reltype, bpl_rel.media, bpl_rel.label, bpl_rel.serial, bpl_rel.country, bpl_rel.main FROM bpl_rel_song AS rs LEFT JOIN bpl_rel ON rs.r_id = bpl_rel.id WHERE s_id=".intval($row_songs['id']).$relstat." ORDER BY reltype ASC, main DESC, reldate ASC, media ASC";
		$sr = mysql_query($query_sr, $db_purplelodge_net) or die(mysql_error());
		$row_sr = mysql_fetch_assoc($sr);
		$totalRows_sr = mysql_num_rows($sr);
		$occnum = 1;
	  ?>
      <?php if ($prevsongtitle == trim($row_songs['title'])) { $repeat = true; } else { $repeat = false; } ?>
      <?php if($repeat) { ?>
	  <!-- <tr class="break" valign="top"><td><img src="../syspics/ned.gif" alt="ned" class="nedpil" align="right" /></td><td colspan="6"></td></tr> -->
	  <?php } ?>
      <?php if (($totalRows_sr) || (isset($_SESSION['user']))) { //if releases ?>
	  <tr class="<?php if ($rownum%2) { echo "oddrow"; } else { echo "evenrow"; } ?><?php if($repeat) echo " rep"; ?>" valign="top">
		<td class="title_col">
				<?php if ((trim($row_songs['alt_title'])<>"") || (trim($row_songs['mix_info'])<>"")) { 
                    echo "<acronym title=\"";   //begin acro tag
                    if (trim($row_songs['alt_title'])) { echo "aka ".$row_songs['alt_title']; } //alt title
                    $alt = 1;
                    if ((trim($row_songs['alt_title'])) && (trim($row_songs['mix_info']))) { echo " - "; }
                    if (trim($row_songs['mix_info'])) { echo "remix by ".$row_songs['mix_info']; } //remixer info
                    echo "\">"; //end ecro tag 
                } else {
                    $alt = 0;
                }?>            
				<?php if(!($repeat)) { echo $row_songs['title']; } ; $prevsongtitle = trim($row_songs['title']); ?>
				<?php if ($alt) { 
                	echo "</acronym>";
                } ?>            
        </td>
		<td class="comment_col"><?php if ($repeat && $alt) {
                    echo "<acronym title=\"";   //begin acro tag
                    if (trim($row_songs['alt_title'])) { echo "aka ".$row_songs['alt_title']; } //alt title
                    if ((trim($row_songs['alt_title'])) && (trim($row_songs['mix_info']))) { echo " - "; }
                    if (trim($row_songs['mix_info'])) { echo "remix by ".$row_songs['mix_info']; } //remixer info
                    echo "\">"; //end ecro tag          
        } ?><?php echo trim($row_songs['comment']); ?><?php if ($repeat && $alt) echo "</acronym>"; ?></td>
		<td class="time_col" align="right"><?php if (intval($row_songs['time'])) { echo timecalc($row_songs['time']); } ?></td>
		<td class="artist_col"><?php echo $row_songs['artist']; ?></td>
		<td class="loc_col">
		<?php do { 
			echo "<a href=\"disco.php?rid=".$row_sr['r_id'];
			if (trim($row_sr['reltype'])<>"rel") { echo "&amp;pro=1"; }
			echo "\" title=\"";
			if (trim($row_sr['reltype'])<>"rel") { echo reltypeshort($row_sr['reltype']).": "; }
			echo $row_sr['title']." - ".$row_sr['label']." ".$row_sr['serial']." (".$row_sr['country'].") - ".cutsekel($row_sr['reldate'])."\">".trans2pic(cleanmedialist(strtolower($row_sr['media'])))."</a> "; 
			if ($occnum==1) { $org_reldate = $row_sr['reldate']; } ; 
			$occnum++; 
		} while ($row_sr = mysql_fetch_assoc($sr)); ?>
        </td>
		<td align="right" class="rel_col"><?php echo datefix($org_reldate); ?></td>
        <!-- Editlink for admins begin -->
		<?php if (isset($_SESSION['user'])) { ?>
        <td align="right" class="edit_col">
		<a href="admin/edit_song.php?s_id=<?php echo $row_songs['id'] ?>&amp;origin=<?php echo $origin ?>"><img src="syspics/pencil.gif" alt="Edit this song" width="16" height="16" border="0" title="Edit this song" /></a>
		</td>
		<?php } ?> 
        <!-- Editlink for admins end -->
	  </tr>	
    <?php $rownum++;
	} //end if releases
	} while ($row_songs = mysql_fetch_assoc($songs)); ?>
	</table>
	  
	<?php } else { // statisk indhold ?> 
		<p>This is the song list section. </p>
		<p>Choose a starting letter above to display the list. </p>
	    <?php } ?>   	
